<div class="content-header">
    <div class="content-title">用户留言管理</div>
</div>

<div class="search-area">
    <div class="search-label">联系人姓名：</div>
    <input type="text" id="fb_name" placeholder="请输入姓名" class="layui-input" style="width: 150px; display: inline;">
    <div class="search-label">留言标题：</div>
    <input type="text" id="fb_title" placeholder="请输入标题" class="layui-input" style="width: 200px; display: inline;">
    <div class="search-label">状态：</div>
    <select id="fb_status" class="layui-input" style="width: 120px; display: inline;">
        <option value="">全部</option>
        <option value="0">未处理</option>
        <option value="1">已处理</option>
    </select>
    <button class="layui-btn" onclick="searchData()">搜索</button>
    <button class="layui-btn layui-btn-primary" onclick="resetSearch()">重置</button>
</div>

<table id="feedbacksTable" lay-filter="feedbacksTable"></table>

<script type="text/html" id="statusTpl">
    {{# if(d.fb_status == 1){ }}
    <span class="status-handled">已处理</span>
    {{# } else { }}
    <span class="status-pending">未处理</span>
    {{# } }}
</script>

<script type="text/html" id="barTpl">
    <button class="layui-btn layui-btn-xs" onclick="viewFeedback({{ d.id }})">查看</button>
    {{# if(d.fb_status == 0){ }}
    <button class="layui-btn layui-btn-xs layui-btn-warm" onclick="replyFeedback({{ d.id }})">回复</button>
    <button class="layui-btn layui-btn-xs layui-btn-normal" onclick="handleFeedback({{ d.id }})">标记已处理</button>
    {{# } }}
    <button class="layui-btn layui-btn-xs layui-btn-danger" onclick="delFeedback({{ d.id }})">删除</button>
</script>

<style>
    .status-pending {
        color: #faad14;
    }
    .status-handled {
        color: #52c41a;
    }
</style>

<script>
    layui.use(['table', 'layer'], function() {
        var table = layui.table;
        var layer = layui.layer;
        
        // 初始化表格
        table.render({
            elem: '#feedbacksTable',
            url: '/backend/feedbacks/list',
            page: true,
            limit: 10,
            limits: [10, 20, 50, 100],
            where: {},
            cols: [[
                {field: 'id', title: 'ID', width: 80, sort: true},
                {field: 'fb_name', title: '联系人姓名', width: 120},
                {field: 'fb_phone', title: '联系电话', width: 120},
                {field: 'fb_email', title: '邮箱', width: 180},
                {field: 'fb_company', title: '公司名称', width: 120},
                {field: 'fb_title', title: '留言标题', width: 200},
                {field: 'fb_status_text', title: '状态', width: 80, templet: '#statusTpl'},
                {field: 'ip', title: 'IP地址', width: 130},
                {field: 'created_at', title: '留言时间', width: 160},
                {title: '操作', width: 250, toolbar: '#barTpl'},
            ]],
        });
    });
    
    // 搜索数据
    window.searchData = function() {
        layui.table.reload('feedbacksTable', {
            where: {
                fb_name: document.getElementById('fb_name').value,
                fb_title: document.getElementById('fb_title').value,
                fb_status: document.getElementById('fb_status').value,
            },
            page: {
                curr: 1,
            },
        });
    }
    
    // 重置搜索
    window.resetSearch = function() {
        document.getElementById('fb_name').value = '';
        document.getElementById('fb_title').value = '';
        document.getElementById('fb_status').value = '';
        window.searchData();
    }
    
    // 查看留言
    window.viewFeedback = function(id) {
        layer.open({
            type: 2,
            title: '查看留言',
            area: ['700px', '550px'],
            content: '/backend/feedbacks/view?id=' + id,
        });
    }
    
    // 回复留言
    window.replyFeedback = function(id) {
        layer.open({
            type: 2,
            title: '回复留言',
            area: ['700px', '550px'],
            content: '/backend/feedbacks/reply?id=' + id,
            end: function() {
                layui.table.reload('feedbacksTable');
            },
        });
    }
    
    // 标记已处理
    window.handleFeedback = function(id) {
        layer.confirm('确定要标记为已处理吗？', function(index) {
            layui.$.ajax({
                url: '/backend/feedbacks/handle',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(res) {
                    if (res.code === 1) {
                        layer.msg(res.msg, {icon: 1});
                        layui.table.reload('feedbacksTable');
                    } else {
                        layer.msg(res.msg, {icon: 5});
                    }
                },
            });
            layer.close(index);
        });
    }
    
    // 删除留言
    window.delFeedback = function(id) {
        layer.confirm('确定要删除该留言吗？', function(index) {
            layui.$.ajax({
                url: '/backend/feedbacks/del',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(res) {
                    if (res.code === 1) {
                        layer.msg(res.msg, {icon: 1});
                        layui.table.reload('feedbacksTable');
                    } else {
                        layer.msg(res.msg, {icon: 5});
                    }
                },
            });
            layer.close(index);
        });
    }
</script>