<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?: '系统管理后台' ?></title>
    <link rel="stylesheet" href="/static/backend/layui/css/layui.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f0f2f5;
            overflow: hidden;
        }
        /* 顶部导航 */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            background: #393D49;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        .header-left {
            display: flex;
            align-items: center;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 600;
        }
        .logo-text {
            color: #fff;
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
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 500;
            color: #fff;
        }
        .user-name {
            font-size: 14px;
        }
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 6px 16px;
            background: #FF5722;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s;
        }
        .logout-btn:hover {
            background: #e64a19;
        }
        /* 侧边栏 */
        .sidebar {
            position: fixed;
            top: 50px;
            left: 0;
            bottom: 0;
            width: 220px;
            background: #2F4050;
            overflow-y: auto;
            z-index: 999;
            transition: transform 0.3s;
        }
        .sidebar-menu {
            padding: 10px 0;
        }
        .menu-item {
            margin-bottom: 2px;
        }
        .menu-header {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #a7b1c2;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
        }
        .menu-header:hover {
            background: #1ab394;
            color: #fff;
        }
        .menu-header i.layui-icon {
            width: 24px;
            font-size: 16px;
            margin-right: 10px;
        }
        .menu-title {
            flex: 1;
        }
        .menu-header .arrow {
            font-size: 12px;
            transition: transform 0.3s;
        }
        .menu-header.open .arrow {
            transform: rotate(180deg);
        }
        .menu-children {
            display: none;
            background: #1f2d3d;
        }
        .menu-children.open {
            display: block;
        }
        .sub-has-children {
            padding-left: 10px;
        }
        .sub-menu-header {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #8aa4af;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
        }
        .sub-menu-header:hover {
            background: #2f4050;
            color: #fff;
        }
        .sub-menu-title {
            flex: 1;
        }
        .sub-arrow {
            font-size: 12px;
            transition: transform 0.3s;
        }
        .sub-menu-header.open .sub-arrow {
            transform: rotate(90deg);
        }
        .sub-menu-children {
            display: none;
            background: #16202a;
        }
        .sub-menu-children.open {
            display: block;
        }
        .menu-link {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #8aa4af;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .menu-link:hover {
            background: #1ab394;
            color: #fff;
            border-left-color: #1ab394;
        }
        .menu-link.single {
            padding: 12px 20px;
            font-size: 14px;
        }
        .menu-link i.layui-icon {
            width: 20px;
            font-size: 14px;
            margin-right: 10px;
        }
        .menu-text {
            flex: 1;
        }
        /* 主内容区域 */
        .main-container {
            margin-left: 220px;
            margin-top: 50px;
            height: calc(100vh - 50px);
            overflow-y: auto;
            padding: 20px;
            transition: margin-left 0.3s;
        }
        .content-wrapper {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            min-height: calc(100% - 40px);
        }
        .content-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 15px;
            border-bottom: 1px solid #e6e6e6;
            margin-bottom: 20px;
        }
        .content-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        .content-actions {
            display: flex;
            gap: 10px;
        }
        /* Layui样式覆盖 */
        .layui-btn {
            border-radius: 4px;
        }
        .layui-table {
            margin: 0;
        }
        .layui-card {
            border-radius: 8px;
        }
        /* 响应式 */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-container {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- 顶部导航 -->
    <?= include 'header.php' ?>
    
    <!-- 侧边栏菜单 -->
    <?= include 'sidebar.php' ?>
    
    <!-- 主内容区域 -->
    <div class="main-container">
        <div class="content-wrapper">
            <!-- 内容区域（由子页面填充） -->
            <?= $content ?>
        </div>
    </div>
    
    <script src="/static/backend/layui/layui.js"></script>
    <script>
        // 菜单展开/收起
        function toggleMenu(id) {
            var el = document.getElementById(id);
            var header = el.parentElement.querySelector('.menu-header');
            if (el.classList.contains('open')) {
                el.classList.remove('open');
                header.classList.remove('open');
            } else {
                // 先关闭其他展开的菜单
                document.querySelectorAll('.menu-children').forEach(function(item) {
                    item.classList.remove('open');
                });
                document.querySelectorAll('.menu-header').forEach(function(item) {
                    item.classList.remove('open');
                });
                el.classList.add('open');
                header.classList.add('open');
            }
        }
        
        function toggleSubMenu(id) {
            var el = document.getElementById(id);
            var header = el.parentElement.querySelector('.sub-menu-header');
            if (el.classList.contains('open')) {
                el.classList.remove('open');
                header.classList.remove('open');
            } else {
                el.classList.add('open');
                header.classList.add('open');
            }
        }
        
        // 退出登录
        function logout() {
            if (confirm('确定要退出登录吗？')) {
                window.location.href = '/backend/login/logout';
            }
        }
        
        // 初始化Layui
        layui.use(['layer', 'form', 'table'], function() {
            window.layer = layui.layer;
            window.form = layui.form;
            window.table = layui.table;
        });
    </script>
</body>
</html>