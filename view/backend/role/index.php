<div class="content-header">
    <div class="content-title">角色管理</div>
    <div class="content-actions">
        <button class="layui-btn" onclick="addRole()">
            <i class="layui-icon layui-icon-add"></i> 添加角色
        </button>
    </div>
</div>

<div class="search-form">
    <div class="search-item">
        <span class="search-label">角色名称：</span>
        <input type="text" id="role_name" placeholder="请输入角色名称" class="layui-input" style="width: 200px; display: inline;">
    </div>
    <div class="search-item">
        <span class="search-label">角色标识：</span>
        <input type="text" id="role_code" placeholder="请输入角色标识" class="layui-input" style="width: 200px; display: inline;">
    </div>
    <div class="search-item">
        <span class="search-label">角色状态：</span>
        <select id="role_status" class="layui-input" style="width: 150px; display: inline;">
            <option value="">全部</option>
            <option value="1">启用</option>
            <option value="0">禁用</option>
        </select>
    </div>
    <button class="layui-btn" onclick="search()">搜索</button>
    <button class="layui-btn layui-btn-primary" onclick="resetSearch()">重置</button>
</div>

<table id="roleTable" lay-filter="roleTable"></table>

<!-- 操作栏模板 -->
<script type="text/html" id="toolbar">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-xs" lay-event="permission">权限</a>
    <a class="layui-btn layui-btn-xs" lay-event="menu">菜单</a>
    <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>
    {{# if(d.role_status == 1){ }}
    <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="disable">禁用</a>
    {{# } else { }}
    <a class="layui-btn layui-btn-xs" lay-event="enable">启用</a>
    {{# } }}
</script>

<!-- 状态模板 -->
<script type="text/html" id="statusTpl">
    {{# if(d.role_status == 1){ }}
    <span class="status-badge status-enabled">启用</span>
    {{# } else { }}
    <span class="status-badge status-disabled">禁用</span>
    {{# } }}
</script>

<!-- 类型模板 -->
<script type="text/html" id="typeTpl">
    {{# if(d.role_type == 1){ }}
    <span class="type-badge type-system">系统内置</span>
    {{# } else { }}
    <span class="type-badge type-custom">用户自定义</span>
    {{# } }}
</script>

<style>
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
    .type-system {
        background: #ecf5ff;
        color: #409eff;
    }
    .type-custom {
        background: #f0f0f0;
        color: #909399;
    }
</style>

<script>
    layui.use(['table', 'layer', 'form'], function() {
        var table = layui.table;
        var layer = layui.layer;
        var form = layui.form;
        
        // 渲染表格
        var tableIns = table.render({
            elem: '#roleTable',
            url: '/backend/role/list',
            page: true,
            limit: 10,
            limits: [10, 20, 50],
            cols: [[
                {type: 'numbers', title: '序号', width: 80},
                {field: 'role_name', title: '角色名称', width: 150},
                {field: 'role_code', title: '角色标识', width: 150},
                {field: 'role_type_text', title: '角色类型', width: 120, templet: '#typeTpl'},
                {field: 'data_scope_text', title: '数据范围', width: 150},
                {field: 'role_status_text', title: '状态', width: 100, templet: '#statusTpl'},
                {field: 'role_sort', title: '排序', width: 80},
                {field: 'role_remark', title: '备注', width: 200},
                {field: 'created_at', title: '创建时间', width: 170},
                {title: '操作', width: 200, templet: '#toolbar'},
            ]],
        });
        
        // 监听行工具事件
        table.on('tool(roleTable)', function(obj) {
            var data = obj.data;
            var layEvent = obj.event;
            
            if (layEvent === 'edit') {
                editRole(data.id);
            } else if (layEvent === 'permission') {
                configurePermission(data.id);
            } else if (layEvent === 'menu') {
                configureMenu(data.id);
            } else if (layEvent === 'del') {
                deleteRole(data.id, data.role_name);
            } else if (layEvent === 'disable') {
                changeStatus(data.id, 0);
            } else if (layEvent === 'enable') {
                changeStatus(data.id, 1);
            }
        });
        
        // 添加角色
        function addRole() {
            layer.open({
                type: 2,
                title: '添加角色',
                area: ['600px', '500px'],
                content: '/backend/role/add',
                end: function() {
                    tableIns.reload();
                }
            });
        }
        
        // 编辑角色
        function editRole(id) {
            layer.open({
                type: 2,
                title: '编辑角色',
                area: ['600px', '500px'],
                content: '/backend/role/edit?id=' + id,
                end: function() {
                    tableIns.reload();
                }
            });
        }
        
        // 删除角色
        function deleteRole(id, name) {
            layer.confirm('确定要删除角色「' + name + '」吗？', {
                icon: 3,
                title: '提示'
            }, function(index) {
                layer.close(index);
                layui.$.ajax({
                    url: '/backend/role/del',
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
            layer.confirm('确定要' + statusText + '该角色吗？', {
                icon: 3,
                title: '提示'
            }, function(index) {
                layer.close(index);
                layui.$.ajax({
                    url: '/backend/role/status',
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
        
        // 配置权限
        function configurePermission(id) {
            layer.open({
                type: 2,
                title: '配置权限',
                area: ['700px', '500px'],
                content: '/backend/role/permission?id=' + id,
                end: function() {
                    tableIns.reload();
                }
            });
        }
        
        // 配置菜单
        function configureMenu(id) {
            layer.open({
                type: 2,
                title: '配置菜单',
                area: ['600px', '500px'],
                content: '/backend/role/menu?id=' + id,
                end: function() {
                    tableIns.reload();
                }
            });
        }
        
        // 搜索
        function search() {
            tableIns.reload({
                where: {
                    role_name: document.getElementById('role_name').value,
                    role_code: document.getElementById('role_code').value,
                    role_status: document.getElementById('role_status').value,
                },
                page: {
                    curr: 1
                }
            });
        }
        
        // 重置搜索
        function resetSearch() {
            document.getElementById('role_name').value = '';
            document.getElementById('role_code').value = '';
            document.getElementById('role_status').value = '';
            search();
        }
    });
</script>