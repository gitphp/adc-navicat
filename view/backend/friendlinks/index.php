<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>友情链接管理</title>
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
        .status-normal {
            color: #52c41a;
        }
        .status-disable {
            color: #ff4d4f;
        }
        .logo-preview {
            width: 40px;
            height: 40px;
            border-radius: 4px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="search-form">
        <div class="search-item">
            <label>网站名称</label>
            <input type="text" id="link_name" placeholder="请输入网站名称" class="layui-input" style="width: 200px;">
        </div>
        <div class="search-item">
            <label>状态</label>
            <select id="link_status" class="layui-input" style="width: 120px;">
                <option value="">全部</option>
                <option value="1">启用</option>
                <option value="0">禁用</option>
            </select>
        </div>
        <button class="layui-btn layui-btn-primary" onclick="searchData()">搜索</button>
        <button class="layui-btn layui-btn-primary" onclick="resetSearch()">重置</button>
    </div>
    
    <div class="layui-btn-group">
        <button class="layui-btn" onclick="addFriendLink()">添加友情链接</button>
    </div>
    
    <table id="friendlinksTable" lay-filter="friendlinksTable"></table>
    
    <script type="text/html" id="logoTpl">
        {{# if(d.link_logo){ }}
        <img src="{{ d.link_logo }}" class="logo-preview" alt="Logo">
        {{# } else { }}
        <span>-</span>
        {{# } }}
    </script>
    
    <script type="text/html" id="statusTpl">
        {{# if(d.link_status == 1){ }}
        <span class="status-normal">启用</span>
        {{# } else { }}
        <span class="status-disable">禁用</span>
        {{# } }}
    </script>
    
    <script type="text/html" id="barTpl">
        <button class="layui-btn layui-btn-xs" onclick="editFriendLink({{ d.id }})">编辑</button>
        <button class="layui-btn layui-btn-xs layui-btn-danger" onclick="delFriendLink({{ d.id }})">删除</button>
        {{# if(d.link_status == 1){ }}
        <button class="layui-btn layui-btn-xs layui-btn-warm" onclick="toggleStatus({{ d.id }}, 0)">禁用</button>
        {{# } else { }}
        <button class="layui-btn layui-btn-xs layui-btn-normal" onclick="toggleStatus({{ d.id }}, 1)">启用</button>
        {{# } }}
    </script>
    
    <script src="/static/backend/layui/layui.js"></script>
    <script>
        layui.use(['table', 'layer'], function() {
            var table = layui.table;
            var layer = layui.layer;
            
            // 初始化表格
            table.render({
                elem: '#friendlinksTable',
                url: '/backend/friendlinks/list',
                page: true,
                limit: 10,
                limits: [10, 20, 50, 100],
                cols: [[
                    {field: 'id', title: 'ID', width: 80, sort: true},
                    {field: 'link_name', title: '网站名称', width: 150},
                    {field: 'link_logo', title: 'Logo', width: 80, templet: '#logoTpl'},
                    {field: 'link_url', title: '网站链接', width: 250},
                    {field: 'link_desc', title: '网站描述', width: 200},
                    {field: 'link_sort', title: '排序', width: 80},
                    {field: 'link_status_text', title: '状态', width: 80, templet: '#statusTpl'},
                    {field: 'created_at', title: '创建时间', width: 160},
                    {title: '操作', width: 220, toolbar: '#barTpl'},
                ]],
            });
        });
        
        // 搜索数据
        function searchData() {
            layui.table.reload('friendlinksTable', {
                where: {
                    link_name: document.getElementById('link_name').value,
                    link_status: document.getElementById('link_status').value,
                },
                page: {
                    curr: 1,
                },
            });
        }
        
        // 重置搜索
        function resetSearch() {
            document.getElementById('link_name').value = '';
            document.getElementById('link_status').value = '';
            searchData();
        }
        
        // 添加友情链接
        function addFriendLink() {
            layer.open({
                type: 2,
                title: '添加友情链接',
                area: ['550px', '500px'],
                content: '/backend/friendlinks/add',
                end: function() {
                    layui.table.reload('friendlinksTable');
                },
            });
        }
        
        // 编辑友情链接
        function editFriendLink(id) {
            layer.open({
                type: 2,
                title: '编辑友情链接',
                area: ['550px', '500px'],
                content: '/backend/friendlinks/edit?id=' + id,
                end: function() {
                    layui.table.reload('friendlinksTable');
                },
            });
        }
        
        // 删除友情链接
        function delFriendLink(id) {
            layer.confirm('确定要删除该友情链接吗？', function(index) {
                layui.$.ajax({
                    url: '/backend/friendlinks/del',
                    type: 'POST',
                    data: {id: id},
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1});
                            layui.table.reload('friendlinksTable');
                        } else {
                            layer.msg(res.msg, {icon: 5});
                        }
                    },
                });
                layer.close(index);
            });
        }
        
        // 切换状态
        function toggleStatus(id, status) {
            var text = status == 1 ? '启用' : '禁用';
            layer.confirm('确定要' + text + '该友情链接吗？', function(index) {
                layui.$.ajax({
                    url: '/backend/friendlinks/status',
                    type: 'POST',
                    data: {id: id, status: status},
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1});
                            layui.table.reload('friendlinksTable');
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