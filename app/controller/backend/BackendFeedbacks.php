<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\model\Feedbacks;
use app\validate\FeedbacksValidate;

/**
 * 后台用户留言管理控制器
 */
class BackendFeedbacks extends BackendBase
{
    /**
     * 留言列表页面
     * @return \think\Response
     */
    public function index()
    {
        $this->title = '用户留言管理';
        return $this->render('feedbacks/index');
    }

    /**
     * 获取留言列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取分页参数
        $page = $this->request->param('page', 1, 'intval');
        $limit = $this->request->param('limit', 10, 'intval');
        
        // 获取搜索条件
        $fb_name = $this->request->param('fb_name', '');
        $fb_title = $this->request->param('fb_title', '');
        $fb_status = $this->request->param('fb_status', '', 'intval');
        
        // 构建查询
        $query = Feedbacks::order('created_at', 'desc');
        
        // 搜索条件
        if (!empty($fb_name)) {
            $query->where('fb_name', 'like', '%' . $fb_name . '%');
        }
        if (!empty($fb_title)) {
            $query->where('fb_title', 'like', '%' . $fb_title . '%');
        }
        if ($fb_status !== '') {
            $query->where('fb_status', $fb_status);
        }
        
        // 分页查询
        $list = $query->paginate([
            'list_rows' => $limit,
            'page' => $page,
        ]);
        
        // 获取分页信息
        $total = $list->total();
        $data = $list->items();
        
        return json([
            'code' => 0,
            'msg' => 'success',
            'count' => $total,
            'data' => $data,
        ]);
    }

    /**
     * 查看留言详情页面
     * @return \think\Response
     */
    public function view()
    {
        $id = $this->request->param('id', 0, 'intval');
        $feedback = Feedbacks::find($id);
        
        if (!$feedback) {
            return '留言不存在';
        }
        
        return view('feedbacks/view', [
            'feedback' => $feedback,
        ]);
    }

    /**
     * 回复留言页面
     * @return \think\Response
     */
    public function reply()
    {
        $id = $this->request->param('id', 0, 'intval');
        $feedback = Feedbacks::find($id);
        
        if (!$feedback) {
            return '留言不存在';
        }
        
        return view('feedbacks/reply', [
            'feedback' => $feedback,
        ]);
    }

    /**
     * 保存回复
     * @return \think\response\Json
     */
    public function saveReply()
    {
        $data = $this->request->param();
        
        // 验证数据
        $validate = new FeedbacksValidate();
        if (!$validate->scene('reply')->check($data)) {
            return json([
                'code' => 0,
                'msg' => $validate->getError(),
                'data' => null,
            ]);
        }
        
        $id = $data['id'];
        $feedback = Feedbacks::find($id);
        
        if (!$feedback) {
            return json([
                'code' => 0,
                'msg' => '留言不存在',
                'data' => null,
            ]);
        }
        
        // 更新数据
        $feedback->reply_content = $data['reply_content'];
        $feedback->fb_status = 1; // 标记为已处理
        $feedback->replied_at = date('Y-m-d H:i:s');
        
        if ($feedback->save()) {
            return json([
                'code' => 1,
                'msg' => '回复成功',
                'data' => $feedback,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg' => '回复失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 删除留言
     * @return \think\response\Json
     */
    public function del()
    {
        $id = $this->request->param('id', 0, 'intval');
        $feedback = Feedbacks::find($id);
        
        if (!$feedback) {
            return json([
                'code' => 0,
                'msg' => '留言不存在',
                'data' => null,
            ]);
        }
        
        if ($feedback->delete()) {
            return json([
                'code' => 1,
                'msg' => '删除成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg' => '删除失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 标记已处理
     * @return \think\response\Json
     */
    public function handle()
    {
        $id = $this->request->param('id', 0, 'intval');
        $feedback = Feedbacks::find($id);
        
        if (!$feedback) {
            return json([
                'code' => 0,
                'msg' => '留言不存在',
                'data' => null,
            ]);
        }
        
        $feedback->fb_status = 1;
        
        if ($feedback->save()) {
            return json([
                'code' => 1,
                'msg' => '已标记为已处理',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg' => '操作失败',
                'data' => null,
            ]);
        }
    }
}