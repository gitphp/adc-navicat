<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>权限管理</title>
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
        .type-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .type-menu {
            background: #ecf5ff;
            color: #409eff;
        }
        .type-button {
            background: #f0f9eb;
            color: #67c23a;
        }
        .type-api {
            background: #f5f0ff;
            color: #9b59b6;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-title">权限管理</div>
        <div class="header-right">
            <button class="add-btn" onclick="addPermission()">添加权限</button>
        </div>
    </div>
    
    <div class="main-content">
        <div class="search-form">
            <div class="search-item">
                <span class="search-label">权限名称：</span>
                <input type="text" id="per_name" placeholder="请输入权限名称" class="layui-input" style="width: 200px; display: inline;">
            </div>
            <div class="search-item">
                <span class="search-label">权限标识：</span>
                <input type="text" id="per_code" placeholder="请输入权限标识" class="layui-input" style="width: 200px; display: inline;">
            </div>
            <div class="search-item">
                <span class="search-label">权限类型：</span>
                <select id="per_type" class="layui-input" style="width: 150px; display: inline;">
                    <option value="">全部</option>
                    <option value="menu">菜单</option>
                    <option value="button">按钮</option>
                    <option value="api">接口</option>
                </select>
            </div>
            <div class="search-item">
                <span class="search-label">权限状态：</span>
                <select id="per_status" class="layui-input" style="width: 150px; display: inline;">
                    <option value="">全部</option>
                    <option value="1">启用</option>
                    <option value="0">禁用</option>
                </select>
            </div>
            <button class="layui-btn" onclick="search()">搜索</button>
            <button class="layui-btn layui-btn-primary" onclick="resetSearch()">重置</button>
        </div>
        
        <table id="permissionTable" lay-filter="permissionTable"></table>
    </div>
    
    <!-- 操作栏模板 -->
    <script type="text/html" id="toolbar">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>
        {{# if(d.per_status == 1){ }}
        <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="disable">禁用</a>
        {{# } else { }}
        <a class="layui-btn layui-btn-xs" lay-event="enable">启用</a>
        {{# } }}
    </script>
    
    <!-- 状态模板 -->
    <script type="text/html" id="statusTpl">
        {{# if(d.per_status == 1){ }}
        <span class="status-badge status-enabled">启用</span>
        {{# } else { }}
        <span class="status-badge status-disabled">禁用</span>
        {{# } }}
    </script>
    
    <!-- 类型模板 -->
    <script type="text/html" id="typeTpl">
        {{# if(d.per_type == 'menu'){ }}
        <span class="type-badge type-menu">菜单</span>
        {{# } else if(d.per_type == 'button'){ }}
        <span class="type-badge type-button">按钮</span>
        {{# } else { }}
        <span class="type-badge type-api">接口</span>
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
                elem: '#permissionTable',
                url: '/backend/permission/list',
                page: true,
                limit: 10,
                limits: [10, 20, 50],
                where: {},
                cols: [[
                    {type: 'numbers', title: '序号', width: 80},
                    {field: 'per_name', title: '权限名称', width: 150},
                    {field: 'per_code', title: '权限标识', width: 180},
                    {field: 'per_type_text', title: '权限类型', width: 100, templet: '#typeTpl'},
                    {field: 'parent_name', title: '父级权限', width: 120},
                    {field: 'per_path', title: '路径', width: 180},
                    {field: 'per_method', title: '方法', width: 80},
                    {field: 'per_icon', title: '图标', width: 100},
                    {field: 'per_sort', title: '排序', width: 80},
                    {field: 'per_status_text', title: '状态', width: 100, templet: '#statusTpl'},
                    {field: 'created_at', title: '创建时间', width: 170},
                    {title: '操作', width: 200, templet: '#toolbar'},
                ]],
            });
            
            // 监听行工具事件
            table.on('tool(permissionTable)', function(obj) {
                var data = obj.data;
                var layEvent = obj.event;
                
                if (layEvent === 'edit') {
                    editPermission(data.id);
                } else if (layEvent === 'del') {
                    deletePermission(data.id, data.per_name);
                } else if (layEvent === 'disable') {
                    changeStatus(data.id, 0);
                } else if (layEvent === 'enable') {
                    changeStatus(data.id, 1);
                }
            });
            
            // 添加权限
            window.addPermission = function() {
                layer.open({
                    type: 2,
                    title: '添加权限',
                    area: ['600px', '500px'],
                    content: '/backend/permission/add',
                    end: function() {
                        tableIns.reload();
                    }
                });
            }
            
            // 编辑权限
            window.editPermission = function(id) {
                layer.open({
                    type: 2,
                    title: '编辑权限',
                    area: ['600px', '500px'],
                    content: '/backend/permission/edit?id=' + id,
                    end: function() {
                        tableIns.reload();
                    }
                });
            }
            
            // 删除权限
            window.deletePermission = function(id, name) {
                layer.confirm('确定要删除权限「' + name + '」吗？', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    layui.$.ajax({
                        url: '/backend/permission/del',
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
                layer.confirm('确定要' + statusText + '该权限吗？', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    layui.$.ajax({
                        url: '/backend/permission/status',
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
                        per_name: document.getElementById('per_name').value,
                        per_code: document.getElementById('per_code').value,
                        per_type: document.getElementById('per_type').value,
                        per_status: document.getElementById('per_status').value,
                    },
                    page: {
                        curr: 1
                    }
                });
            }
            
            // 重置搜索
            window.resetSearch = function() {
                document.getElementById('per_name').value = '';
                document.getElementById('per_code').value = '';
                document.getElementById('per_type').value = '';
                document.getElementById('per_status').value = '';
                window.search();
            }
        });
    </script>
</body>
</html>
