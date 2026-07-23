<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\BaseController;
use app\model\AuthRole;
use app\validate\RoleValidate;

/**
 * 后台角色管理控制器
 */
class Role extends BaseController
{
    /**
     * 角色列表页面
     * @return \think\Response
     */
    public function index()
    {
        return view('role/index');
    }

    /**
     * 获取角色列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取分页参数
        $page = $this->request->get('page', 1);
        $limit = $this->request->get('limit', 10);
        
        // 获取搜索条件
        $roleName = $this->request->get('role_name', '');
        $roleCode = $this->request->get('role_code', '');
        $roleStatus = $this->request->get('role_status', '');
        
        // 构建查询
        $query = AuthRole::order('role_sort', 'asc')->order('created_at', 'desc');
        
        // 搜索条件
        if ($roleName) {
            $query->where('role_name', 'like', '%' . $roleName . '%');
        }
        if ($roleCode) {
            $query->where('role_code', 'like', '%' . $roleCode . '%');
        }
        if ($roleStatus !== '') {
            $query->where('role_status', $roleStatus);
        }
        
        // 分页查询
        $list = $query->paginate([
            'page'      => $page,
            'list_rows' => $limit,
        ]);
        
        // 处理数据
        $data = [];
        foreach ($list->items() as $role) {
            $data[] = [
                'id'              => $role->id,
                'role_name'       => $role->role_name,
                'role_code'       => $role->role_code,
                'role_type'       => $role->role_type,
                'role_type_text'  => $role->getTypeText(),
                'role_sort'       => $role->role_sort,
                'data_scope'      => $role->data_scope,
                'data_scope_text' => $role->getDataScopeText(),
                'role_status'     => $role->role_status,
                'role_status_text'=> $role->getStatusText(),
                'role_remark'     => $role->role_remark,
                'created_at'      => $role->created_at,
                'updated_at'      => $role->updated_at,
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
     * 添加角色页面
     * @return \think\Response
     */
    public function add()
    {
        return view('role/add', [
            'data_scope_options' => AuthRole::getDataScopeOptions(),
            'role_type_options'  => AuthRole::getRoleTypeOptions(),
        ]);
    }

    /**
     * 保存角色
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->post();
        
        // 验证数据
        try {
            $this->validate($data, RoleValidate::class . '.add');
        } catch (\think\exception\ValidateException $e) {
            return json([
                'code' => 0,
                'msg'  => $e->getMessage(),
                'data' => null,
            ]);
        }
        
        // 检查角色标识是否已存在
        if (AuthRole::where('role_code', $data['role_code'])->find()) {
            return json([
                'code' => 0,
                'msg'  => '角色标识已存在',
                'data' => null,
            ]);
        }
        
        // 处理scope_departments
        if (!empty($data['scope_departments'])) {
            $data['scope_departments'] = json_decode($data['scope_departments'], true);
        } else {
            $data['scope_departments'] = null;
        }
        
        // 创建角色
        $role = AuthRole::create($data);
        
        return json([
            'code' => 1,
            'msg'  => '添加成功',
            'data' => [
                'id' => $role->id,
            ],
        ]);
    }

    /**
     * 编辑角色页面
     * @param int $id 角色ID
     * @return \think\Response
     */
    public function edit($id)
    {
        $role = AuthRole::find($id);
        
        if (!$role) {
            return json([
                'code' => 0,
                'msg'  => '角色不存在',
                'data' => null,
            ]);
        }
        
        return view('role/edit', [
            'role'               => $role,
            'data_scope_options' => AuthRole::getDataScopeOptions(),
            'role_type_options'  => AuthRole::getRoleTypeOptions(),
        ]);
    }

    /**
     * 更新角色
     * @return \think\response\Json
     */
    public function update()
    {
        $data = $this->request->post();
        $id = $data['id'] ?? 0;
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少角色ID',
                'data' => null,
            ]);
        }
        
        $role = AuthRole::find($id);
        
        if (!$role) {
            return json([
                'code' => 0,
                'msg'  => '角色不存在',
                'data' => null,
            ]);
        }
        
        // 验证数据
        try {
            $this->validate($data, RoleValidate::class . '.edit');
        } catch (\think\exception\ValidateException $e) {
            return json([
                'code' => 0,
                'msg'  => $e->getMessage(),
                'data' => null,
            ]);
        }
        
        // 检查角色标识是否被其他角色使用
        $exists = AuthRole::where('role_code', $data['role_code'])
            ->where('id', '<>', $id)
            ->find();
        if ($exists) {
            return json([
                'code' => 0,
                'msg'  => '角色标识已存在',
                'data' => null,
            ]);
        }
        
        // 处理scope_departments
        if (!empty($data['scope_departments'])) {
            $data['scope_departments'] = json_decode($data['scope_departments'], true);
        } else {
            $data['scope_departments'] = null;
        }
        
        // 更新角色
        $role->save($data);
        
        return json([
            'code' => 1,
            'msg'  => '更新成功',
            'data' => null,
        ]);
    }

    /**
     * 删除角色
     * @return \think\response\Json
     */
    public function del()
    {
        $id = $this->request->post('id', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少角色ID',
                'data' => null,
            ]);
        }
        
        $role = AuthRole::find($id);
        
        if (!$role) {
            return json([
                'code' => 0,
                'msg'  => '角色不存在',
                'data' => null,
            ]);
        }
        
        // 系统内置角色不允许删除
        if ($role->role_type === AuthRole::ROLE_TYPE_SYSTEM) {
            return json([
                'code' => 0,
                'msg'  => '系统内置角色不允许删除',
                'data' => null,
            ]);
        }
        
        // 软删除
        $role->delete();
        
        return json([
            'code' => 1,
            'msg'  => '删除成功',
            'data' => null,
        ]);
    }

    /**
     * 切换角色状态
     * @return \think\response\Json
     */
    public function status()
    {
        $id = $this->request->post('id', 0);
        $status = $this->request->post('status', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少角色ID',
                'data' => null,
            ]);
        }
        
        $role = AuthRole::find($id);
        
        if (!$role) {
            return json([
                'code' => 0,
                'msg'  => '角色不存在',
                'data' => null,
            ]);
        }
        
        // 更新状态
        $role->role_status = $status;
        $role->save();
        
        return json([
            'code' => 1,
            'msg'  => '状态更新成功',
            'data' => null,
        ]);
    }
}
