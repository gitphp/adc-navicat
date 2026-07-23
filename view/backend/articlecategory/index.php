<div class="content-header">
    <h1 class="content-title">文章分类管理</h1>
    <div class="content-actions">
        <button class="layui-btn layui-btn-normal" onclick="addCategory()">
            <i class="layui-icon layui-icon-add-circle"></i> 添加分类
        </button>
    </div>
</div>

<!-- 搜索表单 -->
<form class="layui-form" id="searchForm" lay-filter="searchForm">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">分类名称</label>
            <div class="layui-input-inline" style="width: 200px;">
                <input type="text" name="cat_name" placeholder="请输入分类名称" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline" style="width: 150px;">
                <select name="status" lay-search>
                    <option value="">全部</option>
                    <option value="1">启用</option>
                    <option value="0">禁用</option>
                </select>
            </div>
        </div>
        <div class="layui-inline">
            <button class="layui-btn" lay-submit lay-filter="search">搜索</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>

<!-- 数据表格 -->
<table class="layui-hide" id="categoryTable"></table>

<script>
    layui.use(['table', 'form', 'layer'], function() {
        var table = layui.table;
        var form = layui.form;
        var layer = layui.layer;
        
        // 渲染表格
        var tableIns = table.render({
            elem: '#categoryTable',
            url: '/backend/articlecategory/list',
            method: 'GET',
            page: true,
            limit: 20,
            limits: [10, 20, 50, 100],
            where: {},
            cols: [[
                {field: 'id', width: 80, title: 'ID', sort: true},
                {field: 'cat_name_display', minWidth: 200, title: '分类名称'},
                {field: 'cat_url', width: 150, title: 'URL别名'},
                {field: 'level_text', width: 100, title: '级别'},
                {field: 'status_text', width: 80, title: '状态', templet: '#statusTpl'},
                {field: 'cat_sort', width: 80, title: '排序', sort: true},
                {field: 'created_at', width: 160, title: '创建时间'},
                {fixed: 'right', width: 200, title: '操作', templet: '#actionTpl'},
            ]],
            done: function(res, curr, count) {
                // 表格渲染完成后的回调
            }
        });
        
        // 搜索监听
        form.on('submit(search)', function(data) {
            tableIns.reload({
                where: data.field,
                page: {curr: 1}
            });
            return false;
        });
        
        // 添加分类
        window.addCategory = function(parentId) {
            parentId = parentId || 0;
            layer.open({
                type: 2,
                title: '添加文章分类',
                area: ['600px', '450px'],
                content: '/backend/articlecategory/add?parent_id=' + parentId,
                end: function() {
                    tableIns.reload();
                }
            });
        };
        
        // 编辑分类
        window.editCategory = function(id) {
            layer.open({
                type: 2,
                title: '编辑文章分类',
                area: ['600px', '450px'],
                content: '/backend/articlecategory/edit?id=' + id,
                end: function() {
                    tableIns.reload();
                }
            });
        };
        
        // 删除分类
        window.delCategory = function(id) {
            layer.confirm('确定要删除这个分类吗？', {icon: 3}, function(index) {
                layui.$.ajax({
                    url: '/backend/articlecategory/del',
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
                layer.close(index);
            });
        };
        
        // 切换状态
        window.toggleStatus = function(id, status) {
            layui.$.ajax({
                url: '/backend/articlecategory/status',
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
        };
        
        // 添加子分类
        window.addChild = function(id) {
            addCategory(id);
        };
    });
</script>

<!-- 模板 -->
<script type="text/html" id="statusTpl">
    <button class="layui-btn layui-btn-xs {{d.status_class}}" onclick="toggleStatus({{d.id}}, {{d.status == 1 ? 0 : 1}})">
        {{d.status_text}}
    </button>
</script>

<script type="text/html" id="actionTpl">
    <button class="layui-btn layui-btn-xs" onclick="addChild({{d.id}})">
        <i class="layui-icon layui-icon-add-1"></i> 添加子分类
    </button>
    <button class="layui-btn layui-btn-normal layui-btn-xs" onclick="editCategory({{d.id}})">
        <i class="layui-icon layui-icon-edit"></i> 编辑
    </button>
    <button class="layui-btn layui-btn-danger layui-btn-xs" onclick="delCategory({{d.id}})">
        <i class="layui-icon layui-icon-delete"></i> 删除
    </button>
</script>