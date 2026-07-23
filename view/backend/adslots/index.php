<div class="content-header">
    <div class="content-title">广告位管理</div>
    <div class="content-actions">
        <button class="layui-btn" onclick="addAdSlot()">
            <i class="layui-icon layui-icon-add"></i> 添加广告位
        </button>
    </div>
</div>

<div class="search-area">
    <div class="search-label">广告位编码：</div>
    <input type="text" id="slot_code" placeholder="请输入编码" class="layui-input" style="width: 200px; display: inline;">
    <div class="search-label">广告位名称：</div>
    <input type="text" id="slot_name" placeholder="请输入名称" class="layui-input" style="width: 200px; display: inline;">
    <div class="search-label">状态：</div>
    <select id="slot_status" class="layui-input" style="width: 120px; display: inline;">
        <option value="">全部</option>
        <option value="1">启用</option>
        <option value="0">禁用</option>
    </select>
    <button class="layui-btn" onclick="searchData()">搜索</button>
    <button class="layui-btn layui-btn-primary" onclick="resetSearch()">重置</button>
</div>

<table id="adslotsTable" lay-filter="adslotsTable"></table>

<script type="text/html" id="statusTpl">
    {{# if(d.slot_status == 1){ }}
    <span class="status-normal">启用</span>
    {{# } else { }}
    <span class="status-disable">禁用</span>
    {{# } }}
</script>

<script type="text/html" id="systemTpl">
    {{# if(d.is_system == 1){ }}
    <span class="system-tag">系统预设</span>
    {{# } else { }}
    <span>自定义</span>
    {{# } }}
</script>

<script type="text/html" id="sizeTpl">
    {{# if(d.width > 0 && d.height > 0){ }}
    <span>{{ d.width }} × {{ d.height }}</span>
    {{# } else { }}
    <span>未设置</span>
    {{# } }}
</script>

<script type="text/html" id="barTpl">
    <button class="layui-btn layui-btn-xs" onclick="editAdSlot({{ d.id }})">编辑</button>
    {{# if(d.is_system != 1){ }}
    <button class="layui-btn layui-btn-xs layui-btn-danger" onclick="delAdSlot({{ d.id }})">删除</button>
    {{# } }}
    {{# if(d.slot_status == 1){ }}
    <button class="layui-btn layui-btn-xs layui-btn-warm" onclick="toggleStatus({{ d.id }}, 0)">禁用</button>
    {{# } else { }}
    <button class="layui-btn layui-btn-xs layui-btn-normal" onclick="toggleStatus({{ d.id }}, 1)">启用</button>
    {{# } }}
</script>

<style>
    .status-normal {
        color: #52c41a;
    }
    .status-disable {
        color: #ff4d4f;
    }
    .system-tag {
        background: #1890ff;
        color: #fff;
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
            elem: '#adslotsTable',
            url: '/backend/adslots/list',
            page: true,
            limit: 10,
            limits: [10, 20, 50, 100],
            where: {},
            cols: [[
                {field: 'id', title: 'ID', width: 80, sort: true},
                {field: 'slot_code', title: '广告位编码', width: 180},
                {field: 'slot_name', title: '广告位名称', width: 200},
                {field: 'description', title: '描述', width: 200},
                {field: 'size_text', title: '尺寸(像素)', width: 120, templet: '#sizeTpl'},
                {field: 'max_items', title: '最大数量', width: 100},
                {field: 'is_system_text', title: '类型', width: 100, templet: '#systemTpl'},
                {field: 'slot_status_text', title: '状态', width: 80, templet: '#statusTpl'},
                {field: 'created_at', title: '创建时间', width: 160},
                {title: '操作', width: 200, toolbar: '#barTpl'},
            ]],
        });
    });
    
    // 搜索数据
    window.searchData = function() {
        layui.table.reload('adslotsTable', {
            where: {
                slot_code: document.getElementById('slot_code').value,
                slot_name: document.getElementById('slot_name').value,
                slot_status: document.getElementById('slot_status').value,
            },
            page: {
                curr: 1,
            },
        });
    }
    
    // 重置搜索
    window.resetSearch = function() {
        document.getElementById('slot_code').value = '';
        document.getElementById('slot_name').value = '';
        document.getElementById('slot_status').value = '';
        window.searchData();
    }
    
    // 添加广告位
    window.addAdSlot = function() {
        layer.open({
            type: 2,
            title: '添加广告位',
            area: ['600px', '550px'],
            content: '/backend/adslots/add',
            end: function() {
                layui.table.reload('adslotsTable');
            },
        });
    }
    
    // 编辑广告位
    window.editAdSlot = function(id) {
        layer.open({
            type: 2,
            title: '编辑广告位',
            area: ['600px', '550px'],
            content: '/backend/adslots/edit?id=' + id,
            end: function() {
                layui.table.reload('adslotsTable');
            },
        });
    }
    
    // 删除广告位
    window.delAdSlot = function(id) {
        layer.confirm('确定要删除该广告位吗？', function(index) {
            layui.$.ajax({
                url: '/backend/adslots/del',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(res) {
                    if (res.code === 1) {
                        layer.msg(res.msg, {icon: 1});
                        layui.table.reload('adslotsTable');
                    } else {
                        layer.msg(res.msg, {icon: 5});
                    }
                },
            });
            layer.close(index);
        });
    }
    
    // 切换状态
    window.toggleStatus = function(id, status) {
        var text = status == 1 ? '启用' : '禁用';
        layer.confirm('确定要' + text + '该广告位吗？', function(index) {
            layui.$.ajax({
                url: '/backend/adslots/status',
                type: 'POST',
                data: {id: id, status: status},
                dataType: 'json',
                success: function(res) {
                    if (res.code === 1) {
                        layer.msg(res.msg, {icon: 1});
                        layui.table.reload('adslotsTable');
                    } else {
                        layer.msg(res.msg, {icon: 5});
                    }
                },
            });
            layer.close(index);
        });
    }
</script>