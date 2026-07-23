<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\BaseController;
use app\model\AuthPermissions;
use app\validate\PermissionValidate;

/**
 * 后台权限管理控制器
 */
class Permission extends BaseController
{
    /**
     * 权限列表页面
     * @return \think\Response
     */
    public function index()
    {
        return view('permission/index');
    }

    /**
     * 获取权限列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取分页参数
        $page = $this->request->get('page', 1);
        $limit = $this->request->get('limit', 10);
        
        // 获取搜索条件
        $perName = $this->request->get('per_name', '');
        $perCode = $this->request->get('per_code', '');
        $perType = $this->request->get('per_type', '');
        $perStatus = $this->request->get('per_status', '');
        
        // 构建查询
        $query = AuthPermissions::order('per_sort', 'desc')->order('id', 'asc');
        
        // 搜索条件
        if ($perName) {
            $query->where('per_name', 'like', '%' . $perName . '%');
        }
        if ($perCode) {
            $query->where('per_code', 'like', '%' . $perCode . '%');
        }
        if ($perType) {
            $query->where('per_type', $perType);
        }
        if ($perStatus !== '') {
            $query->where('per_status', $perStatus);
        }
        
        // 分页查询
        $list = $query->paginate([
            'page'      => $page,
            'list_rows' => $limit,
        ]);
        
        // 处理数据
        $data = [];
        foreach ($list->items() as $permission) {
            // 获取父级名称
            $parentName = '-';
            if ($permission->parent_id > 0) {
                $parent = AuthPermissions::find($permission->parent_id);
                if ($parent) {
                    $parentName = $parent->per_name;
                }
            }
            
            $data[] = [
                'id'           => $permission->id,
                'parent_id'    => $permission->parent_id,
                'parent_name'  => $parentName,
                'per_name'     => $permission->per_name,
                'per_code'     => $permission->per_code,
                'per_type'     => $permission->per_type,
                'per_type_text'=> $permission->getTypeText(),
                'per_path'     => $permission->per_path,
                'per_method'   => $permission->per_method,
                'per_icon'     => $permission->per_icon,
                'per_sort'     => $permission->per_sort,
                'per_status'   => $permission->per_status,
                'per_status_text' => $permission->getStatusText(),
                'created_at'   => $permission->created_at,
                'updated_at'   => $permission->updated_at,
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
     * 获取树形权限数据（AJAX）
     * @return \think\response\Json
     */
    public function tree()
    {
        $tree = AuthPermissions::getTree();
        
        return json([
            'code' => 0,
            'msg'  => 'success',
            'data' => $tree,
        ]);
    }

    /**
     * 添加权限页面
     * @return \think\Response
     */
    public function add()
    {
        // 获取父级权限选项（仅菜单类型）
        $parentOptions = AuthPermissions::getAllOptions(0, 'menu');
        
        return view('permission/add', [
            'parent_options' => $parentOptions,
            'per_type_options' => AuthPermissions::getPerTypeOptions(),
        ]);
    }

    /**
     * 保存权限
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->post();
        
        // 验证数据
        try {
            $this->validate($data, PermissionValidate::class . '.add');
        } catch (\think\exception\ValidateException $e) {
            return json([
                'code' => 0,
                'msg'  => $e->getMessage(),
                'data' => null,
            ]);
        }
        
        // 检查权限标识是否已存在
        if (AuthPermissions::where('per_code', $data['per_code'])->find()) {
            return json([
                'code' => 0,
                'msg'  => '权限标识已存在',
                'data' => null,
            ]);
        }
        
        // 处理parent_id
        $data['parent_id'] = isset($data['parent_id']) ? (int)$data['parent_id'] : 0;
        
        // 创建权限
        $permission = AuthPermissions::create($data);
        
        return json([
            'code' => 1,
            'msg'  => '添加成功',
            'data' => [
                'id' => $permission->id,
            ],
        ]);
    }

    /**
     * 编辑权限页面
     * @param int $id 权限ID
     * @return \think\Response
     */
    public function edit($id)
    {
        $permission = AuthPermissions::find($id);
        
        if (!$permission) {
            return json([
                'code' => 0,
                'msg'  => '权限不存在',
                'data' => null,
            ]);
        }
        
        // 获取父级权限选项（排除当前权限及其子级）
        $parentOptions = AuthPermissions::getAllOptions($id, 'menu');
        
        return view('permission/edit', [
            'permission'      => $permission,
            'parent_options'  => $parentOptions,
            'per_type_options' => AuthPermissions::getPerTypeOptions(),
        ]);
    }

    /**
     * 更新权限
     * @return \think\response\Json
     */
    public function update()
    {
        $data = $this->request->post();
        $id = $data['id'] ?? 0;
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少权限ID',
                'data' => null,
            ]);
        }
        
        $permission = AuthPermissions::find($id);
        
        if (!$permission) {
            return json([
                'code' => 0,
                'msg'  => '权限不存在',
                'data' => null,
            ]);
        }
        
        // 验证数据
        try {
            $this->validate($data, PermissionValidate::class . '.edit');
        } catch (\think\exception\ValidateException $e) {
            return json([
                'code' => 0,
                'msg'  => $e->getMessage(),
                'data' => null,
            ]);
        }
        
        // 检查权限标识是否被其他权限使用
        $exists = AuthPermissions::where('per_code', $data['per_code'])
            ->where('id', '<>', $id)
            ->find();
        if ($exists) {
            return json([
                'code' => 0,
                'msg'  => '权限标识已存在',
                'data' => null,
            ]);
        }
        
        // 处理parent_id
        $data['parent_id'] = isset($data['parent_id']) ? (int)$data['parent_id'] : 0;
        
        // 更新权限
        $permission->save($data);
        
        return json([
            'code' => 1,
            'msg'  => '更新成功',
            'data' => null,
        ]);
    }

    /**
     * 删除权限
     * @return \think\response\Json
     */
    public function del()
    {
        $id = $this->request->post('id', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少权限ID',
                'data' => null,
            ]);
        }
        
        $permission = AuthPermissions::find($id);
        
        if (!$permission) {
            return json([
                'code' => 0,
                'msg'  => '权限不存在',
                'data' => null,
            ]);
        }
        
        // 检查是否有子权限
        $childrenCount = AuthPermissions::where('parent_id', $id)->count();
        if ($childrenCount > 0) {
            return json([
                'code' => 0,
                'msg'  => '该权限下存在子权限，无法删除',
                'data' => null,
            ]);
        }
        
        // 软删除
        $permission->delete();
        
        return json([
            'code' => 1,
            'msg'  => '删除成功',
            'data' => null,
        ]);
    }

    /**
     * 切换权限状态
     * @return \think\response\Json
     */
    public function status()
    {
        $id = $this->request->post('id', 0);
        $status = $this->request->post('status', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少权限ID',
                'data' => null,
            ]);
        }
        
        $permission = AuthPermissions::find($id);
        
        if (!$permission) {
            return json([
                'code' => 0,
                'msg'  => '权限不存在',
                'data' => null,
            ]);
        }
        
        // 更新状态
        $permission->per_status = $status;
        $permission->save();
        
        return json([
            'code' => 1,
            'msg'  => '状态更新成功',
            'data' => null,
        ]);
    }
}
