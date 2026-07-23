<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 菜单/功能模型
 * @property int $id 主键ID
 * @property int $parent_id 父级菜单ID
 * @property string $menu_name 菜单名称
 * @property string $menu_icon 菜单图标
 * @property string $menu_path 前端路由路径
 * @property string $component 前端组件路径
 * @property string $permission_code 关联的权限标识
 * @property int $menu_sort 排序权重
 * @property int $menu_status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class AuthMenus extends Model
{
    use SoftDelete;
    
    // 设置表名
    protected $name = 'auth_menus';
    
    // 设置主键
    protected $pk = 'id';
    
    // 软删除字段
    protected $deleteTime = 'deleted_at';
    
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    
    // 时间字段格式化
    protected $dateFormat = 'Y-m-d H:i:s';
    
    // 状态映射
    const MENU_STATUS_DISABLED = 0;   // 禁用
    const MENU_STATUS_ENABLED = 1;    // 启用
    
    /**
     * 获取状态选项
     * @return array
     */
    public static function getMenuStatusOptions(): array
    {
        return [
            self::MENU_STATUS_DISABLED => '禁用',
            self::MENU_STATUS_ENABLED => '启用',
        ];
    }
    
    /**
     * 获取状态文本
     * @return string
     */
    public function getStatusText(): string
    {
        $options = self::getMenuStatusOptions();
        return $options[$this->menu_status] ?? '未知';
    }
    
    /**
     * 获取树形结构数据
     * @param int $parentId 父级ID
     * @return array
     */
    public static function getTree(int $parentId = 0): array
    {
        $list = self::where('parent_id', $parentId)
            ->where('menu_status', self::MENU_STATUS_ENABLED)
            ->order('menu_sort', 'desc')
            ->order('id', 'asc')
            ->select()
            ->toArray();
        
        foreach ($list as &$item) {
            $children = self::getTree($item['id']);
            if (!empty($children)) {
                $item['children'] = $children;
            }
        }
        
        return $list;
    }
    
    /**
     * 获取所有菜单列表（用于下拉选择）
     * @param int $excludeId 排除的ID
     * @return array
     */
    public static function getAllOptions(int $excludeId = 0): array
    {
        $query = self::where('menu_status', self::MENU_STATUS_ENABLED);
        
        if ($excludeId > 0) {
            $query->where('id', '<>', $excludeId);
        }
        
        $list = $query->order('menu_sort', 'desc')->order('id', 'asc')->select();
        
        $options = [];
        foreach ($list as $item) {
            $options[] = [
                'id' => $item->id,
                'menu_name' => $item->menu_name,
            ];
        }
        
        return $options;
    }
    
    /**
     * 获取菜单选项（带层级缩进）
     * @param int $parentId 父级ID
     * @param string $prefix 前缀
     * @param int $excludeId 排除的ID
     * @return array
     */
    public static function getOptionsWithLevel(int $parentId = 0, string $prefix = '', int $excludeId = 0): array
    {
        $list = self::where('parent_id', $parentId)
            ->where('menu_status', self::MENU_STATUS_ENABLED)
            ->order('menu_sort', 'desc')
            ->order('id', 'asc')
            ->select();
        
        $options = [];
        foreach ($list as $item) {
            if ($item->id == $excludeId) {
                continue;
            }
            
            $options[] = [
                'id' => $item->id,
                'menu_name' => $prefix . $item->menu_name,
            ];
            
            // 递归获取子菜单
            $childOptions = self::getOptionsWithLevel((int) $item->id, $prefix . '├─ ', $excludeId);
            $options = array_merge($options, $childOptions);
        }
        
        return $options;
    }
}
