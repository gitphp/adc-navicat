<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\BaseController;
use app\model\ConArticle;
use app\model\Category;
use app\validate\ArticleValidate;

/**
 * 后台文章管理控制器
 */
class BackendArticle extends BaseController
{
    /**
     * 文章列表页面
     * @return \think\Response
     */
    public function index()
    {
        return view('article/index');
    }

    /**
     * 获取文章列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取分页参数
        $page = $this->request->get('page', 1);
        $limit = $this->request->get('limit', 10);
        
        // 获取搜索条件
        $title = $this->request->get('title', '');
        $categoryId = $this->request->get('category_id', '');
        $artStatus = $this->request->get('art_status', '');
        $isTop = $this->request->get('is_top', '');
        $authorName = $this->request->get('author_name', '');
        $startDate = $this->request->get('start_date', '');
        $endDate = $this->request->get('end_date', '');
        
        // 构建查询
        $query = ConArticle::whereNull('deleted_at')->order('is_top', 'desc')->order('created_at', 'desc');
        
        // 搜索条件
        if ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        }
        if ($categoryId !== '') {
            $query->where('category_id', $categoryId);
        }
        if ($artStatus !== '') {
            $query->where('art_status', $artStatus);
        }
        if ($isTop !== '') {
            $query->where('is_top', $isTop);
        }
        if ($authorName) {
            $query->where('author_name', 'like', '%' . $authorName . '%');
        }
        if ($startDate) {
            $query->where('created_at', '>=', $startDate . ' 00:00:00');
        }
        if ($endDate) {
            $query->where('created_at', '<=', $endDate . ' 23:59:59');
        }
        
        // 分页查询
        $list = $query->paginate([
            'page'      => $page,
            'list_rows' => $limit,
        ]);
        
        // 处理数据
        $data = [];
        foreach ($list->items() as $article) {
            $data[] = [
                'id'              => $article->id,
                'title'           => $article->title,
                'subtitle'        => $article->subtitle,
                'category_id'     => $article->category_id,
                'category_name'   => $this->getCategoryName($article->category_id),
                'author_id'       => $article->author_id,
                'author_name'     => $article->author_name,
                'source'          => $article->source,
                'art_status'      => $article->art_status,
                'art_status_text' => ConArticle::getStatusText($article->art_status),
                'is_top'          => $article->is_top,
                'is_top_text'     => $article->is_top == 1 ? '是' : '否',
                'is_original'     => $article->is_original,
                'is_original_text' => $article->is_original == 1 ? '原创' : '转载',
                'view_count'      => $article->view_count,
                'like_count'      => $article->like_count,
                'comment_count'   => $article->comment_count,
                'published_at'    => $article->published_at,
                'created_at'      => $article->created_at,
            ];
        }
        
        return json([
            'code'  => 0,
            'msg'   => 'success',
            'count' => $list->total(),
            'data'  => $data,
        ]);
    }
    
    /**
     * 获取分类名称
     * @param int $categoryId
     * @return string
     */
    protected function getCategoryName(int $categoryId): string
    {
        if ($categoryId == 0) {
            return '未分类';
        }
        $category = Category::find($categoryId);
        return $category ? $category->category_name : '未知分类';
    }

    /**
     * 添加文章页面
     * @return \think\Response
     */
    public function add()
    {
        // 获取所有分类（树形结构）
        $categories = Category::whereNull('deleted_at')->order('sort_order', 'asc')->select()->toArray();
        $categoryTree = Category::getTree($categories);
        
        return view('article/add', [
            'category_tree'         => $categoryTree,
            'content_type_options'  => ConArticle::getContentTypeOptions(),
            'status_options'        => ConArticle::getStatusOptions(),
            'source_options'        => ConArticle::getSourceOptions(),
        ]);
    }

