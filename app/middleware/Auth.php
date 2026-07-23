<?php
declare (strict_types = 1);

namespace app\middleware;

use app\model\User;
use think\facade\Session;
use think\facade\Url;

/**
 * 认证中间件
 * 用于验证用户登录状态
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
        
        return $next($request);
    }
}
