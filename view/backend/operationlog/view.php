<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>日志详情</title>
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
            width: 120px;
            text-align: right;
            margin-right: 10px;
            color: #666;
            font-weight: 500;
            vertical-align: top;
        }
        .detail-value {
            display: inline-block;
            max-width: calc(100% - 140px);
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
        .status-success {
            background: #f6ffed;
            color: #52c41a;
        }
        .status-fail {
            background: #fff2f0;
            color: #ff4d4f;
        }
        .json-section {
            margin-top: 10px;
            padding: 10px;
            background: #fafafa;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            max-height: 200px;
            overflow-y: auto;
            word-break: break-all;
        }
        .form-actions {
            margin-top: 20px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }
        .error-msg {
            color: #ff4d4f;
        }
    </style>
</head>
<body>
    <div class="detail-container">
        <div class="title-row">
            <span class="title-text">日志详情</span>
            <span class="status-tag <?= $log->operator_status == 1 ? 'status-success' : 'status-fail' ?>">
                <?= $log->operator_status_text ?>
            </span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">日志ID</span>
            <span class="detail-value"><?= $log->id ?></span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">操作人</span>
            <span class="detail-value"><?= htmlspecialchars($log->operator_name) ?></span>
            <span style="color: #999;">(ID: <?= $log->operator_id ?>)</span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">业务模块</span>
            <span class="detail-value"><?= htmlspecialchars($log->biz_type_text) ?></span>
            <span style="color: #999;">(<?= htmlspecialchars($log->biz_type) ?>)</span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">活动类型</span>
            <span class="detail-value"><?= htmlspecialchars($log->activity_type) ?></span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">操作类型</span>
            <span class="detail-value"><?= htmlspecialchars($log->action_text) ?></span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">操作对象</span>
            <span class="detail-value"><?= htmlspecialchars($log->biz_label) ?></span>
            <span style="color: #999;">(ID: <?= $log->biz_id ?>)</span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">客户端IP</span>
            <span class="detail-value"><?= htmlspecialchars($log->client_ip) ?></span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">User-Agent</span>
            <span class="detail-value"><?= htmlspecialchars($log->user_agent) ?></span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">请求URL</span>
            <span class="detail-value"><?= htmlspecialchars($log->request_url) ?></span>
        </div>
        
        <div class="detail-item">
            <span class="detail-label">调用方法</span>
            <span class="detail-value"><?= htmlspecialchars($log->method_fun) ?></span>
        </div>
        
        <?php if (!empty($log->old_value)): ?>
        <div class="detail-item">
            <span class="detail-label">修改前数据</span>
            <div class="detail-value">
                <div class="json-section"><?= htmlspecialchars(json_encode($log->old_value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($log->new_value)): ?>
        <div class="detail-item">
            <span class="detail-label">修改后数据</span>
            <div class="detail-value">
                <div class="json-section"><?= htmlspecialchars(json_encode($log->new_value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($log->error_msg)): ?>
        <div class="detail-item">
            <span class="detail-label">错误信息</span>
            <span class="detail-value error-msg"><?= htmlspecialchars($log->error_msg) ?></span>
        </div>
        <?php endif; ?>
        
        <div class="detail-item">
            <span class="detail-label">操作时间</span>
            <span class="detail-value"><?= $log->created_at ?></span>
        </div>
        
        <div class="form-actions">
            <button class="layui-btn layui-btn-primary" onclick="parent.layer.closeAll()">关闭</button>
        </div>
    </div>
</body>
</html>