    /**
     * 保存文章
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->post();
        
        // 验证数据
        $validate = new ArticleValidate();
        if (!$validate->scene('add')->check($data)) {
            return json([
                'code' => 0,
                'msg'  => $validate->getError(),
                'data' => null,
            ]);
        }
        
        // 获取当前登录用户信息
        $userInfo = session('user_info', []);
        $userId = $userInfo['id'] ?? 0;
        $userNick = $userInfo['user_nick'] ?? '';
        
        $article = new ConArticle();
        $article->title           = $data['title'];
        $article->subtitle        = $data['subtitle'] ?? '';
        $article->art_cover       = $data['art_cover'] ?? '';
        $article->art_content     = $data['art_content'];
        $article->content_type    = $data['content_type'] ?? 1;
        $article->summary         = $data['summary'] ?? $this->generateSummary($data['title'], $data['art_content']);
        $article->category_id     = $data['category_id'] ?? 0;
        $article->tag_ids         = isset($data['tag_ids']) ? (is_array($data['tag_ids']) ? $data['tag_ids'] : json_decode($data['tag_ids'], true)) : [];
        $article->author_id       = $userId;
        $article->author_name     = $userNick;
        $article->source          = $data['source'] ?? '原创';
        $article->source_url      = $data['source_url'] ?? '';
        $article->art_status      = $data['art_status'] ?? 1;
        $article->is_top          = $data['is_top'] ?? 0;
        $article->is_original     = $data['is_original'] ?? 1;
        $article->is_commentable  = $data['is_commentable'] ?? 1;
        $article->seo_title       = $data['seo_title'] ?? $data['title'];
        $article->seo_keywords    = $data['seo_keywords'] ?? '';
        $article->seo_description = $data['seo_description'] ?? $article->summary;
        $article->extra_fields    = isset($data['extra_fields']) ? (is_array($data['extra_fields']) ? $data['extra_fields'] : json_decode($data['extra_fields'], true)) : [];
        
        // 如果状态为已发布，记录发布时间
        if ($article->art_status == ConArticle::STATUS_PUBLISHED) {
            $article->published_at = date('Y-m-d H:i:s');
        }
        
        $result = $article->save();
        
        if ($result) {
            return json([
                'code' => 1,
                'msg'  => '添加成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '添加失败',
                'data' => null,
            ]);
        }
    }
    
    /**
     * 生成摘要
     * @param string $title
     * @param string $content
     * @param int $length
     * @return string
     */
    protected function generateSummary(string $title, string $content, int $length = 150): string
    {
        // 先去除HTML标签
        $text = strip_tags($content);
        // 去除空白字符
        $text = preg_replace('/\s+/', ' ', $text);
        // 截取指定长度
        if (mb_strlen($text) > $length) {
            $text = mb_substr($text, 0, $length) . '...';
        }
        return $text;
    }

    /**
     * 编辑文章页面
     * @param int $id 文章ID
     * @return \think\Response
     */
    public function edit($id)
    {
        $article = ConArticle::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$article) {
            return json([
                'code' => 0,
                'msg'  => '文章不存在',
                'data' => null,
            ]);
        }
        
        // 获取所有分类（树形结构）
        $categories = Category::whereNull('deleted_at')->order('sort_order', 'asc')->select()->toArray();
        $categoryTree = Category::getTree($categories);
        
