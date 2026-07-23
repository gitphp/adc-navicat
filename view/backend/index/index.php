<div class="content-header">
    <div class="content-title">欢迎回来，<?= htmlspecialchars($user_info['user_nick']) ?></div>
    <div class="content-subtitle"><?= date('Y年m月d日 H:i:s') ?></div>
</div>

<!-- 顶部三栏卡片 -->
<div class="dashboard-top">
    <!-- 系统信息 -->
    <div class="dashboard-card">
        <div class="card-header">
            <i class="layui-icon layui-icon-set"></i>
            <span>系统信息</span>
            <i class="layui-icon layui-icon-refresh refresh-btn" onclick="refreshSysInfo()"></i>
        </div>
        <div class="card-body sys-info">
            <div class="info-item">
                <span class="info-label">PHP:</span>
                <span class="info-value" id="php_version">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">MySQL:</span>
                <span class="info-value" id="mysql_version">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">服务器:</span>
                <span class="info-value" id="server_software">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">操作系统:</span>
                <span class="info-value" id="server_os">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">上传限制:</span>
                <span class="info-value" id="upload_max_size">-</span>
            </div>
            <div class="info-item">
                <span class="info-label">内存限制:</span>
                <span class="info-value" id="memory_limit">-</span>
            </div>
        </div>
    </div>

    <!-- 官方消息 -->
    <div class="dashboard-card">
        <div class="card-header">
            <i class="layui-icon layui-icon-notice"></i>
            <span>官方消息</span>
        </div>
        <div class="card-body news-list">
            <div class="news-item">
                <span class="news-dot"></span>
                <span class="news-text">欢迎使用系统管理后台</span>
                <span class="news-time"><?= date('Y-m-d') ?></span>
            </div>
            <div class="news-item">
                <span class="news-dot"></span>
                <span class="news-text">系统已升级到最新版本</span>
                <span class="news-time"><?= date('Y-m-d') ?></span>
            </div>
            <div class="news-item">
                <span class="news-dot"></span>
                <span class="news-text">请定期备份数据库</span>
                <span class="news-time"><?= date('Y-m-d', strtotime('-1 day')) ?></span>
            </div>
            <div class="news-item">
                <span class="news-dot"></span>
                <span class="news-text">注意：有更新请查看更新日志</span>
                <span class="news-time"><?= date('Y-m-d', strtotime('-2 day')) ?></span>
            </div>
            <div class="news-item">
                <span class="news-dot"></span>
                <span class="news-text">感谢您的使用，祝您工作顺利</span>
                <span class="news-time"><?= date('Y-m-d', strtotime('-3 day')) ?></span>
            </div>
        </div>
    </div>

    <!-- 提交反馈 -->
    <div class="dashboard-card">
        <div class="card-header">
            <i class="layui-icon layui-icon-dialogue"></i>
            <span>提交反馈</span>
        </div>
        <div class="card-body feedback-form">
            <textarea id="feedback_content" placeholder="请输入您的反馈内容，您的支持是我们前进的最大动力！" class="feedback-textarea"></textarea>
            <div class="feedback-actions">
                <button class="layui-btn layui-btn-normal" onclick="submitFeedback()">立即提交</button>
                <button class="layui-btn layui-btn-primary" onclick="clearFeedback()">重置</button>
            </div>
        </div>
    </div>
</div>

