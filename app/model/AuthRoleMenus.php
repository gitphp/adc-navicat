<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * 角色-菜单关联模型
 * @property int $role_id 角色ID
 * @property int $menu_id 菜单ID
 * @property string $created_at 创建时间
 */
class AuthRoleMenus extends Model
{
    // 设置表名
    protected $name = 'auth_role_menus';
    
    // 设置主键（复合主键）
    protected $pk = ['role_id', 'menu_id'];
    
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = false;
    
    // 时间字段格式化
    protected $dateFormat = 'Y-m-d H:i:s';
    
    /**
     * 批量保存角色菜单
     * @param string $roleId 角色ID（雪花ID）
     * @param array $menuIds 菜单ID数组
     * @return bool
     */
    public static function saveMenus(string $roleId, array $menuIds): bool
    {
        try {
            // 删除旧关联
            self::where('role_id', $roleId)->delete();
            
            // 添加新关联
            if (!empty($menuIds)) {
                $data = [];
                foreach ($menuIds as $menuId) {
                    $data[] = [
                        'role_id' => $roleId,
                        'menu_id' => $menuId,
                    ];
                }
                self::insertAll($data);
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * 获取角色的菜单ID列表
     * @param string $roleId 角色ID（雪花ID）
     * @return array
     */
    public static function getMenuIds(string $roleId): array
    {
        $list = self::where('role_id', $roleId)->column('menu_id');
        return $list ? $list : [];
    }
}
