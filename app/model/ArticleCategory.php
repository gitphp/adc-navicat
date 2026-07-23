<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * 文章分类模型
 */
class ArticleCategory extends Model
{
    /**
     * 设置表名
     * @var string
     */
    protected $name = 'article_category';
    
    /**
     * 设置主键
     * @var string
     */
    protected $pk = 'id';
    
    /**
     * 软删除字段（datetime类型）
     * @var string
     */
    protected $deleteTime = 'deleted_at';
    protected $defaultSoftDelete = null;
    
    /**
     * 自动写入时间戳
     * @var bool
     */
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    
    /**
     * 时间字段格式化
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';
    
    /**
     * 获取分类树结构
     * @param array $categories 分类列表
     * @param string $parentId 父级ID
     * @param int $level 当前层级
     * @return array
     */
    public static function getTree(array $categories, string $parentId = '0', int $level = 1): array
    {
        $result = [];
        
        foreach ($categories as $category) {
            if ((string)$category['parent_id'] == $parentId) {
                $category['level'] = $level;
                $children = self::getTree($categories, (string)$category['id'], $level + 1);
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
     * @param string $id 分类ID
     * @param string $separator 分隔符
     * @return string
     */
    public static function getPathNames(string $id, string $separator = ' > '): string
    {
        $names = [];
        $category = self::find($id);
        
        while ($category) {
            array_unshift($names, $category->cat_name);
            if ($category->parent_id == 0) {
                break;
            }
            $category = self::find($category->parent_id);
        }
        
        return implode($separator, $names);
    }
    
    /**
     * 获取所有子分类ID（递归）
     * @param string $parentId 父级ID
     * @param array $ids 结果数组
     * @return array
     */
    public static function getChildIds(string $parentId, array &$ids = []): array
    {
        $children = self::where('parent_id', $parentId)->whereNull('deleted_at')->column('id');
        
        foreach ($children as $childId) {
            $ids[] = $childId;
            self::getChildIds((string)$childId, $ids);
        }
        
        return $ids;
    }
    
    /**
     * 获取一级分类
     * @return \think\Collection
     */
    public static function getTopLevel()
    {
        return self::where('parent_id', 0)->whereNull('deleted_at')->order('cat_sort', 'asc')->select();
    }
    
    /**
     * 获取子分类
     * @param string $parentId 父级ID
     * @return \think\Collection
     */
    public static function getChildren(string $parentId)
    {
        return self::where('parent_id', $parentId)->whereNull('deleted_at')->order('cat_sort', 'asc')->select();
    }
    
    /**
     * 判断是否有子分类
     * @param string $parentId 父级ID
     * @return bool
     */
    public static function hasChildren(string $parentId): bool
    {
        return self::where('parent_id', $parentId)->whereNull('deleted_at')->count() > 0;
    }
    
    /**
     * 获取状态文本
     * @param int $status
     * @return string
     */
    public static function getStatusText(int $status): string
    {
        return $status == 1 ? '启用' : '禁用';
    }
    
    /**
     * 获取状态样式
     * @param int $status
     * @return string
     */
    public static function getStatusClass(int $status): string
    {
        return $status == 1 ? 'layui-btn-normal' : 'layui-btn-danger';
    }
}