<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>菜单管理</title>
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
        .add-btn {
            padding: 6px 16px;
            background: #1E9FFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .add-btn:hover {
            background: #0080FF;
        }
        .main-content {
            padding: 20px;
            min-height: calc(100vh - 60px);
            background: #f0f2f5;
        }
        .search-form {
            background: #fff;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }
        .search-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 10px;
        }
        .search-label {
            display: inline-block;
            width: 70px;
            text-align: right;
            margin-right: 10px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .status-enabled {
            background: #f0f9eb;
            color: #67c23a;
        }
        .status-disabled {
            background: #fef0f0;
            color: #f56c6c;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-title">菜单管理</div>
        <div class="header-right">
            <button class="add-btn" onclick="addMenu()">添加菜单</button>
        </div>
    </div>
    
    <div class="main-content">
        <div class="search-form">
            <div class="search-item">
                <span class="search-label">菜单名称：</span>
                <input type="text" id="menu_name" placeholder="请输入菜单名称" class="layui-input" style="width: 200px; display: inline;">
            </div>
            <div class="search-item">
                <span class="search-label">路由路径：</span>
                <input type="text" id="menu_path" placeholder="请输入路由路径" class="layui-input" style="width: 200px; display: inline;">
            </div>
            <div class="search-item">
                <span class="search-label">菜单状态：</span>
                <select id="menu_status" class="layui-input" style="width: 150px; display: inline;">
                    <option value="">全部</option>
                    <option value="1">启用</option>
                    <option value="0">禁用</option>
                </select>
            </div>
            <button class="layui-btn" onclick="search()">搜索</button>
            <button class="layui-btn layui-btn-primary" onclick="resetSearch()">重置</button>
        </div>
        
        <table id="menuTable" lay-filter="menuTable"></table>
    </div>
    
    <!-- 操作栏模板 -->
    <script type="text/html" id="toolbar">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>
        {{# if(d.menu_status == 1){ }}
        <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="disable">禁用</a>
        {{# } else { }}
        <a class="layui-btn layui-btn-xs" lay-event="enable">启用</a>
        {{# } }}
    </script>
    
    <!-- 状态模板 -->
    <script type="text/html" id="statusTpl">
        {{# if(d.menu_status == 1){ }}
        <span class="status-badge status-enabled">启用</span>
        {{# } else { }}
        <span class="status-badge status-disabled">禁用</span>
        {{# } }}
    </script>

    <script src="/static/backend/layui/layui.js"></script>
    <script>
        layui.use(['table', 'layer', 'form'], function() {
            var table = layui.table;
            var layer = layui.layer;
            var form = layui.form;
            
            // 渲染表格
            var tableIns = table.render({
                elem: '#menuTable',
                url: '/backend/menu/list',
                page: true,
                limit: 10,
                limits: [10, 20, 50],
                cols: [[
                    {type: 'numbers', title: '序号', width: 80},
                    {field: 'menu_name', title: '菜单名称', width: 150},
                    {field: 'parent_name', title: '父级菜单', width: 120},
                    {field: 'menu_path', title: '路由路径', width: 200},
                    {field: 'component', title: '组件路径', width: 200},
                    {field: 'menu_icon', title: '图标', width: 120},
                    {field: 'permission_code', title: '权限标识', width: 150},
                    {field: 'menu_sort', title: '排序', width: 80},
                    {field: 'menu_status_text', title: '状态', width: 100, templet: '#statusTpl'},
                    {field: 'created_at', title: '创建时间', width: 170},
                    {title: '操作', width: 200, templet: '#toolbar'},
                ]],
            });
            
            // 监听行工具事件
            table.on('tool(menuTable)', function(obj) {
                var data = obj.data;
                var layEvent = obj.event;
                
                if (layEvent === 'edit') {
                    editMenu(data.id);
                } else if (layEvent === 'del') {
                    deleteMenu(data.id, data.menu_name);
                } else if (layEvent === 'disable') {
                    changeStatus(data.id, 0);
                } else if (layEvent === 'enable') {
                    changeStatus(data.id, 1);
                }
            });
            
            // 添加菜单
            window.addMenu = function() {
                layer.open({
                    type: 2,
                    title: '添加菜单',
                    area: ['650px', '550px'],
                    content: '/backend/menu/add',
                    end: function() {
                        tableIns.reload();
                    }
                });
            }
            
            // 编辑菜单
            window.editMenu = function(id) {
                layer.open({
                    type: 2,
                    title: '编辑菜单',
                    area: ['650px', '550px'],
                    content: '/backend/menu/edit?id=' + id,
                    end: function() {
                        tableIns.reload();
                    }
                });
            }
            
            // 删除菜单
            window.deleteMenu = function(id, name) {
                layer.confirm('确定要删除菜单「' + name + '」吗？', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    layui.$.ajax({
                        url: '/backend/menu/del',
                        type: 'POST',
                        data: {id: id},
                        dataType: 'json',
                        success: function(res) {
                            if (res.code === 1) {
                                layer.msg(res.msg, {icon: 1});
                                tableIns.reload();
                            } else {
                                layer.msg(res.msg, {icon: 5});
                            }
                        },
                        error: function() {
                            layer.msg('删除失败', {icon: 5});
                        }
                    });
                });
            }
            
            // 切换状态
            window.changeStatus = function(id, status) {
                var statusText = status === 1 ? '启用' : '禁用';
                layer.confirm('确定要' + statusText + '该菜单吗？', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    layui.$.ajax({
                        url: '/backend/menu/status',
                        type: 'POST',
                        data: {id: id, status: status},
                        dataType: 'json',
                        success: function(res) {
                            if (res.code === 1) {
                                layer.msg(res.msg, {icon: 1});
                                tableIns.reload();
                            } else {
                                layer.msg(res.msg, {icon: 5});
                            }
                        },
                        error: function() {
                            layer.msg('操作失败', {icon: 5});
                        }
                    });
                });
            }
            
            // 搜索
            window.search = function() {
                tableIns.reload({
                    where: {
                        menu_name: document.getElementById('menu_name').value,
                        menu_path: document.getElementById('menu_path').value,
                        menu_status: document.getElementById('menu_status').value,
                    },
                    page: {
                        curr: 1
                    }
                });
            }
            
            // 重置搜索
            window.resetSearch = function() {
                document.getElementById('menu_name').value = '';
                document.getElementById('menu_path').value = '';
                document.getElementById('menu_status').value = '';
                window.search();
            }
        });
    </script>
</body>
</html>
