<!-- 顶部导航栏 -->
<div class="navbar" id="navbar">
    <div class="navbar-left">
        <a href="javascript:;" class="navbar-toggle" onclick="toggleSidebar()" title="折叠菜单">
            <i class="layui-icon layui-icon-spread-left"></i>
        </a>
        <a href="javascript:;" class="navbar-btn" onclick="refreshContent()" title="刷新">
            <i class="layui-icon layui-icon-refresh"></i>
        </a>
        <div class="navbar-links">
            <a href="/backend/index">人事系统</a>
            <a href="javascript:;">商户系统</a>
        </div>
    </div>
    <div class="navbar-right">
        <a href="javascript:;" class="navbar-icon" title="语言">
            <i class="layui-icon layui-icon-website"></i>
        </a>
        <a href="javascript:;" class="navbar-icon" title="清除缓存">
            <i class="layui-icon layui-icon-delete"></i>
        </a>
        <a href="javascript:;" class="navbar-icon" title="通知">
            <i class="layui-icon layui-icon-notice"></i>
        </a>
        <a href="javascript:;" class="navbar-icon" title="标签">
            <i class="layui-icon layui-icon-tabs"></i>
        </a>
        <a href="javascript:;" class="navbar-icon" onclick="toggleFullscreen()" title="全屏">
            <i class="layui-icon layui-icon-screen-full"></i>
        </a>
        <a href="javascript:;" class="navbar-icon" title="锁屏">
            <i class="layui-icon layui-icon-password"></i>
        </a>
        <div class="user-info" onclick="toggleUserMenu(event)">
            <div class="user-avatar"><?= htmlspecialchars(mb_substr($user_info['user_nick'] ?? 'A', 0, 1)) ?></div>
            <span class="user-name"><?= htmlspecialchars($user_info['user_nick'] ?? 'admin') ?></span>
            <i class="layui-icon layui-icon-down user-arrow"></i>
            <div class="user-dropdown" id="userDropdown">
                <a href="javascript:;" onclick="logout()">退出登录</a>
            </div>
        </div>
    </div>
</div>

<!-- 多标签页 -->
<div class="tabs-bar" id="tabsBar">
    <div class="tabs-arrow" onclick="scrollTabs('left')" title="向左">
        <i class="layui-icon layui-icon-left"></i>
    </div>
    <div class="tabs-container">
        <div class="tabs-nav" id="tabsNav">
            <div class="tab-item active" data-url="/backend/index" onclick="switchTab(this)">
                <i class="layui-icon layui-icon-home"></i>
                <span>首页</span>
                <i class="layui-icon layui-icon-close tab-close" onclick="event.stopPropagation();closeTab(this)"></i>
            </div>
        </div>
    </div>
    <div class="tabs-arrow" onclick="scrollTabs('right')" title="向右">
        <i class="layui-icon layui-icon-right"></i>
    </div>
</div>