<!-- 统计卡片 -->
<div class="dashboard-stats">
    <div class="stat-card stat-user">
        <div class="stat-icon">
            <i class="layui-icon layui-icon-user"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value"><?= $stats['user_count'] ?></div>
            <div class="stat-label">总用户数</div>
            <div class="stat-trend">今日新增 <span class="trend-up"><?= $stats['today_user_count'] ?></span> 人</div>
        </div>
        <div class="stat-extra">
            <i class="layui-icon layui-icon-refresh"></i>
        </div>
    </div>

    <div class="stat-card stat-bookmark">
        <div class="stat-icon">
            <i class="layui-icon layui-icon-link"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value"><?= $stats['bookmark_count'] ?></div>
            <div class="stat-label">书签数量</div>
            <div class="stat-trend">有效书签</div>
        </div>
        <div class="stat-extra">
            <i class="layui-icon layui-icon-refresh"></i>
        </div>
    </div>

    <div class="stat-card stat-category">
        <div class="stat-icon">
            <i class="layui-icon layui-icon-list"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value"><?= $stats['category_count'] ?></div>
            <div class="stat-label">分类数量</div>
            <div class="stat-trend">有效分类</div>
        </div>
        <div class="stat-extra">
            <i class="layui-icon layui-icon-refresh"></i>
        </div>
    </div>

    <div class="stat-card stat-feedback">
        <div class="stat-icon">
            <i class="layui-icon layui-icon-comment"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value"><?= $stats['feedback_count'] ?></div>
            <div class="stat-label">待处理留言</div>
            <div class="stat-trend">需要回复</div>
        </div>
        <div class="stat-extra">
            <i class="layui-icon layui-icon-refresh"></i>
        </div>
    </div>

    <div class="stat-card stat-job">
        <div class="stat-icon">
            <i class="layui-icon layui-icon-rmb"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value"><?= $stats['job_count'] ?></div>
            <div class="stat-label">招聘职位</div>
            <div class="stat-trend">发布中</div>
        </div>
        <div class="stat-extra">
            <i class="layui-icon layui-icon-refresh"></i>
        </div>
    </div>

    <div class="stat-card stat-article">
        <div class="stat-icon">
            <i class="layui-icon layui-icon-file"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value"><?= $stats['article_count'] ?></div>
            <div class="stat-label">文章数量</div>
            <div class="stat-trend">今日新增 <span class="trend-up"><?= $stats['today_article_count'] ?></span> 篇</div>
        </div>
        <div class="stat-extra">
            <i class="layui-icon layui-icon-refresh"></i>
        </div>
    </div>
</div>

<!-- 快捷入口 -->
<div class="dashboard-shortcuts">
    <div class="shortcut-item" onclick="loadContent('/backend/user', this)">
        <div class="shortcut-icon user-icon">
            <i class="layui-icon layui-icon-user"></i>
        </div>
        <div class="shortcut-text">用户管理</div>
    </div>
    <div class="shortcut-item" onclick="loadContent('/backend/bookmark', this)">
        <div class="shortcut-icon bookmark-icon">
            <i class="layui-icon layui-icon-link"></i>
        </div>
        <div class="shortcut-text">书签管理</div>
    </div>
    <div class="shortcut-item" onclick="loadContent('/backend/category', this)">
        <div class="shortcut-icon category-icon">
            <i class="layui-icon layui-icon-list"></i>
        </div>
        <div class="shortcut-text">分类管理</div>
    </div>
    <div class="shortcut-item" onclick="loadContent('/backend/bossjob', this)">
        <div class="shortcut-icon job-icon">
            <i class="layui-icon layui-icon-rmb"></i>
        </div>
        <div class="shortcut-text">招聘管理</div>
    </div>
    <div class="shortcut-item" onclick="loadContent('/backend/article', this)">
        <div class="shortcut-icon article-icon">
            <i class="layui-icon layui-icon-file"></i>
        </div>
        <div class="shortcut-text">文章管理</div>
    </div>
    <div class="shortcut-item" onclick="loadContent('/backend/feedbacks', this)">
        <div class="shortcut-icon feedback-icon">
            <i class="layui-icon layui-icon-comment"></i>
        </div>
        <div class="shortcut-text">留言管理</div>
    </div>
    <div class="shortcut-item" onclick="loadContent('/backend/friendlinks', this)">
        <div class="shortcut-icon friendlink-icon">
            <i class="layui-icon layui-icon-website"></i>
        </div>
        <div class="shortcut-text">友情链接</div>
    </div>
    <div class="shortcut-item" onclick="loadContent('/backend/siteconfigs', this)">
        <div class="shortcut-icon config-icon">
            <i class="layui-icon layui-icon-set"></i>
        </div>
        <div class="shortcut-text">系统配置</div>
    </div>
</div>

