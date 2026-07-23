<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>广告管理</title>
    <link rel="stylesheet" href="/static/backend/layui/css/layui.css">
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #f0f2f5;
        }
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
        .layui-btn-group {
            margin-bottom: 15px;
        }
        .status-tag {
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="search-form">
        <div class="search-item">
            <label>广告标题</label>
            <input type="text" id="ad_title" placeholder="请输入标题" class="layui-input" style="width: 200px;">
        </div>
        <div class="search-item">
            <label>广告位编码</label>
            <input type="text" id="position_code" placeholder="请输入编码" class="layui-input" style="width: 180px;">
        </div>
        <div class="search-item">
            <label>投放平台</label>
            <select id="platform" class="layui-input" style="width: 120px;">
                <option value="">全部</option>
                <option value="1">全部</option>
                <option value="2">PC端</option>
                <option value="3">移动端</option>
                <option value="4">小程序</option>
            </select>
        </div>
        <div class="search-item">
            <label>状态</label>
            <select id="status" class="layui-input" style="width: 120px;">
                <option value="">全部</option>
                <option value="1">草稿</option>
                <option value="2">待审核</option>
                <option value="3">审核通过</option>
                <option value="4">投放中</option>
                <option value="5">已结束</option>
                <option value="6">已暂停</option>
                <option value="7">审核驳回</option>
                <option value="8">已下线</option>
            </select>
        </div>
        <button class="layui-btn layui-btn-primary" onclick="searchData()">搜索</button>
        <button class="layui-btn layui-btn-primary" onclick="resetSearch()">重置</button>
    </div>
    
    <div class="layui-btn-group">
        <button class="layui-btn" onclick="addAdPosition()">添加广告</button>
    </div>
    
    <table id="adpositionsTable" lay-filter="adpositionsTable"></table>
    
    <script type="text/html" id="statusTpl">
        <span class="status-tag" style="background: {{ d.status == 4 ? '#52c41a' : d.status == 2 ? '#faad14' : d.status == 7 ? '#ff4d4f' : '#d9d9d9' }}; color: {{ d.status == 4 || d.status == 2 || d.status == 7 ? '#fff' : '#666' }};">
            {{ d.status_text }}
        </span>
    </script>
    
    <script type="text/html" id="barTpl">
        <button class="layui-btn layui-btn-xs" onclick="editAdPosition({{ d.id }})">编辑</button>
        {{# if(d.status == 1){ }}
        <button class="layui-btn layui-btn-xs layui-btn-warm" onclick="submitAudit({{ d.id }})">提交审核</button>
        {{# } }}
        {{# if(d.status == 2){ }}
        <button class="layui-btn layui-btn-xs layui-btn-normal" onclick="auditPass({{ d.id }})">审核通过</button>
        <button class="layui-btn layui-btn-xs layui-btn-danger" onclick="auditReject({{ d.id }})">驳回</button>
        {{# } }}
        {{# if(d.status == 3){ }}
        <button class="layui-btn layui-btn-xs layui-btn-normal" onclick="startAd({{ d.id }})">开始投放</button>
        {{# } }}
        {{# if(d.status == 4){ }}
        <button class="layui-btn layui-btn-xs layui-btn-warm" onclick="pauseAd({{ d.id }})">暂停投放</button>
        {{# } }}
        {{# if(d.status == 6){ }}
        <button class="layui-btn layui-btn-xs layui-btn-normal" onclick="startAd({{ d.id }})">继续投放</button>
        {{# } }}
        {{# if(d.status == 4 || d.status == 6){ }}
        <button class="layui-btn layui-btn-xs layui-btn-danger" onclick="offlineAd({{ d.id }})">下线</button>
        {{# } }}
        <button class="layui-btn layui-btn-xs layui-btn-danger" onclick="delAdPosition({{ d.id }})">删除</button>
    </script>
    
    <script src="/static/backend/layui/layui.js"></script>
    <script>
        layui.use(['table', 'layer'], function() {
            var table = layui.table;
            var layer = layui.layer;
            
            // 初始化表格
            table.render({
                elem: '#adpositionsTable',
                url: '/backend/adpositions/list',
                page: true,
                limit: 10,
                limits: [10, 20, 50, 100],
                cols: [[
                    {field: 'id', title: 'ID', width: 80, sort: true},
                    {field: 'ad_title', title: '广告标题', width: 200},
                    {field: 'position_code', title: '广告位编码', width: 150},
                    {field: 'platform_text', title: '投放平台', width: 100},
                    {field: 'cost_type_text', title: '计费方式', width: 80},
                    {field: 'start_time', title: '开始时间', width: 160},
                    {field: 'end_time', title: '结束时间', width: 160},
                    {field: 'sort', title: '排序', width: 60},
                    {field: 'impression_count', title: '展示量', width: 100},
                    {field: 'click_count', title: '点击量', width: 100},
                    {field: 'click_rate', title: '点击率', width: 100},
                    {field: 'status_text', title: '状态', width: 100, templet: '#statusTpl'},
                    {title: '操作', width: 350, toolbar: '#barTpl'},
                ]],
            });
        });
        
        // 搜索数据
        function searchData() {
            layui.table.reload('adpositionsTable', {
                where: {
                    ad_title: document.getElementById('ad_title').value,
                    position_code: document.getElementById('position_code').value,
                    platform: document.getElementById('platform').value,
                    status: document.getElementById('status').value,
                },
                page: {
                    curr: 1,
                },
            });
        }
        
        // 重置搜索
        function resetSearch() {
            document.getElementById('ad_title').value = '';
            document.getElementById('position_code').value = '';
            document.getElementById('platform').value = '';
            document.getElementById('status').value = '';
            searchData();
        }
        
        // 添加广告
        window.addAdPosition = function() {
            layer.open({
                type: 2,
                title: '添加广告',
                area: ['800px', '700px'],
                content: '/backend/adpositions/add',
                end: function() {
                    layui.table.reload('adpositionsTable');
                },
            });
        }
        
        // 编辑广告
        window.editAdPosition = function(id) {
            layer.open({
                type: 2,
                title: '编辑广告',
                area: ['800px', '700px'],
                content: '/backend/adpositions/edit?id=' + id,
                end: function() {
                    layui.table.reload('adpositionsTable');
                },
            });
        }
        
        // 删除广告
        window.delAdPosition = function(id) {
            layer.confirm('确定要删除该广告吗？', function(index) {
                layui.$.ajax({
                    url: '/backend/adpositions/del',
                    type: 'POST',
                    data: {id: id},
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1});
                            layui.table.reload('adpositionsTable');
                        } else {
                            layer.msg(res.msg, {icon: 5});
                        }
                    },
                });
                layer.close(index);
            });
        }
        
        // 提交审核
        function submitAudit(id) {
            layer.confirm('确定要提交审核吗？', function(index) {
                layui.$.ajax({
                    url: '/backend/adpositions/submitAudit',
                    type: 'POST',
                    data: {id: id},
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1});
                            layui.table.reload('adpositionsTable');
                        } else {
                            layer.msg(res.msg, {icon: 5});
                        }
                    },
                });
                layer.close(index);
            });
        }
        
        // 审核通过
        function auditPass(id) {
            layer.confirm('确定审核通过吗？', function(index) {
                layui.$.ajax({
                    url: '/backend/adpositions/auditPass',
                    type: 'POST',
                    data: {id: id},
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1});
                            layui.table.reload('adpositionsTable');
                        } else {
                            layer.msg(res.msg, {icon: 5});
                        }
                    },
                });
                layer.close(index);
            });
        }
        
        // 审核驳回
        function auditReject(id) {
            layer.prompt({
                formType: 2,
                value: '',
                title: '请输入驳回原因',
                area: ['400px', '150px'],
            }, function(reject_reason, index) {
                layui.$.ajax({
                    url: '/backend/adpositions/auditReject',
                    type: 'POST',
                    data: {id: id, reject_reason: reject_reason},
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1});
                            layui.table.reload('adpositionsTable');
                        } else {
                            layer.msg(res.msg, {icon: 5});
                        }
                    },
                });
                layer.close(index);
            });
        }
        
        // 开始投放
        function startAd(id) {
            layer.confirm('确定要开始投放吗？', function(index) {
                layui.$.ajax({
                    url: '/backend/adpositions/start',
                    type: 'POST',
                    data: {id: id},
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1});
                            layui.table.reload('adpositionsTable');
                        } else {
                            layer.msg(res.msg, {icon: 5});
                        }
                    },
                });
                layer.close(index);
            });
        }
        
        // 暂停投放
        function pauseAd(id) {
            layer.confirm('确定要暂停投放吗？', function(index) {
                layui.$.ajax({
                    url: '/backend/adpositions/pause',
                    type: 'POST',
                    data: {id: id},
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1});
                            layui.table.reload('adpositionsTable');
                        } else {
                            layer.msg(res.msg, {icon: 5});
                        }
                    },
                });
                layer.close(index);
            });
        }
        
        // 下线广告
        function offlineAd(id) {
            layer.confirm('确定要下线该广告吗？', function(index) {
                layui.$.ajax({
                    url: '/backend/adpositions/offline',
                    type: 'POST',
                    data: {id: id},
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1});
                            layui.table.reload('adpositionsTable');
                        } else {
                            layer.msg(res.msg, {icon: 5});
                        }
                    },
                });
                layer.close(index);
            });
        }
    </script>
</body>
</html>