<div class="content-header">
    <div class="content-title">书签管理</div>
    <div class="content-actions">
        <button class="layui-btn" onclick="addBookMark()">
            <i class="layui-icon layui-icon-add"></i> 添加书签
        </button>
    </div>
</div>

<div class="search-form">
    <div class="search-item">
        <span class="search-label">短标题：</span>
        <input type="text" id="short_title" placeholder="请输入短标题" class="layui-input" style="width: 150px; display: inline;">
    </div>
    <div class="search-item">
        <span class="search-label">长标题：</span>
        <input type="text" id="book_title" placeholder="请输入长标题" class="layui-input" style="width: 200px; display: inline;">
    </div>
    <div class="search-item">
        <span class="search-label">所属分类：</span>
        <select id="category_id" class="layui-input" style="width: 150px; display: inline;">
            <option value="0">全部</option>
            <option value="0">默认书签栏</option>
        </select>
    </div>
    <div class="search-item">
        <span class="search-label">状态：</span>
        <select id="status" class="layui-input" style="width: 120px; display: inline;">
            <option value="">全部</option>
            <option value="0">隐藏</option>
            <option value="1">正常</option>
            <option value="2">失效</option>
        </select>
    </div>
    <button class="layui-btn" onclick="search()">搜索</button>
    <button class="layui-btn layui-btn-primary" onclick="resetSearch()">重置</button>
</div>

<table id="bookmarkTable" lay-filter="bookmarkTable"></table>

<!-- 操作栏模板 -->
<script type="text/html" id="toolbar">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>
    {{# if(d.status == 1){ }}
    <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="hide">隐藏</a>
    {{# } else { }}
    <a class="layui-btn layui-btn-xs" lay-event="show">显示</a>
    {{# } }}
</script>

<!-- 状态模板 -->
<script type="text/html" id="statusTpl">
    {{# if(d.status == 0){ }}
    <span class="status-badge status-hidden">隐藏</span>
    {{# } else if(d.status == 1){ }}
    <span class="status-badge status-normal">正常</span>
    {{# } else { }}
    <span class="status-badge status-invalid">失效</span>
    {{# } }}
</script>

<!-- 加粗模板 -->
<script type="text/html" id="boldTpl">
    {{# if(d.is_bold == 1){ }}
    <span class="type-badge type-bold">是</span>
    {{# } else { }}
    <span class="type-badge type-normal">否</span>
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
    .status-hidden {
        background: #f5f5f5;
        color: #909399;
    }
    .status-normal {
        background: #f0f9eb;
        color: #67c23a;
    }
    .status-invalid {
        background: #fef0f0;
        color: #f56c6c;
    }
    .type-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
    .type-bold {
        background: #ecf5ff;
        color: #409eff;
    }
    .type-normal {
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
            elem: '#bookmarkTable',
            url: '/backend/bookmark/list',
            page: true,
            limit: 10,
            limits: [10, 20, 50],
            where: {},
            cols: [[
                {type: 'numbers', title: '序号', width: 80},
                {field: 'short_title', title: '短标题', width: 100},
                {field: 'book_title', title: '长标题', width: 200},
                {field: 'book_url', title: '链接地址', width: 300},
                {field: 'category_name', title: '所属分类', width: 120},
                {field: 'status_text', title: '状态', width: 80, templet: '#statusTpl'},
                {field: 'is_bold_text', title: '加粗显示', width: 80, templet: '#boldTpl'},
                {field: 'sort_order', title: '排序', width: 80},
                {field: 'created_at', title: '创建时间', width: 170},
                {title: '操作', width: 200, templet: '#toolbar', align: 'center'},
            ]],
        });
        
        // 监听行工具事件
        table.on('tool(bookmarkTable)', function(obj) {
            var data = obj.data;
            var layEvent = obj.event;
            
            if (layEvent === 'edit') {
                editBookMark(data.id);
            } else if (layEvent === 'del') {
                deleteBookMark(data.id, data.short_title);
            } else if (layEvent === 'hide') {
                changeStatus(data.id, 0);
            } else if (layEvent === 'show') {
                changeStatus(data.id, 1);
            }
        });
        
        // 添加书签
        window.addBookMark = function() {
            layer.open({
                type: 2,
                title: '添加书签',
                area: ['600px', '550px'],
                content: '/backend/bookmark/add',
                end: function() {
                    tableIns.reload();
                }
            });
        }
        
        // 编辑书签
        window.editBookMark = function(id) {
            layer.open({
                type: 2,
                title: '编辑书签',
                area: ['600px', '550px'],
                content: '/backend/bookmark/edit?id=' + id,
                end: function() {
                    tableIns.reload();
                }
            });
        }
        
        // 删除书签
        window.deleteBookMark = function(id, name) {
            layer.confirm('确定要删除书签「' + name + '」吗？', {
                icon: 3,
                title: '提示'
            }, function(index) {
                layer.close(index);
                layui.$.ajax({
                    url: '/backend/bookmark/del',
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
            var statusText = status === 1 ? '显示' : '隐藏';
            layer.confirm('确定要' + statusText + '该书签吗？', {
                icon: 3,
                title: '提示'
            }, function(index) {
                layer.close(index);
                layui.$.ajax({
                    url: '/backend/bookmark/status',
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
                    short_title: document.getElementById('short_title').value,
                    book_title: document.getElementById('book_title').value,
                    category_id: document.getElementById('category_id').value,
                    status: document.getElementById('status').value,
                },
                page: {
                    curr: 1
                }
            });
        }
        
        // 重置搜索
        window.resetSearch = function() {
            document.getElementById('short_title').value = '';
            document.getElementById('book_title').value = '';
            document.getElementById('category_id').value = '0';
            document.getElementById('status').value = '';
            window.search();
        }
    });
</script>