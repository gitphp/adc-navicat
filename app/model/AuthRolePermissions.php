<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * 角色-权限关联模型
 * @property int $role_id 角色ID
 * @property int $permission_id 权限ID
 * @property string $created_at 创建时间
 */
class AuthRolePermissions extends Model
{
    // 设置表名
    protected $name = 'auth_role_permissions';
    
    // 设置主键（复合主键）
    protected $pk = ['role_id', 'permission_id'];
    
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = false;
    
    // 时间字段格式化
    protected $dateFormat = 'Y-m-d H:i:s';
    
    /**
     * 批量保存角色权限
     * @param int $roleId 角色ID
     * @param array $permissionIds 权限ID数组
     * @return bool
     */
    public static function savePermissions(int $roleId, array $permissionIds): bool
    {
        try {
            // 删除旧关联
            self::where('role_id', $roleId)->delete();
            
            // 添加新关联
            if (!empty($permissionIds)) {
                $data = [];
                foreach ($permissionIds as $permissionId) {
                    $data[] = [
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
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
     * 获取角色的权限ID列表
     * @param int $roleId 角色ID
     * @return array
     */
    public static function getPermissionIds(int $roleId): array
    {
        $list = self::where('role_id', $roleId)->column('permission_id');
        return $list ? $list : [];
    }
}
