<div class="header">
    <div class="header-left">
        <div class="logo">
            <i class="layui-icon layui-icon-home"></i>
            <span class="logo-text">系统管理后台</span>
        </div>
    </div>
    <div class="header-right">
        <div class="user-info">
            <div class="user-avatar"><?= htmlspecialchars(substr($user_info['user_nick'], 0, 1)) ?></div>
            <span class="user-name"><?= htmlspecialchars($user_info['user_nick']) ?></span>
        </div>
        <button class="logout-btn" onclick="logout()">
            <i class="layui-icon layui-icon-login"></i>
            退出登录
        </button>
    </div>
</div>