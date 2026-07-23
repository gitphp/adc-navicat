<?php
declare (strict_types = 1);

namespace app\controller\backend;

use app\BaseController;
use app\model\User;
use app\validate\LoginValidate;
use think\facade\Session;
use think\facade\View;

/**
 * 后台登录控制器
 */
class Login extends BaseController
{
    /**
     * 登录页面
     * @return \think\Response
     */
    public function index()
    {
        // 如果已登录，跳转到后台首页
        if (Session::get('user_id')) {
            return redirect('/backend/index');
        }
        
        return View::fetch('login/index');
    }

    /**
     * 登录验证
     * @return \think\response\Json
     */
    public function doLogin()
    {
        // 获取请求参数
        $data = $this->request->post();
        
        // 验证数据
        try {
            $this->validate($data, LoginValidate::class . '.login');
        } catch (\think\exception\ValidateException $e) {
            return json([
                'code' => 0,
                'msg'  => $e->getMessage(),
                'data' => null,
            ]);
        }
        
        // 查找用户
        $user = User::findByAccount($data['account']);
        
        if (!$user) {
            return json([
                'code' => 0,
                'msg'  => '账号不存在或密码错误',
                'data' => null,
            ]);
        }
        
        // 检查用户状态
        if (!$user->isActive()) {
            return json([
                'code' => 0,
                'msg'  => '账号已被禁用',
                'data' => null,
            ]);
        }
        
        // 验证密码
        if (!$user->verifyPassword($data['password'])) {
            return json([
                'code' => 0,
                'msg'  => '账号不存在或密码错误',
                'data' => null,
            ]);
        }
        
        // 更新登录信息
        $user->updateLoginInfo($this->request->ip());
        
        // 设置session（确保字段不为null）
        Session::set('user_id', $user->id);
        Session::set('user_info', [
            'id'              => $user->id,
            'user_name'       => $user->user_name ?: '',
            'user_nick'       => $user->user_nick ?: $user->user_name ?: '',
            'user_mobile'     => $user->user_mobile ?: '',
            'user_email'      => $user->user_email ?: '',
            'user_status'     => $user->user_status,
            'real_auth_status' => $user->real_auth_status,
        ]);
        
        // 记住我功能
        if (!empty($data['remember'])) {
            Session::set('remember', true);
        }
        
        // 强制写入session
        Session::save();
        
        // 使用原生PHP函数确保session写入
        session_write_close();
        
        return json([
            'code' => 1,
            'msg'  => '登录成功',
            'data' => [
                'redirect' => '/backend/index',
            ],
        ]);
    }

    /**
     * 检查session状态（调试用）
     * @return \think\response\Json
     */
    public function checkSession()
    {
        // 使用浏览器cookie中的PHPSESSID
        $sessionId = $_COOKIE['PHPSESSID'] ?? 'unknown';
        $userId = Session::get('user_id');
        $sessionFile = runtime_path() . 'session/sess_' . $sessionId;
        
        // 列出session目录下的所有文件
        $sessionFiles = [];
        if (is_dir(runtime_path() . 'session')) {
            $dir = dir(runtime_path() . 'session');
            while (($file = $dir->read()) !== false) {
                if ($file != '.' && $file != '..') {
                    $sessionFiles[] = $file;
                }
            }
            $dir->close();
        }
        
        return json([
            'code' => 1,
            'msg' => 'session状态',
            'data' => [
                'session_id_from_cookie' => $sessionId,
                'user_id' => $userId,
                'session_file_exists' => file_exists($sessionFile),
                'session_file_size' => file_exists($sessionFile) ? filesize($sessionFile) : 0,
                'session_content' => file_exists($sessionFile) ? file_get_contents($sessionFile) : '',
                'session_dir_files' => $sessionFiles,
                'session_dir_path' => runtime_path() . 'session',
                'cookies' => $_COOKIE,
            ],
        ]);
    }

    /**
     * 退出登录
     * @return \think\Response
     */
    public function logout()
    {
        // 清除session
        Session::delete('user_id');
        Session::delete('user_info');
        Session::delete('remember');
        
        // 跳转到登录页面
        return redirect('/backend/login');
    }

    /**
     * 获取验证码（需安装think-captcha扩展）
     * @return \think\Response
     */
    public function captcha()
    {
        try {
            return captcha();
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * 检查登录状态（AJAX）
     * @return \think\response\Json
     */
    public function check()
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            return json([
                'code' => 0,
                'msg'  => '未登录',
                'data' => null,
            ]);
        }
        
        $user = User::find($userId);
        
        if (!$user || !$user->isActive()) {
            Session::delete('user_id');
            Session::delete('user_info');
            
            return json([
                'code' => 0,
                'msg'  => '用户不存在或已被禁用',
                'data' => null,
            ]);
        }
        
        return json([
            'code' => 1,
            'msg'  => '已登录',
            'data' => [
                'user_id'    => $user->id,
                'user_name'  => $user->user_name,
                'user_nick'  => $user->user_nick,
            ],
        ]);
    }
}
