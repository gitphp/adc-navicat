<div class="content-header">
    <div class="content-title">操作日志管理</div>
</div>

<div class="search-form">
    <div class="search-item">
        <label>操作人</label>
        <input type="text" id="operator_name" placeholder="请输入操作人名称" class="layui-input" style="width: 150px;">
    </div>
    <div class="search-item">
        <label>业务模块</label>
        <select id="biz_type" class="layui-input" style="width: 120px;">
            <option value="">全部</option>
            <option value="user">用户管理</option>
            <option value="role">角色管理</option>
            <option value="permission">权限管理</option>
            <option value="menu">菜单管理</option>
            <option value="article">文章管理</option>
            <option value="category">分类管理</option>
            <option value="bookmark">书签管理</option>
            <option value="adslots">广告位管理</option>
            <option value="adpositions">广告管理</option>
            <option value="friendlinks">友情链接</option>
            <option value="feedbacks">用户留言</option>
            <option value="siteconfigs">站点配置</option>
            <option value="system">系统操作</option>
        </select>
    </div>
    <div class="search-item">
        <label>操作类型</label>
        <select id="action" class="layui-input" style="width: 120px;">
            <option value="">全部</option>
            <option value="INSERT">新增</option>
            <option value="UPDATE">修改</option>
            <option value="DELETE">删除</option>
            <option value="LOGIN">登录</option>
            <option value="VIEW">查看</option>
        </select>
    </div>
    <div class="search-item">
        <label>操作状态</label>
        <select id="operator_status" class="layui-input" style="width: 120px;">
            <option value="">全部</option>
            <option value="1">成功</option>
            <option value="0">失败</option>
        </select>
    </div>
    <div class="search-item">
        <label>时间范围</label>
        <input type="date" id="start_time" class="layui-input" style="width: 130px;">
        <span style="margin: 0 5px;">-</span>
        <input type="date" id="end_time" class="layui-input" style="width: 130px;">
    </div>
    <button class="layui-btn layui-btn-primary" onclick="searchData()">搜索</button>
    <button class="layui-btn layui-btn-primary" onclick="resetSearch()">重置</button>
</div>

<table id="operationLogTable" lay-filter="operationLogTable"></table>

<script type="text/html" id="statusTpl">
    {{# if(d.operator_status == 1){ }}
    <span class="status-success">成功</span>
    {{# } else { }}
    <span class="status-fail">失败</span>
    {{# } }}
</script>

<script type="text/html" id="barTpl">
    <button class="layui-btn layui-btn-xs" onclick="viewLog({{ d.id }})">详情</button>
</script>

<style>
    .search-form {
        background: #fff;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
    }
    .search-item {
        display: inline-block;
        margin-right: 20px;
        margin-bottom: 10px;
    }
    .search-item label {
        margin-right: 8px;
        color: #666;
    }
    .status-success {
        color: #52c41a;
    }
    .status-fail {
        color: #ff4d4f;
    }
</style>

<script>
    layui.use(['table', 'layer'], function() {
        var table = layui.table;
        var layer = layui.layer;
        
        // 初始化表格
        table.render({
            elem: '#operationLogTable',
            url: '/backend/operationlog/list',
            page: true,
            limit: 10,
            limits: [10, 20, 50, 100],
            where: {},
            cols: [[
                {field: 'id', title: 'ID', width: 80, sort: true},
                {field: 'operator_name', title: '操作人', width: 120},
                {field: 'biz_type_text', title: '业务模块', width: 120},
                {field: 'action_text', title: '操作类型', width: 100},
                {field: 'biz_label', title: '操作对象', minWidth: 150},
                {field: 'operator_status_text', title: '操作状态', width: 80, templet: '#statusTpl'},
                {field: 'client_ip', title: 'IP地址', width: 130},
                {field: 'created_at', title: '操作时间', width: 160},
                {title: '操作', width: 90, templet: '#barTpl', fixed: 'right'},
            ]],
        });
        
        // 搜索数据
        window.searchData = function() {
            table.reload('operationLogTable', {
                where: {
                    operator_name: document.getElementById('operator_name').value,
                    biz_type: document.getElementById('biz_type').value,
                    action: document.getElementById('action').value,
                    operator_status: document.getElementById('operator_status').value,
                    start_time: document.getElementById('start_time').value,
                    end_time: document.getElementById('end_time').value,
                },
                page: {
                    curr: 1,
                },
            });
        };
        
        // 重置搜索
        window.resetSearch = function() {
            document.getElementById('operator_name').value = '';
            document.getElementById('biz_type').value = '';
            document.getElementById('action').value = '';
            document.getElementById('operator_status').value = '';
            document.getElementById('start_time').value = '';
            document.getElementById('end_time').value = '';
            window.searchData();
        };
        
        // 查看日志详情
        window.viewLog = function(id) {
            layer.open({
                type: 2,
                title: '日志详情',
                area: ['800px', '600px'],
                content: '/backend/operationlog/view?id=' + id,
            });
        };
    });
</script>