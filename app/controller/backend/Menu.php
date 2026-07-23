<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\model\AuthMenus;
use app\validate\MenuValidate;

/**
 * 后台菜单管理控制器
 */
class Menu extends BackendBase
{
    /**
     * 菜单列表页面
     * @return \think\Response
     */
    public function index()
    {
        $this->title = '菜单管理';
        return $this->render('menu/index');
    }

    /**
     * 获取菜单列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取分页参数
        $page = $this->request->get('page', 1);
        $limit = $this->request->get('limit', 10);
        
        // 获取搜索条件
        $menuName = $this->request->get('menu_name', '');
        $menuPath = $this->request->get('menu_path', '');
        $menuStatus = $this->request->get('menu_status', '');
        
        // 构建查询
        $query = AuthMenus::order('menu_sort', 'desc')->order('id', 'asc');
        
        // 搜索条件
        if ($menuName) {
            $query->where('menu_name', 'like', '%' . $menuName . '%');
        }
        if ($menuPath) {
            $query->where('menu_path', 'like', '%' . $menuPath . '%');
        }
        if ($menuStatus !== '') {
            $query->where('menu_status', $menuStatus);
        }
        
        // 分页查询
        $list = $query->paginate([
            'page'      => $page,
            'list_rows' => $limit,
        ]);
        
        // 处理数据
        $data = [];
        foreach ($list->items() as $menu) {
            // 获取父级名称
            $parentName = '-';
            if ($menu->parent_id > 0) {
                $parent = AuthMenus::find($menu->parent_id);
                if ($parent) {
                    $parentName = $parent->menu_name;
                }
            }
            
            $data[] = [
                'id'              => $menu->id,
                'parent_id'       => $menu->parent_id,
                'parent_name'     => $parentName,
                'menu_name'       => $menu->menu_name,
                'menu_icon'       => $menu->menu_icon,
                'menu_path'       => $menu->menu_path,
                'component'       => $menu->component,
                'permission_code' => $menu->permission_code,
                'menu_sort'       => $menu->menu_sort,
                'menu_status'     => $menu->menu_status,
                'menu_status_text'=> $menu->getStatusText(),
                'created_at'      => $menu->created_at,
                'updated_at'      => $menu->updated_at,
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
     * 获取树形菜单数据（AJAX）
     * @return \think\response\Json
     */
    public function tree()
    {
        $tree = AuthMenus::getTree();
        
        return json([
            'code' => 0,
            'msg'  => 'success',
            'data' => $tree,
        ]);
    }

    /**
     * 添加菜单页面
     * @return \think\Response
     */
    public function add()
    {
        // 获取父级菜单选项（带层级缩进）
        $parentOptions = AuthMenus::getOptionsWithLevel();
        
        return view('menu/add', [
            'parent_options' => $parentOptions,
        ]);
    }

    /**
     * 保存菜单
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->post();
        
        // 验证数据
        try {
            $this->validate($data, MenuValidate::class . '.add');
        } catch (\think\exception\ValidateException $e) {
            return json([
                'code' => 0,
                'msg'  => $e->getMessage(),
                'data' => null,
            ]);
        }
        
        // 处理parent_id
        $data['parent_id'] = isset($data['parent_id']) ? (int)$data['parent_id'] : 0;
        
        // 创建菜单
        $menu = AuthMenus::create($data);
        
        return json([
            'code' => 1,
            'msg'  => '添加成功',
            'data' => [
                'id' => $menu->id,
            ],
        ]);
    }

    /**
     * 编辑菜单页面
     * @param int $id 菜单ID
     * @return \think\Response
     */
    public function edit($id)
    {
        $menu = AuthMenus::find($id);
        
        if (!$menu) {
            return json([
                'code' => 0,
                'msg'  => '菜单不存在',
                'data' => null,
            ]);
        }
        
        // 获取父级菜单选项（排除当前菜单及其子级）
        $parentOptions = AuthMenus::getOptionsWithLevel(0, '', $id);
        
        return view('menu/edit', [
            'menu'           => $menu,
            'parent_options' => $parentOptions,
        ]);
    }

    /**
     * 更新菜单
     * @return \think\response\Json
     */
    public function update()
    {
        $data = $this->request->post();
        $id = $data['id'] ?? 0;
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少菜单ID',
                'data' => null,
            ]);
        }
        
        $menu = AuthMenus::find($id);
        
        if (!$menu) {
            return json([
                'code' => 0,
                'msg'  => '菜单不存在',
                'data' => null,
            ]);
        }
        
        // 验证数据
        try {
            $this->validate($data, MenuValidate::class . '.edit');
        } catch (\think\exception\ValidateException $e) {
            return json([
                'code' => 0,
                'msg'  => $e->getMessage(),
                'data' => null,
            ]);
        }
        
        // 处理parent_id
        $data['parent_id'] = isset($data['parent_id']) ? (int)$data['parent_id'] : 0;
        
        // 更新菜单
        $menu->save($data);
        
        return json([
            'code' => 1,
            'msg'  => '更新成功',
            'data' => null,
        ]);
    }

    /**
     * 删除菜单
     * @return \think\response\Json
     */
    public function del()
    {
        $id = $this->request->post('id', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少菜单ID',
                'data' => null,
            ]);
        }
        
        $menu = AuthMenus::find($id);
        
        if (!$menu) {
            return json([
                'code' => 0,
                'msg'  => '菜单不存在',
                'data' => null,
            ]);
        }
        
        // 检查是否有子菜单
        $childrenCount = AuthMenus::where('parent_id', $id)->count();
        if ($childrenCount > 0) {
            return json([
                'code' => 0,
                'msg'  => '该菜单下存在子菜单，无法删除',
                'data' => null,
            ]);
        }
        
        // 软删除
        $menu->delete();
        
        return json([
            'code' => 1,
            'msg'  => '删除成功',
            'data' => null,
        ]);
    }

    /**
     * 切换菜单状态
     * @return \think\response\Json
     */
    public function status()
    {
        $id = $this->request->post('id', 0);
        $status = $this->request->post('status', 0);
        
        if (!$id) {
            return json([
                'code' => 0,
                'msg'  => '缺少菜单ID',
                'data' => null,
            ]);
        }
        
        $menu = AuthMenus::find($id);
        
        if (!$menu) {
            return json([
                'code' => 0,
                'msg'  => '菜单不存在',
                'data' => null,
            ]);
        }
        
        // 更新状态
        $menu->menu_status = $status;
        $menu->save();
        
        return json([
            'code' => 1,
            'msg'  => '状态更新成功',
            'data' => null,
        ]);
    }
}
