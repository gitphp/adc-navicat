<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文章管理</title>
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
        .stats-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            flex: 1;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
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
        .status-draft {
            background: #f5f7fa;
            color: #909399;
        }
        .status-pending {
            background: #ecf5ff;
            color: #409eff;
        }
        .status-approved {
            background: #f0f9eb;
            color: #67c23a;
        }
        .status-published {
            background: #e8f5e9;
            color: #2e7d32;
        }
        .status-offline {
            background: #fff3e0;
            color: #ff9800;
        }
        .status-rejected {
            background: #fef0f0;
            color: #f56c6c;
        }
        .status-trash {
            background: #f5f5f5;
            color: #777;
        }
        .top-badge {
            display: inline-block;
            padding: 2px 6px;
            background: #ffeb3b;
            color: #333;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .original-badge {
            display: inline-block;
            padding: 2px 6px;
            background: #e3f2fd;
            color: #1976d2;
            border-radius: 4px;
            font-size: 12px;
        }
        .title-link {
            color: #333;
            cursor: pointer;
        }
        .title-link:hover {
            color: #409eff;
        }
    </style>
</head>
<body>
    <div class="content-container">
        <div class="page-title">文章管理</div>
        
        <!-- 统计卡片 -->
        <div class="stats-row" id="statsContainer">
            <div class="stat-card">
                <div class="stat-number" id="statTotal">--</div>
                <div class="stat-label">文章总数</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="statDraft">--</div>
                <div class="stat-label">草稿</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="statPending">--</div>
                <div class="stat-label">待审核</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="statPublished">--</div>
                <div class="stat-label">已发布</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="statOffline">--</div>
                <div class="stat-label">已下线</div>
            </div>
        </div>
        
        <div class="search-bar">
            <div class="search-item">
                <label>文章标题：</label>
                <input type="text" id="title" placeholder="请输入文章标题">
            </div>
            <div class="search-item">
                <label>分类：</label>
                <select id="category_id">
                    <option value="">全部</option>
                </select>
            </div>
            <div class="search-item">
                <label>状态：</label>
                <select id="art_status">
                    <option value="">全部</option>
                    <option value="1">草稿</option>
                    <option value="2">待审核</option>
                    <option value="3">审核通过</option>
                    <option value="4">已发布</option>
                    <option value="5">已下线</option>
                    <option value="6">审核驳回</option>
                    <option value="7">回收站</option>
                </select>
            </div>
            <div class="search-item">
                <label>置顶：</label>
                <select id="is_top">
                    <option value="">全部</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
                </select>
            </div>
            <div class="search-item">
                <label>作者：</label>
                <input type="text" id="author_name" placeholder="请输入作者姓名">
            </div>
            <div class="search-item">
                <label>开始日期：</label>
                <input type="date" id="start_date">
            </div>
            <div class="search-item">
                <label>结束日期：</label>
                <input type="date" id="end_date">
            </div>
            <div class="search-btn">
                <button class="layui-btn" onclick="search()">搜索</button>
                <button class="layui-btn layui-btn-primary" onclick="resetSearch()">重置</button>
            </div>
        </div>
        
        <div class="table-container">
            <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
                <button class="layui-btn" onclick="addArticle()">添加文章</button>
            </div>
            <table id="articleTable" lay-filter="articleTable"></table>
        </div>
    </div>
    
    <!-- 操作栏模板 -->
    <script type="text/html" id="toolbar">
        {{# if(d.is_top == 1){ }}
        <span class="top-badge">置顶</span>
        {{# } }}
        {{# if(d.is_original == 1){ }}
        <span class="original-badge">原创</span>
        {{# } }}
        <div style="display: flex; gap: 5px;">
            <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
            {{# if(d.art_status == 1){ }}
            <a class="layui-btn layui-btn-xs" lay-event="submit">提交审核</a>
            <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="publish">直接发布</a>
            {{# } }}
            {{# if(d.art_status == 2){ }}
            <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="review">审核</a>
            {{# } }}
            {{# if(d.art_status == 4){ }}
            <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="offline">下线</a>
            {{# } }}
            {{# if(d.art_status == 5){ }}
            <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="publish">重新发布</a>
            {{# } }}
            <a class="layui-btn layui-btn-xs" lay-event="top">{{ d.is_top == 1 ? '取消置顶' : '置顶' }}</a>
            <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>
        </div>
    </script>
    
    <!-- 状态模板 -->
    <script type="text/html" id="statusTpl">
        {{# if(d.art_status == 1){ }}
        <span class="status-badge status-draft">草稿</span>
        {{# } else if(d.art_status == 2){ }}
        <span class="status-badge status-pending">待审核</span>
        {{# } else if(d.art_status == 3){ }}
        <span class="status-badge status-approved">审核通过</span>
        {{# } else if(d.art_status == 4){ }}
        <span class="status-badge status-published">已发布</span>
        {{# } else if(d.art_status == 5){ }}
        <span class="status-badge status-offline">已下线</span>
        {{# } else if(d.art_status == 6){ }}
        <span class="status-badge status-rejected">审核驳回</span>
        {{# } else { }}
        <span class="status-badge status-trash">回收站</span>
        {{# } }}
    </script>
    
    <!-- 审核弹窗 -->
    <div id="reviewModal" style="display: none;">
        <form class="layui-form" action="" method="post">
            <input type="hidden" name="article_id" id="reviewArticleId">
            <div class="layui-form-item">
                <label class="layui-form-label">审核结果</label>
                <div class="layui-input-block">
                    <input type="radio" name="review_result" value="1" title="通过" checked>
                    <input type="radio" name="review_result" value="0" title="驳回">
                </div>
            </div>
            <div class="layui-form-item" id="rejectReasonDiv" style="display: none;">
                <label class="layui-form-label">驳回原因</label>
                <div class="layui-input-block">
                    <textarea name="reject_reason" placeholder="请输入驳回原因" class="layui-textarea"></textarea>
                </div>
            </div>
        </form>
    </div>
    
    <script src="/static/backend/layui/layui.js"></script>
    <script>
        layui.use(['table', 'layer', 'form'], function() {
            var table = layui.table;
            var layer = layui.layer;
            var form = layui.form;
            
            // 渲染表格
            var tableIns = table.render({
                elem: '#articleTable',
                url: '/backend/article/list',
                page: true,
                limit: 10,
                limits: [10, 20, 50],
                cols: [[
                    {type: 'numbers', title: '序号', width: 80},
                    {field: 'title', title: '文章标题', width: 250, templet: function(d) {
                        var html = '<span class="title-link" onclick="previewArticle(' + d.id + ')">' + d.title + '</span>';
                        if (d.subtitle) {
                            html += '<br><span style="font-size: 12px; color: #999;">' + d.subtitle + '</span>';
                        }
                        return html;
                    }},
                    {field: 'category_name', title: '分类', width: 100},
                    {field: 'author_name', title: '作者', width: 80},
                    {field: 'source', title: '来源', width: 80},
                    {field: 'art_status_text', title: '状态', width: 100, templet: '#statusTpl'},
                    {field: 'is_top_text', title: '置顶', width: 60},
                    {field: 'view_count', title: '浏览', width: 70},
                    {field: 'like_count', title: '点赞', width: 70},
                    {field: 'comment_count', title: '评论', width: 70},
                    {field: 'created_at', title: '创建时间', width: 170},
                    {title: '操作', width: 280, templet: '#toolbar'},
                ]],
            });
            
            // 监听行工具事件
            table.on('tool(articleTable)', function(obj) {
                var data = obj.data;
                var layEvent = obj.event;
                
                if (layEvent === 'edit') {
                    editArticle(data.id);
                } else if (layEvent === 'submit') {
                    submitForReview(data.id, data.title);
                } else if (layEvent === 'publish') {
                    publishArticle(data.id, data.title);
                } else if (layEvent === 'review') {
                    reviewArticle(data.id, data.title);
                } else if (layEvent === 'offline') {
                    offlineArticle(data.id, data.title);
                } else if (layEvent === 'top') {
                    toggleTop(data.id, data.is_top);
                } else if (layEvent === 'del') {
                    deleteArticle(data.id, data.title);
                }
            });
            
            // 获取统计数据
            function loadStats() {
                layui.$.ajax({
                    url: '/backend/article/stats',
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 0) {
                            document.getElementById('statTotal').textContent = res.data.total;
                            document.getElementById('statDraft').textContent = res.data.draft;
                            document.getElementById('statPending').textContent = res.data.pending_review;
                            document.getElementById('statPublished').textContent = res.data.published;
                            document.getElementById('statOffline').textContent = res.data.offline;
                        }
                    }
                });
            }
            
            // 获取分类下拉
            function loadCategories() {
                layui.$.ajax({
                    url: '/backend/category/tree',
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 0) {
                            var select = document.getElementById('category_id');
                            renderCategoryOptions(res.data, 0, select);
                            form.render('select');
                        }
                    }
                });
            }
            
            // 递归渲染分类选项
            function renderCategoryOptions(categories, level, select) {
                categories.forEach(function(category) {
                    var prefix = '└── '.repeat(level);
                    var option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = prefix + category.category_name;
                    select.appendChild(option);
                    
                    if (category.children && category.children.length > 0) {
                        renderCategoryOptions(category.children, level + 1, select);
                    }
                });
            }
            
            // 添加文章
            function addArticle() {
                layer.open({
                    type: 2,
                    title: '添加文章',
                    area: ['900px', '700px'],
                    content: '/backend/article/add',
                    end: function() {
                        tableIns.reload();
                        loadStats();
                    }
                });
            }
            
            // 编辑文章
            function editArticle(id) {
                layer.open({
                    type: 2,
                    title: '编辑文章',
                    area: ['900px', '700px'],
                    content: '/backend/article/edit?id=' + id,
                    end: function() {
                        tableIns.reload();
                        loadStats();
                    }
                });
            }
            
            // 提交审核
            function submitForReview(id, title) {
                layer.confirm('确定要提交文章「' + title + '」进行审核吗？', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    layui.$.ajax({
                        url: '/backend/article/update',
                        type: 'POST',
                        data: {id: id, art_status: 2},
                        dataType: 'json',
                        success: function(res) {
                            if (res.code === 1) {
                                layer.msg('提交审核成功', {icon: 1});
                                tableIns.reload();
                                loadStats();
                            } else {
                                layer.msg(res.msg, {icon: 5});
                            }
                        },
                        error: function() {
                            layer.msg('提交失败', {icon: 5});
                        }
                    });
                });
            }
            
            // 发布文章
            function publishArticle(id, title) {
                layer.confirm('确定要发布文章「' + title + '」吗？', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    layui.$.ajax({
                        url: '/backend/article/publish',
                        type: 'POST',
                        data: {id: id},
                        dataType: 'json',
                        success: function(res) {
                            if (res.code === 1) {
                                layer.msg('发布成功', {icon: 1});
                                tableIns.reload();
                                loadStats();
                            } else {
                                layer.msg(res.msg, {icon: 5});
                            }
                        },
                        error: function() {
                            layer.msg('发布失败', {icon: 5});
                        }
                    });
                });
            }
            
            // 审核文章
            function reviewArticle(id, title) {
                document.getElementById('reviewArticleId').value = id;
                
                layer.open({
                    type: 1,
                    title: '审核文章：' + title,
                    area: ['500px', '300px'],
                    content: document.getElementById('reviewModal'),
                    btn: ['确定', '取消'],
                    success: function() {
                        form.render();
                        
                        // 监听审核结果变化
                        form.on('radio(review_result)', function(data) {
                            if (data.value == 0) {
                                document.getElementById('rejectReasonDiv').style.display = 'block';
                            } else {
                                document.getElementById('rejectReasonDiv').style.display = 'none';
                            }
                        });
                    },
                    yes: function(index) {
                        var reviewResult = form.val('reviewModal').review_result;
                        var rejectReason = form.val('reviewModal').reject_reason;
                        
                        layui.$.ajax({
                            url: '/backend/article/review',
                            type: 'POST',
                            data: {
                                id: id,
                                art_status: reviewResult == 1 ? 3 : 6,
                                reject_reason: rejectReason
                            },
                            dataType: 'json',
                            success: function(res) {
                                if (res.code === 1) {
                                    layer.msg(res.msg, {icon: 1});
                                    layer.close(index);
                                    tableIns.reload();
                                    loadStats();
                                } else {
                                    layer.msg(res.msg, {icon: 5});
                                }
                            },
                            error: function() {
                                layer.msg('审核失败', {icon: 5});
                            }
                        });
                    }
                });
            }
            
            // 下线文章
            function offlineArticle(id, title) {
                layer.confirm('确定要下线文章「' + title + '」吗？', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    layui.$.ajax({
                        url: '/backend/article/offline',
                        type: 'POST',
                        data: {id: id},
                        dataType: 'json',
                        success: function(res) {
                            if (res.code === 1) {
                                layer.msg('下线成功', {icon: 1});
                                tableIns.reload();
                                loadStats();
                            } else {
                                layer.msg(res.msg, {icon: 5});
                            }
                        },
                        error: function() {
                            layer.msg('下线失败', {icon: 5});
                        }
                    });
                });
            }
            
            // 切换置顶
            function toggleTop(id, isTop) {
                var newStatus = isTop == 1 ? 0 : 1;
                var msg = newStatus == 1 ? '置顶' : '取消置顶';
                
                layer.confirm('确定要' + msg + '该文章吗？', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    layui.$.ajax({
                        url: '/backend/article/top',
                        type: 'POST',
                        data: {id: id, is_top: newStatus},
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
            
            // 删除文章
            function deleteArticle(id, title) {
                layer.confirm('确定要删除文章「' + title + '」吗？', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    layui.$.ajax({
                        url: '/backend/article/del',
                        type: 'POST',
                        data: {id: id},
                        dataType: 'json',
                        success: function(res) {
                            if (res.code === 1) {
                                layer.msg(res.msg, {icon: 1});
                                tableIns.reload();
                                loadStats();
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
            
            // 预览文章
            function previewArticle(id) {
                layer.msg('预览功能开发中...', {icon: 6});
            }
            
            // 搜索
            function search() {
                tableIns.reload({
                    where: {
                        title: document.getElementById('title').value,
                        category_id: document.getElementById('category_id').value,
                        art_status: document.getElementById('art_status').value,
                        is_top: document.getElementById('is_top').value,
                        author_name: document.getElementById('author_name').value,
                        start_date: document.getElementById('start_date').value,
                        end_date: document.getElementById('end_date').value,
                    },
                });
            }
            
            // 重置搜索
            function resetSearch() {
                document.getElementById('title').value = '';
                document.getElementById('category_id').value = '';
                document.getElementById('art_status').value = '';
                document.getElementById('is_top').value = '';
                document.getElementById('author_name').value = '';
                document.getElementById('start_date').value = '';
                document.getElementById('end_date').value = '';
                form.render('select');
                tableIns.reload({
                    where: {},
                });
            }
            
            // 初始化
            loadStats();
            loadCategories();
        });
    </script>
</body>
</html>
