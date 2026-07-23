<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * 用户账号模型
 * @property int $id 用户唯一主键ID
 * @property string $user_name 账号用户名
 * @property string $user_nick 用户昵称
 * @property string $user_mobile 手机号
 * @property string $user_email 邮箱
 * @property string $password_hash BCrypt加密密码
 * @property string $password_salt 自定义盐值
 * @property int $user_status 账号状态
 * @property string $lock_reason 封禁原因
 * @property string $last_login_ip 最后登录IP
 * @property string $last_login_time 最后登录时间
 * @property string $last_login_region IP归属地
 * @property string $register_ip 注册IP
 * @property string $register_time 注册时间
 * @property int $real_auth_status 实名状态
 * @property int $is_deleted 软删除
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class User extends Model
{
    // 设置表名
    protected $name = 'user_account';

    // 设置主键
    protected $pk = 'id';

    // 软删除字段
    protected $deleteTime = 'is_deleted';
    protected $defaultSoftDelete = 0;

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    // 时间字段格式化
    protected $dateFormat = 'Y-m-d H:i:s';

    // 隐藏敏感字段
    protected $hidden = ['password_hash', 'password_salt'];

    /**
     * 验证密码
     * @param string $password 原始密码
     * @return bool
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password . $this->password_salt, $this->password_hash);
    }

    /**
     * 密码加密
     * @param string $password 原始密码
     * @param string $salt 盐值
     * @return string
     */
    public static function encryptPassword(string $password, string $salt): string
    {
        return password_hash($password . $salt, PASSWORD_DEFAULT);
    }

    /**
     * 生成随机盐值
     * @return string
     */
    public static function generateSalt(): string
    {
        return md5(uniqid((string)mt_rand(), true));
    }

    /**
     * 根据用户名/手机号/邮箱查找用户
     * @param string $account 账号（用户名、手机号或邮箱）
     * @return User|null
     */
    public static function findByAccount(string $account): ?User
    {
        return self::where('user_name', $account)
            ->whereOr('user_mobile', $account)
            ->whereOr('user_email', $account)
            ->where('is_deleted', 0)
            ->find();
    }

    /**
     * 判断用户是否正常
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->user_status === 1;
    }

    /**
     * 更新登录信息
     * @param string $ip 登录IP
     * @param string $region IP归属地
     * @return bool
     */
    public function updateLoginInfo(string $ip, string $region = ''): bool
    {
        $this->last_login_ip = $ip;
        $this->last_login_time = date('Y-m-d H:i:s');
        $this->last_login_region = $region;
        return $this->save();
    }
    
    /**
     * 获取用户的角色ID列表
     * @return array
     */
    public function getRoleIds(): array
    {
        return \app\model\AuthUserRole::getRoleIds($this->id);
    }
    
    /**
     * 获取用户的角色列表
     * @return \think\Collection
     */
    public function getRoles()
    {
        $roleIds = $this->getRoleIds();
        if (empty($roleIds)) {
            return collect([]);
        }
        return \app\model\AuthRole::whereIn('id', $roleIds)->where('role_status', 1)->select();
    }
    
    /**
     * 获取用户的权限标识列表
     * @return array
     */
    public function getPermissionCodes(): array
    {
        $roleIds = $this->getRoleIds();
        if (empty($roleIds)) {
            return [];
        }
        
        // 获取角色的权限ID
        $permissionIds = \app\model\AuthRolePermissions::whereIn('role_id', $roleIds)->column('permission_id');
        if (empty($permissionIds)) {
            return [];
        }
        
        // 获取权限标识
        $codes = \app\model\AuthPermissions::whereIn('id', $permissionIds)
            ->where('per_status', 1)
            ->column('per_code');
        
        return $codes ? $codes : [];
    }
    
    /**
     * 检查用户是否有指定权限
     * @param string $code 权限标识
     * @return bool
     */
    public function hasPermission(string $code): bool
    {
        $codes = $this->getPermissionCodes();
        return in_array($code, $codes);
    }
    
    /**
     * 获取用户的菜单ID列表
     * @return array
     */
    public function getMenuIds(): array
    {
        $roleIds = $this->getRoleIds();
        if (empty($roleIds)) {
            return [];
        }
        
        $menuIds = \app\model\AuthRoleMenus::whereIn('role_id', $roleIds)->column('menu_id');
        return $menuIds ? $menuIds : [];
    }
}
