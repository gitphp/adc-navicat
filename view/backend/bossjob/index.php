<div class="content-header">
    <h1 class="content-title">招聘职位管理</h1>
    <div class="content-actions">
        <button class="layui-btn layui-btn-normal" onclick="addJob()">
            <i class="layui-icon layui-icon-add-circle"></i> 添加职位
        </button>
    </div>
</div>

<!-- 搜索表单 -->
<form class="layui-form" id="searchForm" lay-filter="searchForm">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">职位名称</label>
            <div class="layui-input-inline" style="width: 200px;">
                <input type="text" name="job_title" placeholder="请输入职位名称" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">所属部门</label>
            <div class="layui-input-inline" style="width: 150px;">
                <input type="text" name="department" placeholder="请输入部门名称" class="layui-input">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline" style="width: 150px;">
                <select name="job_status" lay-search>
                    <option value="">全部</option>
                    <option value="1">待发布</option>
                    <option value="2">发布中</option>
                    <option value="3">已关闭</option>
                </select>
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">急聘</label>
            <div class="layui-input-inline" style="width: 100px;">
                <select name="is_hot">
                    <option value="">全部</option>
                    <option value="1">急聘</option>
                    <option value="0">普通</option>
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
<table id="jobTable" lay-filter="jobTable"></table>

<!-- 模板 -->
<script type="text/html" id="hotTpl">
    {{# if(d.is_hot == 1){ }}
    <span class="layui-badge layui-bg-red">急聘</span>
    {{# } }}
</script>

<script type="text/html" id="statusTpl">
    <button class="layui-btn layui-btn-xs {{d.job_status_class}}">{{d.job_status_text}}</button>
</script>

<script type="text/html" id="actionTpl">
    {{# if(d.job_status == 1){ }}
    <button class="layui-btn layui-btn-xs" onclick="publishJob({{d.id}})">发布</button>
    {{# } }}
    {{# if(d.job_status == 2){ }}
    <button class="layui-btn layui-btn-danger layui-btn-xs" onclick="closeJob({{d.id}})">关闭</button>
    {{# } }}
    <button class="layui-btn layui-btn-normal layui-btn-xs" onclick="editJob({{d.id}})">编辑</button>
    <button class="layui-btn layui-btn-danger layui-btn-xs" onclick="delJob({{d.id}})">删除</button>
</script>

<script>
layui.use(['table', 'form', 'layer'], function() {
    var table = layui.table;
    var form = layui.form;
    var layer = layui.layer;
    
    // 渲染表格
    var tableIns = table.render({
        elem: '#jobTable',
        url: '/backend/bossjob/list',
        method: 'GET',
        page: true,
        limit: 20,
        limits: [10, 20, 50, 100],
        where: {},
        cols: [[
            {field: 'id', width: 80, title: 'ID', sort: true},
            {field: 'job_title', minWidth: 140, title: '职位名称'},
            {field: 'department', width: 120, title: '所属部门'},
            {field: 'workplace', width: 120, title: '工作地点'},
            {field: 'experience', width: 80, title: '经验要求'},
            {field: 'education', width: 80, title: '学历要求'},
            {field: 'salary_range', width: 100, title: '薪资范围'},
            {field: 'is_hot_text', width: 60, title: '急聘', templet: '#hotTpl'},
            {field: 'job_status_text', width: 80, title: '状态', templet: '#statusTpl'},
            {field: 'expire_at', width: 160, title: '过期时间'},
            {field: 'view_count', width: 80, title: '浏览量', sort: true},
            {field: 'created_at', width: 160, title: '创建时间'},
            {fixed: 'right', width: 250, title: '操作', templet: '#actionTpl'},
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
    
    // 添加职位
    window.addJob = function() {
        layer.open({
            type: 2,
            title: '添加招聘职位',
            area: ['800px', '700px'],
            content: '/backend/bossjob/add',
            end: function() {
                tableIns.reload();
            }
        });
    };
    
    // 编辑职位
    window.editJob = function(id) {
        layer.open({
            type: 2,
            title: '编辑招聘职位',
            area: ['800px', '700px'],
            content: '/backend/bossjob/edit?id=' + id,
            end: function() {
                tableIns.reload();
            }
        });
    };
    
    // 删除职位
    window.delJob = function(id) {
        layer.confirm('确定要删除这个职位吗？', {icon: 3}, function(index) {
            layui.$.ajax({
                url: '/backend/bossjob/del',
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
    
    // 发布职位
    window.publishJob = function(id) {
        layer.confirm('确定要发布这个职位吗？', {icon: 3}, function(index) {
            layui.$.ajax({
                url: '/backend/bossjob/publish',
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
                    layer.msg('操作失败', {icon: 5});
                }
            });
            layer.close(index);
        });
    };
    
    // 关闭职位
    window.closeJob = function(id) {
        layer.confirm('确定要关闭这个职位吗？', {icon: 3}, function(index) {
            layui.$.ajax({
                url: '/backend/bossjob/close',
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
                    layer.msg('操作失败', {icon: 5});
                }
            });
            layer.close(index);
        });
    };
    
    // 切换急聘
    window.toggleHot = function(id, isHot) {
        layui.$.ajax({
            url: '/backend/bossjob/hot',
            type: 'POST',
            data: {id: id, is_hot: isHot},
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
});
</script>