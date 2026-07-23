<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * 用户-角色关联模型
 * @property int $user_id 用户ID
 * @property int $role_id 角色ID
 * @property string $created_at 创建时间
 */
class AuthUserRole extends Model
{
    // 设置表名
    protected $name = 'auth_user_role';
    
    // 设置主键（复合主键）
    protected $pk = ['user_id', 'role_id'];
    
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = false;
    
    // 时间字段格式化
    protected $dateFormat = 'Y-m-d H:i:s';
    
    /**
     * 批量保存用户角色
     * @param int $userId 用户ID
     * @param array $roleIds 角色ID数组
     * @return bool
     */
    public static function saveRoles(int $userId, array $roleIds): bool
    {
        try {
            // 删除旧关联
            self::where('user_id', $userId)->delete();
            
            // 添加新关联
            if (!empty($roleIds)) {
                $data = [];
                foreach ($roleIds as $roleId) {
                    $data[] = [
                        'user_id' => $userId,
                        'role_id' => $roleId,
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
     * 获取用户的角色ID列表
     * @param int $userId 用户ID
     * @return array
     */
    public static function getRoleIds(int $userId): array
    {
        $list = self::where('user_id', $userId)->column('role_id');
        return $list ? $list : [];
    }
}