<!-- 操作日志 -->
<div class="dashboard-logs">
    <div class="card-header">
        <i class="layui-icon layui-icon-file-b"></i>
        <span>最近操作日志</span>
    </div>
    <div class="logs-table">
        <table class="layui-table" lay-data="{url:'/backend/index/logs', page:false}" lay-filter="logsTable">
            <thead>
                <tr>
                    <th lay-data="{field:'id', width:60}">#</th>
                    <th lay-data="{field:'operator_name', width:100}">用户</th>
                    <th lay-data="{field:'created_at', width:160}">时间</th>
                    <th lay-data="{field:'ip', width:120}">IP</th>
                    <th lay-data="{field:'module', width:100}">模块</th>
                    <th lay-data="{field:'action', width:100}">操作</th>
                    <th lay-data="{field:'content', width:300}">日志内容</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<style>
    /* 内容头部 */
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
        margin-bottom: 20px;
    }
    .content-title {
        font-size: 20px;
        font-weight: 600;
        color: #333;
    }
    .content-subtitle {
        font-size: 14px;
        color: #999;
    }

    /* 顶部三栏卡片 */
    .dashboard-top {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    .dashboard-card {
        flex: 1;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    .card-header {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
        font-size: 15px;
        font-weight: 500;
        color: #333;
    }
    .card-header i:first-child {
        margin-right: 8px;
        color: #1ab394;
        font-size: 16px;
    }
    .refresh-btn {
        margin-left: auto;
        cursor: pointer;
        color: #999;
        font-size: 14px;
        transition: color 0.2s;
    }
    .refresh-btn:hover {
        color: #1ab394;
    }
    .card-body {
        padding: 15px 20px;
    }

    /* 系统信息 */
    .sys-info {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        font-size: 13px;
    }
    .info-label {
        color: #666;
    }
    .info-value {
        color: #333;
        font-family: monospace;
    }

    /* 消息列表 */
    .news-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .news-item {
        display: flex;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px dashed #eee;
        font-size: 13px;
    }
    .news-item:last-child {
        border-bottom: none;
    }
    .news-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #1ab394;
        margin-right: 10px;
        flex-shrink: 0;
    }
    .news-text {
        flex: 1;
        color: #333;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .news-time {
        color: #999;
        font-size: 12px;
        margin-left: 10px;
        flex-shrink: 0;
    }

    /* 反馈表单 */
    .feedback-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .feedback-textarea {
        width: 100%;
        height: 100px;
        padding: 10px;
        border: 1px solid #e6e6e6;
        border-radius: 4px;
        resize: none;
        font-size: 13px;
        line-height: 1.5;
    }
    .feedback-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    /* 统计卡片 */
    .dashboard-stats {
        display: flex;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .stat-card {
        flex: 1;
        min-width: 200px;
        max-width: 240px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }
    .stat-user::before { background: #1ab394; }
    .stat-bookmark::before { background: #667eea; }
    .stat-category::before { background: #f8ac59; }
    .stat-feedback::before { background: #ed5565; }
    .stat-job::before { background: #5f9ee3; }
    .stat-article::before { background: #9673a6; }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 15px;
    }
    .stat-user .stat-icon { background: rgba(26, 179, 148, 0.1); color: #1ab394; }
    .stat-bookmark .stat-icon { background: rgba(102, 126, 234, 0.1); color: #667eea; }
    .stat-category .stat-icon { background: rgba(248, 172, 89, 0.1); color: #f8ac59; }
    .stat-feedback .stat-icon { background: rgba(237, 85, 101, 0.1); color: #ed5565; }
    .stat-job .stat-icon { background: rgba(95, 158, 227, 0.1); color: #5f9ee3; }
    .stat-article .stat-icon { background: rgba(150, 115, 166, 0.1); color: #9673a6; }

    .stat-info {
        flex: 1;
    }
    .stat-value {
        font-size: 28px;
        font-weight: 600;
        color: #333;
        line-height: 1.2;
    }
    .stat-label {
        font-size: 13px;
        color: #666;
        margin-top: 4px;
    }
    .stat-trend {
        font-size: 12px;
        color: #999;
        margin-top: 2px;
    }
    .trend-up {
        color: #1ab394;
        font-weight: 500;
    }
    .trend-down {
        color: #ed5565;
        font-weight: 500;
    }
    .stat-extra {
        color: #ccc;
        font-size: 18px;
        cursor: pointer;
        transition: color 0.2s;
    }
    .stat-extra:hover {
        color: #1ab394;
    }

    /* 快捷入口 */
    .dashboard-shortcuts {
        display: flex;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .shortcut-item {
        width: 100px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        padding: 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .shortcut-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }
    .shortcut-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 10px;
    }
    .user-icon { background: rgba(26, 179, 148, 0.1); color: #1ab394; }
    .bookmark-icon { background: rgba(102, 126, 234, 0.1); color: #667eea; }
    .category-icon { background: rgba(248, 172, 89, 0.1); color: #f8ac59; }
    .job-icon { background: rgba(95, 158, 227, 0.1); color: #5f9ee3; }
    .article-icon { background: rgba(150, 115, 166, 0.1); color: #9673a6; }
    .feedback-icon { background: rgba(237, 85, 101, 0.1); color: #ed5565; }
    .friendlink-icon { background: rgba(91, 192, 222, 0.1); color: #5bc0de; }
    .config-icon { background: rgba(200, 200, 200, 0.1); color: #999; }

    .shortcut-text {
        font-size: 13px;
        color: #333;
        text-align: center;
    }

    /* 操作日志 */
    .dashboard-logs {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    .logs-table {
        padding: 0 20px 20px;
    }
    .logs-table .layui-table {
        margin: 0;
    }

    /* 响应式 */
    @media (max-width: 768px) {
        .dashboard-top {
            flex-direction: column;
        }
        .stat-card {
            max-width: 100%;
        }
    }
</style>

<script>
    layui.use(['form', 'table', 'layer'], function() {
        var table = layui.table;
        var layer = layui.layer;
        
        // 加载系统信息
        loadSysInfo();
        
        // 初始化日志表格
        table.render({
            elem: '.logs-table table',
            url: '/backend/index/logs',
            page: false,
            cols: [[
                {field: 'id', width: 60, title: '#'},
                {field: 'operator_name', width: 100, title: '用户'},
                {field: 'created_at', width: 160, title: '时间'},
                {field: 'ip', width: 120, title: 'IP'},
                {field: 'module', width: 100, title: '模块'},
                {field: 'action', width: 100, title: '操作'},
                {field: 'content', width: 300, title: '日志内容'},
            ]],
            parseData: function(res) {
                return {
                    code: res.code,
                    msg: res.msg,
                    data: res.data,
                };
            },
        });
    });
    
    // 加载系统信息
    function loadSysInfo() {
        layui.$.ajax({
            url: '/backend/index/sysinfo',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.code === 0 && res.data) {
                    document.getElementById('php_version').textContent = res.data.php_version;
                    document.getElementById('mysql_version').textContent = res.data.mysql_version;
                    document.getElementById('server_software').textContent = res.data.server_software;
                    document.getElementById('server_os').textContent = res.data.server_os;
                    document.getElementById('upload_max_size').textContent = res.data.upload_max_size;
                    document.getElementById('memory_limit').textContent = res.data.memory_limit;
                }
            },
            error: function() {
                console.log('加载系统信息失败');
            }
        });
    }
    
    // 刷新系统信息
    function refreshSysInfo() {
        loadSysInfo();
        var icon = document.querySelector('.refresh-btn');
        icon.style.transform = 'rotate(180deg)';
        setTimeout(function() {
            icon.style.transform = 'rotate(0deg)';
        }, 500);
    }
    
    // 提交反馈
    function submitFeedback() {
        var content = document.getElementById('feedback_content').value.trim();
        if (!content) {
            layer.msg('请输入反馈内容', {icon: 5});
            return;
        }
        
        layui.$.ajax({
            url: '/backend/index/feedback',
            type: 'POST',
            data: {content: content},
            dataType: 'json',
            success: function(res) {
                if (res.code === 1) {
                    layer.msg(res.msg, {icon: 1});
                    document.getElementById('feedback_content').value = '';
                } else {
                    layer.msg(res.msg, {icon: 5});
                }
            },
            error: function() {
                layer.msg('提交失败', {icon: 5});
            }
        });
    }
    
    // 清空反馈
    function clearFeedback() {
        document.getElementById('feedback_content').value = '';
    }
</script>