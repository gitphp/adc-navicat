<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>站点配置管理</title>
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
    </style>
</head>
<body>
    <div class="search-form">
        <div class="search-item">
            <label>配置分组</label>
            <select id="conf_group" class="layui-input" style="width: 150px;">
                <option value="">全部</option>
                <option value="basic">基础设置</option>
                <option value="seo">SEO优化</option>
                <option value="contact">联系方式</option>
                <option value="social">社交账号</option>
            </select>
        </div>
        <div class="search-item">
            <label>配置键名</label>
            <input type="text" id="conf_key" placeholder="请输入配置键名" class="layui-input" style="width: 200px;">
        </div>
        <button class="layui-btn layui-btn-primary" onclick="searchData()">搜索</button>
        <button class="layui-btn layui-btn-primary" onclick="resetSearch()">重置</button>
    </div>
    
    <div class="layui-btn-group">
        <button class="layui-btn" onclick="addConfig()">添加配置</button>
    </div>
    
    <table id="siteConfigsTable" lay-filter="siteConfigsTable"></table>
    
    <script type="text/html" id="barTpl">
        <button class="layui-btn layui-btn-xs" onclick="editConfig({{ d.id }})">编辑</button>
        <button class="layui-btn layui-btn-xs layui-btn-danger" onclick="delConfig({{ d.id }})">删除</button>
    </script>
    
    <script src="/static/backend/layui/layui.js"></script>
    <script>
        layui.use(['table', 'layer'], function() {
            var table = layui.table;
            var layer = layui.layer;
            
            // 初始化表格
            table.render({
                elem: '#siteConfigsTable',
                url: '/backend/siteconfigs/list',
                page: true,
                limit: 10,
                limits: [10, 20, 50, 100],
                cols: [[
                    {field: 'id', title: 'ID', width: 80, sort: true},
                    {field: 'conf_group_text', title: '配置分组', width: 120},
                    {field: 'conf_key', title: '配置键名', width: 200},
                    {field: 'conf_value', title: '配置值', width: 250},
                    {field: 'conf_desc', title: '配置说明', width: 180},
                    {field: 'input_type_text', title: '输入类型', width: 120},
                    {field: 'conf_sort', title: '排序', width: 80},
                    {field: 'updated_at', title: '更新时间', width: 160},
                    {title: '操作', width: 150, toolbar: '#barTpl'},
                ]],
            });
        });
        
        // 搜索数据
        function searchData() {
            layui.table.reload('siteConfigsTable', {
                where: {
                    conf_group: document.getElementById('conf_group').value,
                    conf_key: document.getElementById('conf_key').value,
                },
                page: {
                    curr: 1,
                },
            });
        }
        
        // 重置搜索
        function resetSearch() {
            document.getElementById('conf_group').value = '';
            document.getElementById('conf_key').value = '';
            searchData();
        }
        
        // 添加配置
        window.addConfig = function() {
            layer.open({
                type: 2,
                title: '添加配置',
                area: ['550px', '450px'],
                content: '/backend/siteconfigs/add',
                end: function() {
                    layui.table.reload('siteConfigsTable');
                },
            });
        }
        
        // 编辑配置
        window.editConfig = function(id) {
            layer.open({
                type: 2,
                title: '编辑配置',
                area: ['550px', '450px'],
                content: '/backend/siteconfigs/edit?id=' + id,
                end: function() {
                    layui.table.reload('siteConfigsTable');
                },
            });
        }
        
        // 删除配置
        window.delConfig = function(id) {
            layer.confirm('确定要删除该配置吗？', function(index) {
                layui.$.ajax({
                    url: '/backend/siteconfigs/del',
                    type: 'POST',
                    data: {id: id},
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1});
                            layui.table.reload('siteConfigsTable');
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