        return view('article/edit', [
            'article'               => $article,
            'category_tree'         => $categoryTree,
            'content_type_options'  => ConArticle::getContentTypeOptions(),
            'status_options'        => ConArticle::getStatusOptions(),
            'source_options'        => ConArticle::getSourceOptions(),
        ]);
    }

    /**
     * 更新文章
     * @return \think\response\Json
     */
    public function update()
    {
        $data = $this->request->post();
        
        if (!$data['id']) {
            return json([
                'code' => 0,
                'msg'  => '缺少文章ID',
                'data' => null,
            ]);
        }
        
        // 验证数据
        $validate = new ArticleValidate();
        if (!$validate->scene('edit')->check($data)) {
            return json([
                'code' => 0,
                'msg'  => $validate->getError(),
                'data' => null,
            ]);
        }
        
        $article = ConArticle::where('id', $data['id'])->whereNull('deleted_at')->find();
        
        if (!$article) {
            return json([
                'code' => 0,
                'msg'  => '文章不存在',
                'data' => null,
            ]);
        }
        
        $article->title           = $data['title'];
        $article->subtitle        = $data['subtitle'] ?? '';
        $article->art_cover       = $data['art_cover'] ?? '';
        $article->art_content     = $data['art_content'];
        $article->content_type    = $data['content_type'] ?? 1;
        $article->summary         = $data['summary'] ?? $this->generateSummary($data['title'], $data['art_content']);
        $article->category_id     = $data['category_id'] ?? 0;
        $article->tag_ids         = isset($data['tag_ids']) ? (is_array($data['tag_ids']) ? $data['tag_ids'] : json_decode($data['tag_ids'], true)) : [];
        $article->source          = $data['source'] ?? '原创';
        $article->source_url      = $data['source_url'] ?? '';
        $article->is_top          = $data['is_top'] ?? 0;
        $article->is_original     = $data['is_original'] ?? 1;
        $article->is_commentable  = $data['is_commentable'] ?? 1;
        $article->seo_title       = $data['seo_title'] ?? $data['title'];
        $article->seo_keywords    = $data['seo_keywords'] ?? '';
        $article->seo_description = $data['seo_description'] ?? $article->summary;
        $article->extra_fields    = isset($data['extra_fields']) ? (is_array($data['extra_fields']) ? $data['extra_fields'] : json_decode($data['extra_fields'], true)) : [];
        
        // 如果状态变为已发布且之前不是已发布，记录发布时间
        if ($article->art_status != ConArticle::STATUS_PUBLISHED && $data['art_status'] == ConArticle::STATUS_PUBLISHED) {
            $article->published_at = date('Y-m-d H:i:s');
        }
        
        $article->art_status = $data['art_status'] ?? 1;
        
        $result = $article->save();
        
        if ($result) {
            return json([
                'code' => 1,
                'msg'  => '更新成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '更新失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 删除文章（软删除）
     * @return \think\response\Json
     */
    public function del()
    {
        $id = $this->request->post('id', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少文章ID',
                'data' => null,
            ]);
        }
        
        $article = ConArticle::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$article) {
            return json([
                'code' => 0,
                'msg'  => '文章不存在',
                'data' => null,
            ]);
        }
        
        // 软删除
        $result = $article->delete();
        
        if ($result) {
            return json([
                'code' => 1,
                'msg'  => '删除成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '删除失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 文章审核
     * @return \think\response\Json
     */
    public function review()
    {
        $id = $this->request->post('id', 0);
        $artStatus = $this->request->post('art_status', 0);
        $rejectReason = $this->request->post('reject_reason', '');
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少文章ID',
                'data' => null,
            ]);
        }
        
        $article = ConArticle::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$article) {
            return json([
                'code' => 0,
                'msg'  => '文章不存在',
                'data' => null,
            ]);
        }
        
        // 只有待审核状态的文章才能审核
        if ($article->art_status != ConArticle::STATUS_PENDING_REVIEW) {
            return json([
                'code' => 0,
                'msg'  => '当前文章状态不允许审核',
                'data' => null,
            ]);
        }
        
        // 审核驳回需要填写驳回原因
        if ($artStatus == ConArticle::STATUS_REJECTED && empty($rejectReason)) {
            return json([
                'code' => 0,
                'msg'  => '审核驳回需要填写驳回原因',
                'data' => null,
            ]);
        }
        
        // 获取当前登录用户信息
        $userInfo = session('user_info', []);
        $reviewerId = $userInfo['id'] ?? 0;
        
        $article->art_status = $artStatus;
        $article->reviewer_id = $reviewerId;
        $article->reviewed_at = date('Y-m-d H:i:s');
        $article->reject_reason = $rejectReason;
        
        // 如果审核通过直接发布
        if ($artStatus == ConArticle::STATUS_APPROVED) {
            $article->art_status = ConArticle::STATUS_PUBLISHED;
            $article->published_at = date('Y-m-d H:i:s');
        }
        
        $result = $article->save();
        
        if ($result) {
            $msg = $artStatus == ConArticle::STATUS_PUBLISHED ? '审核通过并发布成功' : ($artStatus == ConArticle::STATUS_REJECTED ? '审核驳回成功' : '审核完成');
            return json([
                'code' => 1,
                'msg'  => $msg,
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '审核失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 发布文章
     * @return \think\response\Json
     */
    public function publish()
    {
        $id = $this->request->post('id', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少文章ID',
                'data' => null,
            ]);
        }
        
        $article = ConArticle::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$article) {
            return json([
                'code' => 0,
                'msg'  => '文章不存在',
                'data' => null,
            ]);
        }
        
        // 只有草稿、审核通过状态才能发布
        if (!in_array($article->art_status, [ConArticle::STATUS_DRAFT, ConArticle::STATUS_APPROVED, ConArticle::STATUS_OFFLINE])) {
            return json([
                'code' => 0,
                'msg'  => '当前文章状态不允许发布',
                'data' => null,
            ]);
        }
        
        $article->art_status = ConArticle::STATUS_PUBLISHED;
        $article->published_at = date('Y-m-d H:i:s');
        
        $result = $article->save();
        
        if ($result) {
            return json([
                'code' => 1,
                'msg'  => '发布成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '发布失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 下线文章
     * @return \think\response\Json
     */
    public function offline()
    {
        $id = $this->request->post('id', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少文章ID',
                'data' => null,
            ]);
        }
        
        $article = ConArticle::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$article) {
            return json([
                'code' => 0,
                'msg'  => '文章不存在',
                'data' => null,
            ]);
        }
        
        // 只有已发布状态才能下线
        if ($article->art_status != ConArticle::STATUS_PUBLISHED) {
            return json([
                'code' => 0,
                'msg'  => '当前文章状态不允许下线',
                'data' => null,
            ]);
        }
        
        $article->art_status = ConArticle::STATUS_OFFLINE;
        
        $result = $article->save();
        
        if ($result) {
            return json([
                'code' => 1,
                'msg'  => '下线成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '下线失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 切换置顶状态
     * @return \think\response\Json
     */
    public function top()
    {
        $id = $this->request->post('id', 0);
        $isTop = $this->request->post('is_top', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少文章ID',
                'data' => null,
            ]);
        }
        
        $article = ConArticle::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$article) {
            return json([
                'code' => 0,
                'msg'  => '文章不存在',
                'data' => null,
            ]);
        }
        
        $article->is_top = $isTop;
        $result = $article->save();
        
        if ($result) {
            $msg = $isTop == 1 ? '置顶成功' : '取消置顶成功';
            return json([
                'code' => 1,
                'msg'  => $msg,
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '操作失败',
                'data' => null,
            ]);
        }
    }
    
    /**
     * 获取文章统计数据
     * @return \think\response\Json
     */
    public function stats()
    {
        $stats = [
            'total' => ConArticle::whereNull('deleted_at')->count(),
            'draft' => ConArticle::where('art_status', ConArticle::STATUS_DRAFT)->whereNull('deleted_at')->count(),
            'pending_review' => ConArticle::where('art_status', ConArticle::STATUS_PENDING_REVIEW)->whereNull('deleted_at')->count(),
            'published' => ConArticle::where('art_status', ConArticle::STATUS_PUBLISHED)->whereNull('deleted_at')->count(),
            'offline' => ConArticle::where('art_status', ConArticle::STATUS_OFFLINE)->whereNull('deleted_at')->count(),
        ];
        
        return json([
            'code' => 0,
            'msg'  => 'success',
            'data' => $stats,
        ]);
    }
}
