<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理</title>
    <link rel="stylesheet" href="/static/backend/layui/css/layui.css">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .header {
            height: 60px;
            background: #393D49;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        .header-title {
            font-size: 20px;
            font-weight: 600;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .logout-btn {
            padding: 6px 16px;
            background: #FF5722;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .logout-btn:hover {
            background: #e64a19;
        }
        .main-content {
            padding: 30px;
            min-height: calc(100vh - 60px);
            background: #f0f2f5;
        }
        .welcome-card {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }
        .welcome-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .welcome-desc {
            color: #999;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-title">系统管理后台</div>
        <div class="header-right">
            <div class="user-info">
                <div class="user-avatar"><?= htmlspecialchars(substr($user_info['user_nick'], 0, 1)) ?></div>
                <span><?= htmlspecialchars($user_info['user_nick']) ?></span>
            </div>
            <button class="logout-btn" onclick="logout()">退出登录</button>
        </div>
    </div>
    
    <div class="main-content">
        <div class="welcome-card">
            <div class="welcome-title">欢迎回来，<?= htmlspecialchars($user_info['user_nick']) ?></div>
            <div class="welcome-desc">这是您的系统管理后台，您可以在这里管理系统的各项功能。</div>
        </div>
    </div>
    
    <script src="/static/backend/layui/layui.js"></script>
    <script>
        function logout() {
            if (confirm('确定要退出登录吗？')) {
                window.location.href = '/backend/login/logout';
            }
        }
    </script>
</body>
</html>
