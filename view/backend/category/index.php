<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>分类管理</title>
    <link rel="stylesheet" href="/static/backend/layui/css/layui.css">
    <style>
        body {
            padding: 20px;
            background: #f5f7fa;
        }
        .content-container {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }
        .page-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .search-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        .search-item {
            display: flex;
            align-items: center;
        }
        .search-item label {
            margin-right: 8px;
            color: #666;
            font-size: 14px;
        }
        .search-item input,
        .search-item select {
            width: 160px;
            padding: 5px 10px;
            border: 1px solid #e6e6e6;
            border-radius: 4px;
            font-size: 14px;
        }
        .search-btn {
            margin-left: auto;
        }
        .table-container {
            margin-top: 10px;
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
        .type-all {
            background: #ecf5ff;
            color: #409eff;
        }
        .type-visible {
            background: #f0f9eb;
            color: #67c23a;
        }
        .type-hidden {
            background: #f5f0ff;
            color: #9b59b6;
        }
        .level-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            background: #f5f7fa;
            color: #606266;
        }
        .category-name-display {
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="content-container">
        <div class="page-title">分类管理</div>
        
        <div class="search-bar">
            <div class="search-item">
                <label>分类名称：</label>
                <input type="text" id="category_name" placeholder="请输入分类名称">
            </div>
            <div class="search-item">
                <label>状态：</label>
                <select id="cat_status">
                    <option value="">全部</option>
                    <option value="1">显示</option>
                    <option value="0">隐藏</option>
                </select>
            </div>
            <div class="search-btn">
                <button class="layui-btn" onclick="search()">搜索</button>
                <button class="layui-btn layui-btn-primary" onclick="resetSearch()">重置</button>
            </div>
        </div>
        
        <div class="table-container">
            <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
                <button class="layui-btn" onclick="addCategory()">添加分类</button>
            </div>
            <table id="categoryTable" lay-filter="categoryTable"></table>
        </div>
    </div>
    
    <!-- 操作栏模板 -->
    <script type="text/html" id="toolbar">
        <a class="layui-btn layui-btn-xs" lay-event="addChild">添加子分类</a>
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>
        {{# if(d.cat_status == 1){ }}
        <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="hide">隐藏</a>
        {{# } else { }}
        <a class="layui-btn layui-btn-xs" lay-event="show">显示</a>
        {{# } }}
    </script>
    
    <!-- 状态模板 -->
    <script type="text/html" id="statusTpl">
        {{# if(d.cat_status == 1){ }}
        <span class="status-badge status-enabled">显示</span>
        {{# } else { }}
        <span class="status-badge status-disabled">隐藏</span>
        {{# } }}
    </script>
    
    <!-- 可见性类型模板 -->
    <script type="text/html" id="showTypeTpl">
        {{# if(d.show_type == 0){ }}
        <span class="type-badge type-all">全部可见</span>
        {{# } else if(d.show_type == 1){ }}
        <span class="type-badge type-visible">指定客户可见</span>
        {{# } else { }}
        <span class="type-badge type-hidden">指定客户不可见</span>
        {{# } }}
    </script>
    
    <!-- 级别模板 -->
    <script type="text/html" id="levelTpl">
        <span class="level-badge">{{ d.level_text }}</span>
    </script>
    
    <script src="/static/backend/layui/layui.js"></script>
    <script>
        layui.use(['table', 'layer', 'form'], function() {
            var table = layui.table;
            var layer = layui.layer;
            var form = layui.form;
            
            // 渲染表格
            var tableIns = table.render({
                elem: '#categoryTable',
                url: '/backend/category/list',
                page: false,
                where: {},
                cols: [[
                    {type: 'numbers', title: '序号', width: 80},
                    {field: 'category_name_display', title: '分类名称', width: 350, templet: function(d) {
                        return '<span class="category-name-display">' + d.category_name_display + '</span>';
                    }},
                    {field: 'level_text', title: '级别', width: 100, templet: '#levelTpl'},
                    {field: 'show_type_text', title: '可见性', width: 120, templet: '#showTypeTpl'},
                    {field: 'cat_status_text', title: '状态', width: 80, templet: '#statusTpl'},
                    {field: 'sort_order', title: '排序', width: 80},
                    {field: 'description', title: '描述', width: 200},
                    {field: 'created_at', title: '创建时间', width: 170},
                    {title: '操作', width: 200, templet: '#toolbar'},
                ]],
            });
            
            // 监听行工具事件
            table.on('tool(categoryTable)', function(obj) {
                var data = obj.data;
                var layEvent = obj.event;
                
                if (layEvent === 'addChild') {
                    addCategory(data.id);
                } else if (layEvent === 'edit') {
                    editCategory(data.id);
                } else if (layEvent === 'del') {
                    deleteCategory(data.id, data.category_name);
                } else if (layEvent === 'hide') {
                    changeStatus(data.id, 0);
                } else if (layEvent === 'show') {
                    changeStatus(data.id, 1);
                }
            });
            
            // 添加分类
            window.addCategory = function(parentId) {
                var url = '/backend/category/add';
                if (parentId) {
                    url += '?parent_id=' + parentId;
                }
                layer.open({
                    type: 2,
                    title: parentId ? '添加子分类' : '添加分类',
                    area: ['550px', '500px'],
                    content: url,
                    end: function() {
                        tableIns.reload();
                    }
                });
            }
            
            // 编辑分类
            window.editCategory = function(id) {
                layer.open({
                    type: 2,
                    title: '编辑分类',
                    area: ['550px', '500px'],
                    content: '/backend/category/edit?id=' + id,
                    end: function() {
                        tableIns.reload();
                    }
                });
            }
            
            // 删除分类
            window.deleteCategory = function(id, name) {
                layer.confirm('确定要删除分类「' + name + '」吗？', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    layui.$.ajax({
                        url: '/backend/category/del',
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
                var statusText = status === 1 ? '显示' : '隐藏';
                layer.confirm('确定要' + statusText + '该分类吗？', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    layui.$.ajax({
                        url: '/backend/category/status',
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
                        category_name: document.getElementById('category_name').value,
                        cat_status: document.getElementById('cat_status').value,
                    },
                });
            }
            
            // 重置搜索
            window.resetSearch = function() {
                document.getElementById('category_name').value = '';
                document.getElementById('cat_status').value = '';
                tableIns.reload({
                    where: {},
                });
            }
        });
    </script>
</body>
</html>
