<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\model\ArticleCategory;
use app\validate\ArticleCategoryValidate;

/**
 * 后台文章分类管理控制器
 */
class BackendArticleCategory extends BackendBase
{
    /**
     * 文章分类列表页面
     * @return \think\Response
     */
    public function index()
    {
        $this->title = '文章分类管理';
        return $this->render('articlecategory/index');
    }

    /**
     * 获取文章分类列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取搜索条件
        $catName = $this->request->get('cat_name', '');
        $status = $this->request->get('status', '');
        
        // 构建查询
        $query = ArticleCategory::whereNull('deleted_at')->order('cat_sort', 'asc');
        
        // 搜索条件
        if ($catName) {
            $query->where('cat_name', 'like', '%' . $catName . '%');
        }
        if ($status !== '') {
            $query->where('status', $status);
        }
        
        // 获取所有数据
        $categories = $query->select()->toArray();
        
        // 构建树形结构
        $tree = ArticleCategory::getTree($categories);
        
        // 处理数据，添加层级缩进
        $data = $this->formatTree($tree);
        
        return json([
            'code'  => 0,
            'msg'   => 'success',
            'count' => count($data),
            'data'  => $data,
        ]);
    }
    
    /**
     * 格式化树形数据（扁平化）
     * @param array $tree
     * @param int $level
     * @return array
     */
    protected function formatTree(array $tree, int $level = 0): array
    {
        $result = [];
        
        foreach ($tree as $item) {
            $prefix = str_repeat('└── ', $level);
            $item['cat_name_display'] = $prefix . $item['cat_name'];
            $item['level_text'] = $this->getLevelText($item['level']);
            $item['status_text'] = ArticleCategory::getStatusText($item['status']);
            $item['status_class'] = ArticleCategory::getStatusClass($item['status']);
            
            $result[] = $item;
            
            if (isset($item['children']) && !empty($item['children'])) {
                $result = array_merge($result, $this->formatTree($item['children'], $level + 1));
            }
        }
        
        return $result;
    }
    
    /**
     * 获取级别文本
     * @param int $level
     * @return string
     */
    protected function getLevelText(int $level): string
    {
        $options = [
            1 => '一级分类',
            2 => '二级分类',
            3 => '三级分类',
        ];
        return $options[$level] ?? '未知级别';
    }

    /**
     * 添加文章分类页面
     * @return \think\Response
     */
    public function add()
    {
        $parentId = $this->request->get('parent_id', '0');
        
        // 获取所有上级分类（用于选择父级）
        $allCategories = ArticleCategory::whereNull('deleted_at')->order('cat_sort', 'asc')->select()->toArray();
        $tree = ArticleCategory::getTree($allCategories);
        
        return view('articlecategory/add', [
            'parent_id' => $parentId,
            'categories' => $tree,
        ]);
    }

    /**
     * 保存文章分类
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->post();
        
        // 验证数据
        $validate = new ArticleCategoryValidate();
        if (!$validate->scene('add')->check($data)) {
            return json([
                'code' => 0,
                'msg'  => $validate->getError(),
                'data' => null,
            ]);
        }
        
        $category = new ArticleCategory();
        $category->cat_name     = $data['cat_name'];
        $category->parent_id    = $data['parent_id'] ?? 0;
        $category->cat_url      = $data['cat_url'] ?? '';
        $category->description  = $data['description'] ?? '';
        $category->cat_sort     = $data['cat_sort'] ?? 0;
        $category->status       = $data['status'] ?? 1;
        
        $result = $category->save();
        
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
     * 编辑文章分类页面
     * @param string $id 分类ID
     * @return \think\Response
     */
    public function edit(string $id)
    {
        $category = ArticleCategory::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$category) {
            return json([
                'code' => 0,
                'msg'  => '分类不存在',
                'data' => null,
            ]);
        }
        
        // 获取所有上级分类（用于选择父级）
        $allCategories = ArticleCategory::whereNull('deleted_at')->order('cat_sort', 'asc')->select()->toArray();
        $tree = ArticleCategory::getTree($allCategories);
        
        return view('articlecategory/edit', [
            'category'   => $category,
            'categories' => $tree,
        ]);
    }

    /**
     * 更新文章分类
     * @return \think\response\Json
     */
    public function update()
    {
        $data = $this->request->post();
        
        if (!$data['id']) {
            return json([
                'code' => 0,
                'msg'  => '缺少分类ID',
                'data' => null,
            ]);
        }
        
        // 验证数据
        $validate = new ArticleCategoryValidate();
        if (!$validate->scene('edit')->check($data)) {
            return json([
                'code' => 0,
                'msg'  => $validate->getError(),
                'data' => null,
            ]);
        }
        
        $category = ArticleCategory::where('id', $data['id'])->whereNull('deleted_at')->find();
        
        if (!$category) {
            return json([
                'code' => 0,
                'msg'  => '分类不存在',
                'data' => null,
            ]);
        }
        
        // 检查是否将自己设为父级
        if ($data['parent_id'] == $data['id']) {
            return json([
                'code' => 0,
                'msg'  => '不能将自己设为父级分类',
                'data' => null,
            ]);
        }
        
        // 检查是否将子级设为父级（循环引用）
        $childIds = ArticleCategory::getChildIds($data['id']);
        if (in_array($data['parent_id'], $childIds)) {
            return json([
                'code' => 0,
                'msg'  => '不能将子分类设为父级',
                'data' => null,
            ]);
        }
        
        $category->cat_name     = $data['cat_name'];
        $category->parent_id    = $data['parent_id'] ?? 0;
        $category->cat_url      = $data['cat_url'] ?? '';
        $category->description  = $data['description'] ?? '';
        $category->cat_sort     = $data['cat_sort'] ?? 0;
        $category->status       = $data['status'] ?? 1;
        
        $result = $category->save();
        
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
     * 删除文章分类（软删除）
     * @return \think\response\Json
     */
    public function del()
    {
        $id = $this->request->post('id', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少分类ID',
                'data' => null,
            ]);
        }
        
        $category = ArticleCategory::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$category) {
            return json([
                'code' => 0,
                'msg'  => '分类不存在',
                'data' => null,
            ]);
        }
        
        // 检查是否有子分类
        if (ArticleCategory::hasChildren((string)$id)) {
            return json([
                'code' => 0,
                'msg'  => '请先删除子分类',
                'data' => null,
            ]);
        }
        
        // 软删除
        if ($category->delete()) {
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
     * 切换分类状态
     * @return \think\response\Json
     */
    public function status()
    {
        $id = $this->request->post('id', 0);
        $status = $this->request->post('status', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少分类ID',
                'data' => null,
            ]);
        }
        
        $category = ArticleCategory::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$category) {
            return json([
                'code' => 0,
                'msg'  => '分类不存在',
                'data' => null,
            ]);
        }
        
        $category->status = $status;
        $result = $category->save();
        
        if ($result) {
            return json([
                'code' => 1,
                'msg'  => '状态更新成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '状态更新失败',
                'data' => null,
            ]);
        }
    }
    
    /**
     * 获取分类树形结构（用于下拉选择）
     * @return \think\response\Json
     */
    public function tree()
    {
        $categories = ArticleCategory::whereNull('deleted_at')->order('cat_sort', 'asc')->select()->toArray();
        $tree = ArticleCategory::getTree($categories);
        
        return json([
            'code' => 0,
            'msg'  => 'success',
            'data' => $tree,
        ]);
    }
}