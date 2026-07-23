<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户管理</title>
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
        .status-normal {
            background: #f0f9eb;
            color: #67c23a;
        }
        .status-disabled {
            background: #fef0f0;
            color: #f56c6c;
        }
        .auth-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .auth-none {
            background: #f5f5f5;
            color: #999;
        }
        .auth-pending {
            background: #fdf6ec;
            color: #e6a23c;
        }
        .auth-verified {
            background: #f0f9eb;
            color: #67c23a;
        }
        .auth-rejected {
            background: #fef0f0;
            color: #f56c6c;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-title">用户管理</div>
    </div>
    
    <div class="main-content">
        <div class="search-form">
            <div class="search-item">
                <span class="search-label">用户名：</span>
                <input type="text" id="user_name" placeholder="请输入用户名" class="layui-input" style="width: 200px; display: inline;">
            </div>
            <div class="search-item">
                <span class="search-label">昵称：</span>
                <input type="text" id="user_nick" placeholder="请输入昵称" class="layui-input" style="width: 200px; display: inline;">
            </div>
            <div class="search-item">
                <span class="search-label">手机号：</span>
                <input type="text" id="user_mobile" placeholder="请输入手机号" class="layui-input" style="width: 200px; display: inline;">
            </div>
            <div class="search-item">
                <span class="search-label">状态：</span>
                <select id="user_status" class="layui-input" style="width: 150px; display: inline;">
                    <option value="">全部</option>
                    <option value="1">正常</option>
                    <option value="0">禁用</option>
                </select>
            </div>
            <button class="layui-btn" onclick="search()">搜索</button>
            <button class="layui-btn layui-btn-primary" onclick="resetSearch()">重置</button>
        </div>
        
        <table id="userTable" lay-filter="userTable"></table>
    </div>
    
    <!-- 操作栏模板 -->
    <script type="text/html" id="toolbar">
        <a class="layui-btn layui-btn-xs" lay-event="edit">分配角色</a>
        {{# if(d.user_status == 1){ }}
        <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="disable">禁用</a>
        {{# } else { }}
        <a class="layui-btn layui-btn-xs" lay-event="enable">启用</a>
        {{# } }}
        <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>
    </script>
    
    <!-- 状态模板 -->
    <script type="text/html" id="statusTpl">
        {{# if(d.user_status == 1){ }}
        <span class="status-badge status-normal">正常</span>
        {{# } else { }}
        <span class="status-badge status-disabled">禁用</span>
        {{# } }}
    </script>
    
    <!-- 实名状态模板 -->
    <script type="text/html" id="authTpl">
        {{# if(d.real_auth_status == 0){ }}
        <span class="auth-badge auth-none">未实名</span>
        {{# } else if(d.real_auth_status == 1){ }}
        <span class="auth-badge auth-pending">待审核</span>
        {{# } else if(d.real_auth_status == 2){ }}
        <span class="auth-badge auth-verified">已实名</span>
        {{# } else { }}
        <span class="auth-badge auth-rejected">驳回</span>
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
                elem: '#userTable',
                url: '/backend/user/list',
                page: true,
                limit: 10,
                limits: [10, 20, 50],
                cols: [[
                    {type: 'numbers', title: '序号', width: 80},
                    {field: 'user_name', title: '用户名', width: 120},
                    {field: 'user_nick', title: '昵称', width: 120},
                    {field: 'user_mobile', title: '手机号', width: 130},
                    {field: 'user_email', title: '邮箱', width: 150},
                    {field: 'user_status_text', title: '状态', width: 100, templet: '#statusTpl'},
                    {field: 'real_auth_text', title: '实名状态', width: 100, templet: '#authTpl'},
                    {field: 'role_names', title: '角色', width: 200},
                    {field: 'register_time', title: '注册时间', width: 170},
                    {field: 'last_login_time', title: '最后登录', width: 170},
                    {title: '操作', width: 200, templet: '#toolbar'},
                ]],
            });
            
            // 监听行工具事件
            table.on('tool(userTable)', function(obj) {
                var data = obj.data;
                var layEvent = obj.event;
                
                if (layEvent === 'edit') {
                    editUser(data.id);
                } else if (layEvent === 'del') {
                    deleteUser(data.id, data.user_name);
                } else if (layEvent === 'disable') {
                    changeStatus(data.id, 0);
                } else if (layEvent === 'enable') {
                    changeStatus(data.id, 1);
                }
            });
            
            // 编辑用户（分配角色）
            function editUser(id) {
                layer.open({
                    type: 2,
                    title: '分配角色',
                    area: ['500px', '400px'],
                    content: '/backend/user/edit?id=' + id,
                    end: function() {
                        tableIns.reload();
                    }
                });
            }
            
            // 删除用户
            function deleteUser(id, name) {
                layer.confirm('确定要删除用户「' + name + '」吗？', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    layui.$.ajax({
                        url: '/backend/user/del',
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
            function changeStatus(id, status) {
                var statusText = status === 1 ? '启用' : '禁用';
                layer.confirm('确定要' + statusText + '该用户吗？', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    layui.$.ajax({
                        url: '/backend/user/status',
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
                        user_name: document.getElementById('user_name').value,
                        user_nick: document.getElementById('user_nick').value,
                        user_mobile: document.getElementById('user_mobile').value,
                        user_status: document.getElementById('user_status').value,
                    },
                    page: {
                        curr: 1
                    }
                });
            }
            
            // 重置搜索
            window.resetSearch = function() {
                document.getElementById('user_name').value = '';
                document.getElementById('user_nick').value = '';
                document.getElementById('user_mobile').value = '';
                document.getElementById('user_status').value = '';
                window.search();
            }
        });
    </script>
</body>
</html>
