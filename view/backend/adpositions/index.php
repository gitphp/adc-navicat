<div class="content-header">
    <div class="content-title">广告管理</div>
    <div class="content-actions">
        <button class="layui-btn" onclick="addAdPosition()">
            <i class="layui-icon layui-icon-add"></i> 添加广告
        </button>
    </div>
</div>

<div class="search-area">
    <div class="search-label">广告标题：</div>
    <input type="text" id="ad_title" placeholder="请输入标题" class="layui-input" style="width: 200px; display: inline;">
    <div class="search-label">广告位编码：</div>
    <input type="text" id="position_code" placeholder="请输入编码" class="layui-input" style="width: 180px; display: inline;">
    <div class="search-label">投放平台：</div>
    <select id="platform" class="layui-input" style="width: 120px; display: inline;">
        <option value="">全部</option>
        <option value="1">全部</option>
        <option value="2">PC端</option>
        <option value="3">移动端</option>
        <option value="4">小程序</option>
    </select>
    <div class="search-label">状态：</div>
    <select id="status" class="layui-input" style="width: 120px; display: inline;">
        <option value="">全部</option>
        <option value="1">草稿</option>
        <option value="2">待审核</option>
        <option value="3">审核通过</option>
        <option value="4">投放中</option>
        <option value="5">已结束</option>
        <option value="6">已暂停</option>
        <option value="7">审核驳回</option>
        <option value="8">已下线</option>
    </select>
    <button class="layui-btn" onclick="searchData()">搜索</button>
    <button class="layui-btn layui-btn-primary" onclick="resetSearch()">重置</button>
</div>

<table id="adpositionsTable" lay-filter="adpositionsTable"></table>

<script type="text/html" id="statusTpl">
    <span class="status-tag" style="background: {{ d.status == 4 ? '#52c41a' : d.status == 2 ? '#faad14' : d.status == 7 ? '#ff4d4f' : '#d9d9d9' }}; color: {{ d.status == 4 || d.status == 2 || d.status == 7 ? '#fff' : '#666' }};">
        {{ d.status_text }}
    </span>
</script>

