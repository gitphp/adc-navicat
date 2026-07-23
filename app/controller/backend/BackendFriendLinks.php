<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\model\FriendLinks;
use app\validate\FriendLinksValidate;

/**
 * 后台友情链接管理控制器
 */
class BackendFriendLinks extends BackendBase
{
    /**
     * 友情链接列表页面
     * @return \think\Response
     */
    public function index()
    {
        $this->title = '友情链接管理';
        return $this->render('friendlinks/index');
    }

    /**
     * 获取友情链接列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取分页参数
        $page = $this->request->param('page', 1, 'intval');
        $limit = $this->request->param('limit', 10, 'intval');
        
        // 获取搜索条件
        $link_name = $this->request->param('link_name', '');
        $link_status = $this->request->param('link_status', '', 'intval');
        
        // 构建查询
        $query = FriendLinks::order('link_sort', 'asc')->order('id', 'asc');
        
        // 搜索条件
        if (!empty($link_name)) {
            $query->where('link_name', 'like', '%' . $link_name . '%');
        }
        if ($link_status !== '') {
            $query->where('link_status', $link_status);
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
     * 添加友情链接页面
     * @return \think\Response
     */
    public function add()
    {
        return view('friendlinks/add');
    }

    /**
     * 保存友情链接
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->param();
        
        // 验证数据
        $validate = new FriendLinksValidate();
        if (!$validate->scene('add')->check($data)) {
            return json([
                'code' => 0,
                'msg' => $validate->getError(),
                'data' => null,
            ]);
        }
        
        // 创建友情链接
        $friendLink = new FriendLinks();
        $friendLink->link_name = $data['link_name'];
        $friendLink->link_url = $data['link_url'];
        $friendLink->link_logo = $data['link_logo'] ?? '';
        $friendLink->link_desc = $data['link_desc'] ?? '';
        $friendLink->link_sort = $data['link_sort'] ?? 0;
        $friendLink->link_status = $data['link_status'] ?? 1;
        
        if ($friendLink->save()) {
            return json([
                'code' => 1,
                'msg' => '添加成功',
                'data' => $friendLink,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg' => '添加失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 编辑友情链接页面
     * @return \think\Response
     */
    public function edit()
    {
        $id = $this->request->param('id', 0, 'intval');
        $friendLink = FriendLinks::find($id);
        
        if (!$friendLink) {
            return '友情链接不存在';
        }
        
        return view('friendlinks/edit', [
            'friendLink' => $friendLink,
        ]);
    }

    /**
     * 更新友情链接
     * @return \think\response\Json
     */
    public function update()
    {
        $data = $this->request->param();
        
        // 验证数据
        $validate = new FriendLinksValidate();
        if (!$validate->scene('edit')->check($data)) {
            return json([
                'code' => 0,
                'msg' => $validate->getError(),
                'data' => null,
            ]);
        }
        
        $id = $data['id'];
        $friendLink = FriendLinks::find($id);
        
        if (!$friendLink) {
            return json([
                'code' => 0,
                'msg' => '友情链接不存在',
                'data' => null,
            ]);
        }
        
        // 更新数据
        $friendLink->link_name = $data['link_name'];
        $friendLink->link_url = $data['link_url'];
        $friendLink->link_logo = $data['link_logo'] ?? '';
        $friendLink->link_desc = $data['link_desc'] ?? '';
        $friendLink->link_sort = $data['link_sort'] ?? 0;
        $friendLink->link_status = $data['link_status'] ?? 1;
        
        if ($friendLink->save()) {
            return json([
                'code' => 1,
                'msg' => '更新成功',
                'data' => $friendLink,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg' => '更新失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 删除友情链接
     * @return \think\response\Json
     */
    public function del()
    {
        $id = $this->request->param('id', 0, 'intval');
        $friendLink = FriendLinks::find($id);
        
        if (!$friendLink) {
            return json([
                'code' => 0,
                'msg' => '友情链接不存在',
                'data' => null,
            ]);
        }
        
        if ($friendLink->delete()) {
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
     * 切换状态
     * @return \think\response\Json
     */
    public function status()
    {
        $id = $this->request->param('id', 0, 'intval');
        $status = $this->request->param('status', 0, 'intval');
        
        $friendLink = FriendLinks::find($id);
        
        if (!$friendLink) {
            return json([
                'code' => 0,
                'msg' => '友情链接不存在',
                'data' => null,
            ]);
        }
        
        $friendLink->link_status = $status;
        
        if ($friendLink->save()) {
            return json([
                'code' => 1,
                'msg' => $status == 1 ? '已启用' : '已禁用',
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