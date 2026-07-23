<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>查看留言</title>
    <link rel="stylesheet" href="/static/backend/layui/css/layui.css">
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #f0f2f5;
        }
        .detail-container {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }
        .detail-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #e0e0e0;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .detail-label {
            display: inline-block;
            width: 100px;
            text-align: right;
            margin-right: 10px;
            color: #666;
            font-weight: 500;
            vertical-align: top;
        }
        .detail-value {
            display: inline-block;
            max-width: calc(100% - 120px);
            word-break: break-all;
        }
        .title-row {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #1890ff;
        }
        .title-text {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        .status-tag {
            margin-left: 10px;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .status-pending {
            background: #fff7e6;
            color: #fa8c16;
        }
        .status-handled {
            background: #f6ffed;
            color: #52c41a;
        }
        .reply-section {
            margin-top: 20px;
            padding: 15px;
            background: #fafafa;
            border-radius: 8px;
        }
        .reply-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .reply-content {
            color: #666;
            line-height: 1.8;
        }
        .form-actions {
            margin-top: 20px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="detail-container">
        <div class="title-row">
            <span class="title-text"><?= htmlspecialchars($feedback->fb_title) ?></span>
            <span class="status-tag <?= $feedback->fb_status == 1 ? 'status-handled' : 'status-pending' ?>">
                <?= $feedback->fb_status_text ?>
            </span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">联系人姓名</span>
            <span class="detail-value"><?= htmlspecialchars($feedback->fb_name) ?></span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">联系电话</span>
            <span class="detail-value"><?= htmlspecialchars($feedback->fb_phone) ?: '-' ?></span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">邮箱</span>
            <span class="detail-value"><?= htmlspecialchars($feedback->fb_email) ?: '-' ?></span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">公司名称</span>
            <span class="detail-value"><?= htmlspecialchars($feedback->fb_company) ?: '-' ?></span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">留言内容</span>
            <span class="detail-value"><?= nl2br(htmlspecialchars($feedback->fb_content)) ?></span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">IP地址</span>
            <span class="detail-value"><?= htmlspecialchars($feedback->ip) ?></span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">留言时间</span>
            <span class="detail-value"><?= $feedback->created_at ?></span>
        </div>
        
        <?php if (!empty($feedback->reply_content)): ?>
        <div class="reply-section">
            <div class="reply-title">回复内容</div>
            <div class="reply-content"><?= nl2br(htmlspecialchars($feedback->reply_content)) ?></div>
            <div style="margin-top: 10px; color: #999; font-size: 12px;">
                回复时间：<?= $feedback->replied_at ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="form-actions">
            <?php if ($feedback->fb_status == 0): ?>
            <button class="layui-btn" onclick="replyFeedback(<?= $feedback->id ?>)">回复留言</button>
            <?php endif; ?>
            <button class="layui-btn layui-btn-primary" onclick="parent.layer.closeAll()">关闭</button>
        </div>
    </div>
    
    <script src="/static/backend/layui/layui.js"></script>
    <script>
        // 回复留言
        function replyFeedback(id) {
            parent.layer.closeAll();
            parent.layer.open({
                type: 2,
                title: '回复留言',
                area: ['700px', '550px'],
                content: '/backend/feedbacks/reply?id=' + id,
                end: function() {
                    // 刷新父页面表格
                    parent.layui.table.reload('feedbacksTable');
                },
            });
        }
    </script>
</body>
</html>