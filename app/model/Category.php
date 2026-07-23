<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * 分类模型
 */
class Category extends Model
{
    /**
     * 定义时间戳字段名
     * @var string[]
     */
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $deleteTime = 'deleted_at';
    
    /**
     * 自动写入时间戳
     * @var bool
     */
    protected $autoWriteTimestamp = false;
    
    /**
     * 隐藏字段
     * @var string[]
     */
    protected $hidden = ['deleted_at', 'deleted_by'];
    
    /**
     * 类型转换
     * @var array
     */
    protected $type = [
        'id'          => 'integer',
        'parent_id'   => 'integer',
        'show_type'   => 'integer',
        'cat_status'  => 'integer',
        'level'       => 'integer',
        'sort_order'  => 'integer',
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
        'deleted_by'  => 'integer',
    ];
    
    /**
     * 获取分类树结构
     * @param array $categories 分类列表
     * @param int $parentId 父级ID
     * @param int $level 当前层级
     * @return array
     */
    public static function getTree(array $categories, int $parentId = 0, int $level = 1): array
    {
        $result = [];
        
        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                $category['level'] = $level;
                $children = self::getTree($categories, $category['id'], $level + 1);
                if (!empty($children)) {
                    $category['children'] = $children;
                }
                $result[] = $category;
            }
        }
        
        return $result;
    }
    
    /**
     * 获取分类路径名称（面包屑）
     * @param int $id 分类ID
     * @param string $separator 分隔符
     * @return string
     */
    public static function getPathNames(int $id, string $separator = ' > '): string
    {
        $names = [];
        $category = self::find($id);
        
        while ($category) {
            array_unshift($names, $category->category_name);
            if ($category->parent_id == 0) {
                break;
            }
            $category = self::find($category->parent_id);
        }
        
        return implode($separator, $names);
    }
    
    /**
     * 获取所有子分类ID（递归）
     * @param int $parentId 父级ID
     * @param array $ids 结果数组
     * @return array
     */
    public static function getChildIds(int $parentId, array &$ids = []): array
    {
        $children = self::where('parent_id', $parentId)->whereNull('deleted_at')->column('id');
        
        foreach ($children as $childId) {
            $ids[] = $childId;
            self::getChildIds($childId, $ids);
        }
        
        return $ids;
    }
    
    /**
     * 获取指定层级的分类
     * @param int $level 层级
     * @return \think\Collection
     */
    public static function getByLevel(int $level)
    {
        return self::where('level', $level)->whereNull('deleted_at')->order('sort_order', 'asc')->select();
    }
    
    /**
     * 获取一级分类
     * @return \think\Collection
     */
    public static function getTopLevel()
    {
        return self::where('parent_id', 0)->whereNull('deleted_at')->order('sort_order', 'asc')->select();
    }
    
    /**
     * 获取子分类
     * @param int $parentId 父级ID
     * @return \think\Collection
     */
    public static function getChildren(int $parentId)
    {
        return self::where('parent_id', $parentId)->whereNull('deleted_at')->order('sort_order', 'asc')->select();
    }
    
    /**
     * 判断是否有子分类
     * @param int $parentId 父级ID
     * @return bool
     */
    public static function hasChildren(int $parentId): bool
    {
        return self::where('parent_id', $parentId)->whereNull('deleted_at')->count() > 0;
    }
    
    /**
     * 获取可见性类型文本
     * @param int $type
     * @return string
     */
    public static function getShowTypeText(int $type): string
    {
        $options = [
            0 => '全部可见',
            1 => '指定客户可见',
            2 => '指定客户不可见',
        ];
        return $options[$type] ?? '未知';
    }
    
    /**
     * 获取状态文本
     * @param int $status
     * @return string
     */
    public static function getStatusText(int $status): string
    {
        return $status == 1 ? '显示' : '隐藏';
    }
}
