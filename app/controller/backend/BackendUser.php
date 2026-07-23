<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\BaseController;
use app\model\User;
use app\model\AuthRole;
use app\model\AuthUserRole;

/**
 * 后台用户管理控制器
 */
class BackendUser extends BaseController
{
    /**
     * 用户列表页面
     * @return \think\Response
     */
    public function index()
    {
        return view('user/index');
    }

    /**
     * 获取用户列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取分页参数
        $page = $this->request->get('page', 1);
        $limit = $this->request->get('limit', 10);
        
        // 获取搜索条件
        $userName = $this->request->get('user_name', '');
        $userNick = $this->request->get('user_nick', '');
        $userMobile = $this->request->get('user_mobile', '');
        $userStatus = $this->request->get('user_status', '');
        
        // 构建查询
        $query = User::where('is_deleted', 0)->order('created_at', 'desc');
        
        // 搜索条件
        if ($userName) {
            $query->where('user_name', 'like', '%' . $userName . '%');
        }
        if ($userNick) {
            $query->where('user_nick', 'like', '%' . $userNick . '%');
        }
        if ($userMobile) {
            $query->where('user_mobile', 'like', '%' . $userMobile . '%');
        }
        if ($userStatus !== '') {
            $query->where('user_status', $userStatus);
        }
        
        // 分页查询
        $list = $query->paginate([
            'page'      => $page,
            'list_rows' => $limit,
        ]);
        
        // 处理数据
        $data = [];
        foreach ($list->items() as $user) {
            // 获取角色名称
            $roleIds = $user->getRoleIds();
            $roleNames = [];
            if (!empty($roleIds)) {
                $roles = AuthRole::whereIn('id', $roleIds)->column('role_name');
                $roleNames = $roles ? $roles : [];
            }
            
            $data[] = [
                'id'             => $user->id,
                'user_name'      => $user->user_name,
                'user_nick'      => $user->user_nick,
                'user_mobile'    => $user->user_mobile,
                'user_email'     => $user->user_email,
                'user_status'    => $user->user_status,
                'user_status_text' => $user->user_status == 1 ? '正常' : '禁用',
                'real_auth_status' => $user->real_auth_status,
                'real_auth_text' => $this->getAuthStatusText($user->real_auth_status),
                'role_names'     => implode(', ', $roleNames),
                'register_time'  => $user->register_time,
                'last_login_time' => $user->last_login_time,
                'created_at'     => $user->created_at,
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
     * 获取实名状态文本
     * @param int $status
     * @return string
     */
    protected function getAuthStatusText(int $status): string
    {
        $options = [
            0 => '未实名',
            1 => '待审核',
            2 => '已实名',
            3 => '实名驳回',
        ];
        return $options[$status] ?? '未知';
    }

    /**
     * 编辑用户页面（分配角色）
     * @param int $id 用户ID
     * @return \think\Response
     */
    public function edit($id)
    {
        $user = User::where('id', $id)->where('is_deleted', 0)->find();
        
        if (!$user) {
            return json([
                'code' => 0,
                'msg'  => '用户不存在',
                'data' => null,
            ]);
        }
        
        // 获取用户已分配的角色ID
        $userRoleIds = $user->getRoleIds();
        
        // 获取所有启用的角色
        $roles = AuthRole::where('role_status', 1)->order('role_sort', 'asc')->select();
        
        return view('user/edit', [
            'user'         => $user,
            'roles'        => $roles,
            'user_role_ids' => $userRoleIds,
        ]);
    }

    /**
     * 更新用户角色
     * @return \think\response\Json
     */
    public function updateRoles()
    {
        $userId = $this->request->post('user_id', 0);
        $roleIds = $this->request->post('role_ids', []);
        
        if (!$userId) {
            return json([
                'code' => 0,
                'msg'  => '缺少用户ID',
                'data' => null,
            ]);
        }
        
        $user = User::where('id', $userId)->where('is_deleted', 0)->find();
        
        if (!$user) {
            return json([
                'code' => 0,
                'msg'  => '用户不存在',
                'data' => null,
            ]);
        }
        
        // 转换为数组
        if (!is_array($roleIds)) {
            $roleIds = explode(',', $roleIds);
        }
        
        // 过滤无效ID
        $roleIds = array_filter($roleIds, function($id) {
            return is_numeric($id) && $id > 0;
        });
        
        // 更新用户角色
        $result = AuthUserRole::saveRoles($userId, $roleIds);
        
        if ($result) {
            return json([
                'code' => 1,
                'msg'  => '角色分配成功',
                'data' => null,
            ]);
        } else {
            return json([
                'code' => 0,
                'msg'  => '角色分配失败',
                'data' => null,
            ]);
        }
    }

    /**
     * 切换用户状态
     * @return \think\response\Json
     */
    public function status()
    {
        $id = $this->request->post('id', 0);
        $status = $this->request->post('status', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少用户ID',
                'data' => null,
            ]);
        }
        
        $user = User::where('id', $id)->where('is_deleted', 0)->find();
        
        if (!$user) {
            return json([
                'code' => 0,
                'msg'  => '用户不存在',
                'data' => null,
            ]);
        }
        
        // 更新状态
        $user->user_status = $status;
        $user->save();
        
        return json([
            'code' => 1,
            'msg'  => '状态更新成功',
            'data' => null,
        ]);
    }

    /**
     * 删除用户（软删除）
     * @return \think\response\Json
     */
    public function del()
    {
        $id = $this->request->post('id', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少用户ID',
                'data' => null,
            ]);
        }
        
        $user = User::where('id', $id)->where('is_deleted', 0)->find();
        
        if (!$user) {
            return json([
                'code' => 0,
                'msg'  => '用户不存在',
                'data' => null,
            ]);
        }
        
        // 软删除
        $user->is_deleted = 1;
        $user->save();
        
        return json([
            'code' => 1,
            'msg'  => '删除成功',
            'data' => null,
        ]);
    }
}
