<?php
declare (strict_types = 1);

namespace app\helper;

use app\model\User;
use think\facade\Session;

/**
 * 权限检查工具类
 */
class PermissionHelper
{
    /**
     * 获取当前登录用户
     * @return User|null
     */
    public static function getUser(): ?User
    {
        $userId = Session::get('user_id');
        if (!$userId) {
            return null;
        }
        
        return User::find($userId);
    }
    
    /**
     * 检查当前用户是否登录
     * @return bool
     */
    public static function isLoggedIn(): bool
    {
        return self::getUser() !== null;
    }
    
    /**
     * 检查用户是否有指定权限
     * @param string $code 权限标识
     * @param User|null $user 用户对象（默认为当前登录用户）
     * @return bool
     */
    public static function hasPermission(string $code, User $user = null): bool
    {
        if (!$user) {
            $user = self::getUser();
        }
        
        if (!$user) {
            return false;
        }
        
        return $user->hasPermission($code);
    }
    
    /**
     * 检查用户是否有任意一个权限
     * @param array $codes 权限标识数组
     * @param User|null $user 用户对象（默认为当前登录用户）
     * @return bool
     */
    public static function hasAnyPermission(array $codes, User $user = null): bool
    {
        if (!$user) {
            $user = self::getUser();
        }
        
        if (!$user) {
            return false;
        }
        
        $userCodes = $user->getPermissionCodes();
        foreach ($codes as $code) {
            if (in_array($code, $userCodes)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * 检查用户是否有所有权限
     * @param array $codes 权限标识数组
     * @param User|null $user 用户对象（默认为当前登录用户）
     * @return bool
     */
    public static function hasAllPermissions(array $codes, User $user = null): bool
    {
        if (!$user) {
            $user = self::getUser();
        }
        
        if (!$user) {
            return false;
        }
        
        $userCodes = $user->getPermissionCodes();
        foreach ($codes as $code) {
            if (!in_array($code, $userCodes)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * 获取当前用户的权限列表
     * @return array
     */
    public static function getCurrentPermissions(): array
    {
        $user = self::getUser();
        if (!$user) {
            return [];
        }
        
        return $user->getPermissionCodes();
    }
    
    /**
     * 获取当前用户的菜单列表（树形结构）
     * @return array
     */
    public static function getCurrentMenuTree(): array
    {
        $user = self::getUser();
        if (!$user) {
            return [];
        }
        
        $menuIds = $user->getMenuIds();
        if (empty($menuIds)) {
            return [];
        }
        
        // 获取所有启用的菜单
        $menus = \app\model\AuthMenus::where('menu_status', 1)
            ->order('menu_sort', 'desc')
            ->order('id', 'asc')
            ->select()
            ->toArray();
        
        // 如果是超级管理员，返回所有菜单
        if (self::isSuperAdmin($user)) {
            return self::buildMenuTree($menus, array_column($menus, 'id'));
        }
        
        // 级联获取父级菜单ID（用户有权访问子菜单时，自动获得父级菜单访问权限）
        $allowedMenuIds = self::getAllowedMenuIds($menus, $menuIds);
        
        // 构建树形结构
        return self::buildMenuTree($menus, $allowedMenuIds);
    }
    
    /**
     * 级联获取允许访问的菜单ID（包含父级菜单）
     * @param array $menus 所有菜单列表
     * @param array $userMenuIds 用户直接关联的菜单ID
     * @return array
     */
    public static function getAllowedMenuIds(array $menus, array $userMenuIds): array
    {
        $allowedIds = $userMenuIds;
        $menuMap = [];
        
        // 构建菜单ID到父级ID的映射
        foreach ($menus as $menu) {
            $menuMap[(string)$menu['id']] = (string)$menu['parent_id'];
        }
        
        // 级联获取父级菜单ID
        $changed = true;
        while ($changed) {
            $changed = false;
            foreach ($allowedIds as $menuId) {
                $parentId = $menuMap[(string)$menuId] ?? null;
                if ($parentId && $parentId !== '0' && !in_array($parentId, $allowedIds)) {
                    $allowedIds[] = $parentId;
                    $changed = true;
                }
            }
        }
        
        return $allowedIds;
    }
    
    /**
     * 构建菜单树形结构
     * @param array $menus 菜单列表
     * @param array $allowedMenuIds 允许访问的菜单ID
     * @param int $parentId 父级ID
     * @return array
     */
    public static function buildMenuTree(array $menus, array $allowedMenuIds, string $parentId = '0'): array
    {
        $tree = [];
        
        foreach ($menus as $menu) {
            if ($menu['parent_id'] == $parentId && in_array($menu['id'], $allowedMenuIds)) {
                $children = self::buildMenuTree($menus, $allowedMenuIds, $menu['id']);
                if (!empty($children)) {
                    $menu['children'] = $children;
                }
                $tree[] = $menu;
            }
        }
        
        return $tree;
    }
    
    /**
     * 获取当前用户的角色列表
     * @return \think\Collection
     */
    public static function getCurrentRoles()
    {
        $user = self::getUser();
        if (!$user) {
            return collect([]);
        }
        
        return $user->getRoles();
    }
    
    /**
     * 检查用户是否为超级管理员
     * @param User|null $user 用户对象（默认为当前登录用户）
     * @return bool
     */
    public static function isSuperAdmin(User $user = null): bool
    {
        if (!$user) {
            $user = self::getUser();
        }
        
        if (!$user) {
            return false;
        }
        
        $roles = $user->getRoles();
        foreach ($roles as $role) {
            // role_code为super_admin或admin，或者角色名称为超级管理员，都视为超级管理员
            if ($role->role_code === 'super_admin' || $role->role_code === 'admin' || $role->role_name === '超级管理员') {
                return true;
            }
        }
        
        return false;
    }
}
