<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\BaseController;
use app\model\Category;
use app\validate\CategoryValidate;

/**
 * 后台分类管理控制器
 */
class BackendCategory extends BaseController
{
    /**
     * 分类列表页面
     * @return \think\Response
     */
    public function index()
    {
        return view('category/index');
    }

    /**
     * 获取分类列表数据（AJAX）
     * @return \think\response\Json
     */
    public function list()
    {
        // 获取搜索条件
        $categoryName = $this->request->get('category_name', '');
        $catStatus = $this->request->get('cat_status', '');
        
        // 构建查询
        $query = Category::whereNull('deleted_at')->order('sort_order', 'asc');
        
        // 搜索条件
        if ($categoryName) {
            $query->where('category_name', 'like', '%' . $categoryName . '%');
        }
        if ($catStatus !== '') {
            $query->where('cat_status', $catStatus);
        }
        
        // 获取所有数据
        $categories = $query->select()->toArray();
        
        // 构建树形结构
        $tree = Category::getTree($categories);
        
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
            $item['category_name_display'] = $prefix . $item['category_name'];
            $item['level_text'] = $this->getLevelText($item['level']);
            $item['show_type_text'] = Category::getShowTypeText($item['show_type']);
            $item['cat_status_text'] = Category::getStatusText($item['cat_status']);
            
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
     * 添加分类页面
     * @return \think\Response
     */
    public function add()
    {
        $parentId = $this->request->get('parent_id', 0);
        
        // 获取所有上级分类（用于选择父级）
        $allCategories = Category::whereNull('deleted_at')->order('sort_order', 'asc')->select()->toArray();
        $tree = Category::getTree($allCategories);
        
        return view('category/add', [
            'parent_id' => $parentId,
            'categories' => $tree,
        ]);
    }

    /**
     * 保存分类
     * @return \think\response\Json
     */
    public function save()
    {
        $data = $this->request->post();
        
        // 验证数据
        $validate = new CategoryValidate();
        if (!$validate->scene('add')->check($data)) {
            return json([
                'code' => 0,
                'msg'  => $validate->getError(),
                'data' => null,
            ]);
        }
        
        // 处理层级
        if ($data['parent_id'] == 0) {
            $data['level'] = 1;
        } else {
            $parentCategory = Category::find($data['parent_id']);
            if ($parentCategory) {
                $data['level'] = $parentCategory->level + 1;
            } else {
                $data['level'] = 1;
            }
        }
        
        // 检查层级是否超过3级
        if ($data['level'] > 3) {
            return json([
                'code' => 0,
                'msg'  => '分类最多支持3级',
                'data' => null,
            ]);
        }
        
        // 获取当前登录用户ID（实际项目中从session获取）
        $userId = session('user_id', 0);
        
        $category = new Category();
        $category->category_name = $data['category_name'];
        $category->parent_id     = $data['parent_id'] ?? 0;
        $category->show_type     = $data['show_type'] ?? 0;
        $category->cat_status    = $data['cat_status'] ?? 1;
        $category->level         = $data['level'];
        $category->sort_order    = $data['sort_order'] ?? 0;
        $category->description   = $data['description'] ?? '';
        $category->cat_remark    = $data['cat_remark'] ?? '';
        $category->created_at    = date('Y-m-d H:i:s');
        $category->created_by    = $userId;
        
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
     * 编辑分类页面
     * @param int $id 分类ID
     * @return \think\Response
     */
    public function edit($id)
    {
        $category = Category::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$category) {
            return json([
                'code' => 0,
                'msg'  => '分类不存在',
                'data' => null,
            ]);
        }
        
        // 获取所有上级分类（用于选择父级）
        $allCategories = Category::whereNull('deleted_at')->order('sort_order', 'asc')->select()->toArray();
        $tree = Category::getTree($allCategories);
        
        return view('category/edit', [
            'category'   => $category,
            'categories' => $tree,
        ]);
    }

    /**
     * 更新分类
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
        $validate = new CategoryValidate();
        if (!$validate->scene('edit')->check($data)) {
            return json([
                'code' => 0,
                'msg'  => $validate->getError(),
                'data' => null,
            ]);
        }
        
        $category = Category::where('id', $data['id'])->whereNull('deleted_at')->find();
        
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
        $childIds = Category::getChildIds($data['id']);
        if (in_array($data['parent_id'], $childIds)) {
            return json([
                'code' => 0,
                'msg'  => '不能将子分类设为父级',
                'data' => null,
            ]);
        }
        
        // 处理层级
        if ($data['parent_id'] == 0) {
            $data['level'] = 1;
        } else {
            $parentCategory = Category::find($data['parent_id']);
            if ($parentCategory) {
                $data['level'] = $parentCategory->level + 1;
            } else {
                $data['level'] = 1;
            }
        }
        
        // 检查层级是否超过3级
        if ($data['level'] > 3) {
            return json([
                'code' => 0,
                'msg'  => '分类最多支持3级',
                'data' => null,
            ]);
        }
        
        // 获取当前登录用户ID（实际项目中从session获取）
        $userId = session('user_id', 0);
        
        $category->category_name = $data['category_name'];
        $category->parent_id     = $data['parent_id'] ?? 0;
        $category->show_type     = $data['show_type'] ?? 0;
        $category->cat_status    = $data['cat_status'] ?? 1;
        $category->level         = $data['level'];
        $category->sort_order    = $data['sort_order'] ?? 0;
        $category->description   = $data['description'] ?? '';
        $category->cat_remark    = $data['cat_remark'] ?? '';
        $category->updated_at    = date('Y-m-d H:i:s');
        $category->updated_by    = $userId;
        
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
     * 删除分类（软删除）
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
        
        $category = Category::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$category) {
            return json([
                'code' => 0,
                'msg'  => '分类不存在',
                'data' => null,
            ]);
        }
        
        // 检查是否有子分类
        if (Category::hasChildren($id)) {
            return json([
                'code' => 0,
                'msg'  => '请先删除子分类',
                'data' => null,
            ]);
        }
        
        // 获取当前登录用户ID（实际项目中从session获取）
        $userId = session('user_id', 0);
        
        // 软删除
        $category->deleted_at = date('Y-m-d H:i:s');
        $category->deleted_by = $userId;
        $result = $category->save();
        
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
        
        $category = Category::where('id', $id)->whereNull('deleted_at')->find();
        
        if (!$category) {
            return json([
                'code' => 0,
                'msg'  => '分类不存在',
                'data' => null,
            ]);
        }
        
        // 获取当前登录用户ID（实际项目中从session获取）
        $userId = session('user_id', 0);
        
        $category->cat_status = $status;
        $category->updated_at = date('Y-m-d H:i:s');
        $category->updated_by = $userId;
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
        $categories = Category::whereNull('deleted_at')->order('sort_order', 'asc')->select()->toArray();
        $tree = Category::getTree($categories);
        
        return json([
            'code' => 0,
            'msg'  => 'success',
            'data' => $tree,
        ]);
    }
}
