<?php
declare (strict_types = 1);

namespace app\middleware;

use app\helper\PermissionHelper;
use app\model\User;
use think\facade\Session;
use think\facade\Url;

/**
 * 认证中间件
 * 用于验证用户登录状态和权限
 */
class Auth
{
    /**
     * 处理请求
     * @param \think\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        // 检查session中是否有用户信息
        $userId = Session::get('user_id');
        
        if (!$userId) {
            // 判断是否为AJAX请求
            if ($request->isAjax()) {
                return json([
                    'code' => 401,
                    'msg'  => '未登录，请重新登录',
                    'data' => null,
                ]);
            }
            
            // 跳转到后台登录页面
            return redirect(Url::build('backend.login/index'));
        }
        
        // 检查用户是否存在且正常
        $user = User::find($userId);
        if (!$user || !$user->isActive() || $user->is_deleted) {
            Session::delete('user_id');
            Session::delete('user_info');
            
            if ($request->isAjax()) {
                return json([
                    'code' => 401,
                    'msg'  => '用户不存在或已被禁用',
                    'data' => null,
                ]);
            }
            
            return redirect(Url::build('backend.login/index'));
        }
        
        // 将用户信息注入到request中
        $request->user = $user;
        
        // 权限检查
        $permission = $this->getPermissionCode($request);
        if ($permission && !PermissionHelper::hasPermission($permission, $user) && !PermissionHelper::isSuperAdmin($user)) {
            if ($request->isAjax()) {
                return json([
                    'code' => 403,
                    'msg'  => '没有权限访问该资源',
                    'data' => null,
                ]);
            }
            
            return '<h1>403 没有权限访问该资源</h1>';
        }
        
        return $next($request);
    }
    
    /**
     * 根据请求获取权限标识
     * @param \think\Request $request
     * @return string|null
     */
    protected function getPermissionCode($request): ?string
    {
        $controller = $request->controller();
        $action = $request->action();
        
        // 构建权限标识（格式：controller:action）
        $permissionCode = strtolower($controller) . ':' . strtolower($action);
        
        // 可以在这里添加更多的权限映射逻辑
        // 例如：根据路由规则或自定义配置来获取权限标识
        
        return $permissionCode;
    }
}