<script type="text/html" id="barTpl">
    <button class="layui-btn layui-btn-xs" onclick="editAdPosition({{ d.id }})">编辑</button>
    {{# if(d.status == 1){ }}
    <button class="layui-btn layui-btn-xs layui-btn-warm" onclick="submitAudit({{ d.id }})">提交审核</button>
    {{# } }}
    {{# if(d.status == 2){ }}
    <button class="layui-btn layui-btn-xs layui-btn-normal" onclick="auditPass({{ d.id }})">审核通过</button>
    <button class="layui-btn layui-btn-xs layui-btn-danger" onclick="auditReject({{ d.id }})">驳回</button>
    {{# } }}
    {{# if(d.status == 3){ }}
    <button class="layui-btn layui-btn-xs layui-btn-normal" onclick="startAd({{ d.id }})">开始投放</button>
    {{# } }}
    {{# if(d.status == 4){ }}
    <button class="layui-btn layui-btn-xs layui-btn-warm" onclick="pauseAd({{ d.id }})">暂停投放</button>
    {{# } }}
    {{# if(d.status == 6){ }}
    <button class="layui-btn layui-btn-xs layui-btn-normal" onclick="startAd({{ d.id }})">继续投放</button>
    {{# } }}
    {{# if(d.status == 4 || d.status == 6){ }}
    <button class="layui-btn layui-btn-xs layui-btn-danger" onclick="offlineAd({{ d.id }})">下线</button>
    {{# } }}
    <button class="layui-btn layui-btn-xs layui-btn-danger" onclick="delAdPosition({{ d.id }})">删除</button>
</script>

<style>
    .status-tag {
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
</style>

<script>
    layui.use(['table', 'layer'], function() {
        var table = layui.table;
        var layer = layui.layer;
        
        // 初始化表格
        table.render({
            elem: '#adpositionsTable',
            url: '/backend/adpositions/list',
            page: true,
            limit: 10,
            limits: [10, 20, 50, 100],
            where: {},
            cols: [[
                {field: 'id', title: 'ID', width: 80, sort: true},
                {field: 'ad_title', title: '广告标题', minWidth: 160},
                {field: 'position_code', title: '广告位编码', width: 150},
                {field: 'platform_text', title: '投放平台', width: 100},
                {field: 'cost_type_text', title: '计费方式', width: 80},
                {field: 'start_time', title: '开始时间', width: 160},
                {field: 'end_time', title: '结束时间', width: 160},
                {field: 'sort', title: '排序', width: 60},
                {field: 'impression_count', title: '展示量', width: 100},
                {field: 'click_count', title: '点击量', width: 100},
                {field: 'click_rate', title: '点击率', width: 100},
                {field: 'status_text', title: '状态', width: 100, templet: '#statusTpl'},
                {title: '操作', width: 350, templet: '#barTpl', fixed: 'right'},
            ]],
        });
    });
    
    // 搜索数据
    window.searchData = function() {
        layui.table.reload('adpositionsTable', {
            where: {
                ad_title: document.getElementById('ad_title').value,
                position_code: document.getElementById('position_code').value,
                platform: document.getElementById('platform').value,
                status: document.getElementById('status').value,
            },
            page: {
                curr: 1,
            },
        });
    }
    
    // 重置搜索
    window.resetSearch = function() {
        document.getElementById('ad_title').value = '';
        document.getElementById('position_code').value = '';
        document.getElementById('platform').value = '';
        document.getElementById('status').value = '';
        window.searchData();
    }
    
    // 添加广告
    window.addAdPosition = function() {
        layer.open({
            type: 2,
            title: '添加广告',
            area: ['800px', '700px'],
            content: '/backend/adpositions/add',
            end: function() {
                layui.table.reload('adpositionsTable');
            },
        });
    }
    
    // 编辑广告
    window.editAdPosition = function(id) {
        layer.open({
            type: 2,
            title: '编辑广告',
            area: ['800px', '700px'],
            content: '/backend/adpositions/edit?id=' + id,
            end: function() {
                layui.table.reload('adpositionsTable');
            },
        });
    }
    
    // 删除广告
    window.delAdPosition = function(id) {
        layer.confirm('确定要删除该广告吗？', function(index) {
            layui.$.ajax({
                url: '/backend/adpositions/del',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(res) {
                    if (res.code === 1) {
                        layer.msg(res.msg, {icon: 1});
                        layui.table.reload('adpositionsTable');
                    } else {
                        layer.msg(res.msg, {icon: 5});
                    }
                },
            });
            layer.close(index);
        });
    }
    
    // 提交审核
    window.submitAudit = function(id) {
        layer.confirm('确定要提交审核吗？', function(index) {
            layui.$.ajax({
                url: '/backend/adpositions/submitAudit',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(res) {
                    if (res.code === 1) {
                        layer.msg(res.msg, {icon: 1});
                        layui.table.reload('adpositionsTable');
                    } else {
                        layer.msg(res.msg, {icon: 5});
                    }
                },
            });
            layer.close(index);
        });
    }
    
    // 审核通过
    window.auditPass = function(id) {
        layer.confirm('确定审核通过吗？', function(index) {
            layui.$.ajax({
                url: '/backend/adpositions/auditPass',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(res) {
                    if (res.code === 1) {
                        layer.msg(res.msg, {icon: 1});
                        layui.table.reload('adpositionsTable');
                    } else {
                        layer.msg(res.msg, {icon: 5});
                    }
                },
            });
            layer.close(index);
        });
    }
    
    // 审核驳回
    window.auditReject = function(id) {
        layer.prompt({
            formType: 2,
            value: '',
            title: '请输入驳回原因',
            area: ['400px', '150px'],
        }, function(reject_reason, index) {
            layui.$.ajax({
                url: '/backend/adpositions/auditReject',
                type: 'POST',
                data: {id: id, reject_reason: reject_reason},
                dataType: 'json',
                success: function(res) {
                    if (res.code === 1) {
                        layer.msg(res.msg, {icon: 1});
                        layui.table.reload('adpositionsTable');
                    } else {
                        layer.msg(res.msg, {icon: 5});
                    }
                },
            });
            layer.close(index);
        });
    }
    
    // 开始投放
    window.startAd = function(id) {
        layer.confirm('确定要开始投放吗？', function(index) {
            layui.$.ajax({
                url: '/backend/adpositions/start',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(res) {
                    if (res.code === 1) {
                        layer.msg(res.msg, {icon: 1});
                        layui.table.reload('adpositionsTable');
                    } else {
                        layer.msg(res.msg, {icon: 5});
                    }
                },
            });
            layer.close(index);
        });
    }
    
    // 暂停投放
    window.pauseAd = function(id) {
        layer.confirm('确定要暂停投放吗？', function(index) {
            layui.$.ajax({
                url: '/backend/adpositions/pause',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(res) {
                    if (res.code === 1) {
                        layer.msg(res.msg, {icon: 1});
                        layui.table.reload('adpositionsTable');
                    } else {
                        layer.msg(res.msg, {icon: 5});
                    }
                },
            });
            layer.close(index);
        });
    }
    
    // 下线广告
    window.offlineAd = function(id) {
        layer.confirm('确定要下线该广告吗？', function(index) {
            layui.$.ajax({
                url: '/backend/adpositions/offline',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(res) {
                    if (res.code === 1) {
                        layer.msg(res.msg, {icon: 1});
                        layui.table.reload('adpositionsTable');
                    } else {
                        layer.msg(res.msg, {icon: 5});
                    }
                },
            });
            layer.close(index);
        });
    }
</script>