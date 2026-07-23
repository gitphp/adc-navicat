<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录</title>
    <link rel="stylesheet" href="/static/backend/layui/css/layui.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            width: 400px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
        }
        .login-title {
            text-align: center;
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
        }
        .login-form {
            margin-top: 20px;
        }
        .login-btn {
            width: 100%;
            height: 48px;
            font-size: 16px;
            border-radius: 8px;
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #999;
            font-size: 14px;
        }
        .layui-input-item {
            margin-bottom: 20px;
        }
        .captcha-row {
            display: flex;
            gap: 10px;
        }
        .captcha-row .layui-input-item {
            flex: 1;
        }
        .captcha-img {
            width: 120px;
            height: 44px;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-title">系统登录</div>
        
        <form class="login-form layui-form" action="" method="post">
            <div class="layui-input-item">
                <label class="layui-form-label">账号</label>
                <div class="layui-input-block">
                    <input type="text" name="account" id="account" required lay-verify="required" 
                           placeholder="请输入用户名、手机号或邮箱" 
                           class="layui-input" style="height: 44px; border-radius: 6px;">
                </div>
            </div>
            
            <div class="layui-input-item">
                <label class="layui-form-label">密码</label>
                <div class="layui-input-block">
                    <input type="password" name="password" id="password" required lay-verify="required" 
                           placeholder="请输入密码" 
                           class="layui-input" style="height: 44px; border-radius: 6px;">
                </div>
            </div>
            
            <div class="layui-input-item">
                <div class="layui-input-block" style="padding-left: 0;">
                    <input type="checkbox" name="remember" id="remember" lay-skin="primary" title="记住我">
                </div>
            </div>
            
            <div class="layui-input-item">
                <div class="layui-input-block" style="padding-left: 0;">
                    <button type="button" class="layui-btn layui-btn-primary login-btn" id="loginBtn" 
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border: none;">
                        登 录
                    </button>
                </div>
            </div>
        </form>
        
        <div class="login-footer">
            © 2024 系统管理后台
        </div>
    </div>
    
    <script src="/static/backend/layui/layui.js"></script>
    <script>
        layui.use(['form', 'layer'], function() {
            var form = layui.form;
            var layer = layui.layer;
            
            // 登录按钮点击事件
            document.getElementById('loginBtn').addEventListener('click', function() {
                var account = document.getElementById('account').value.trim();
                var password = document.getElementById('password').value.trim();
                
                if (!account) {
                    layer.msg('请输入账号', {icon: 5});
                    return;
                }
                
                if (!password) {
                    layer.msg('请输入密码', {icon: 5});
                    return;
                }
                
                var remember = document.getElementById('remember').checked ? 1 : 0;
                
                // 发送登录请求
                layui.$.ajax({
                    url: '/backend/login/doLogin',
                    type: 'POST',
                    data: {
                        account: account,
                        password: password,
                        remember: remember
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        document.getElementById('loginBtn').disabled = true;
                        document.getElementById('loginBtn').innerHTML = '登录中...';
                    },
                    success: function(res) {
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1, time: 1000}, function() {
                                window.location.href = res.data.redirect;
                            });
                        } else {
                            layer.msg(res.msg, {icon: 5});
                        }
                    },
                    error: function() {
                        layer.msg('网络错误，请稍后重试', {icon: 5});
                    },
                    complete: function() {
                        document.getElementById('loginBtn').disabled = false;
                        document.getElementById('loginBtn').innerHTML = '登 录';
                    }
                });
            });
            
            // 回车键登录
            document.getElementById('password').addEventListener('keydown', function(e) {
                if (e.keyCode === 13) {
                    document.getElementById('loginBtn').click();
                }
            });
        });
    </script>
</body>
</html>
