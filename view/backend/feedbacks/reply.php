<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>回复留言</title>
    <link rel="stylesheet" href="/static/backend/layui/css/layui.css">
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #f0f2f5;
        }
        .form-container {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }
        .form-actions {
            margin-top: 20px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }
        .layui-form-item {
            margin-bottom: 15px;
        }
        .layui-form-label {
            width: 100px;
        }
        .layui-input-block {
            margin-left: 130px;
        }
        .feedback-info {
            margin-bottom: 20px;
            padding: 15px;
            background: #fafafa;
            border-radius: 8px;
        }
        .feedback-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .feedback-content {
            color: #666;
            line-height: 1.8;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <!-- 留言信息 -->
        <div class="feedback-info">
            <div class="feedback-title"><?= htmlspecialchars($feedback->fb_title) ?></div>
            <div class="feedback-content"><?= nl2br(htmlspecialchars($feedback->fb_content)) ?></div>
        </div>
        
        <form class="layui-form" id="replyForm">
            <input type="hidden" name="id" value="<?= $feedback->id ?>">
            
            <div class="layui-form-item">
                <label class="layui-form-label">回复内容</label>
                <div class="layui-input-block">
                    <textarea name="reply_content" placeholder="请输入回复内容" class="layui-textarea" rows="6" lay-verify="required"></textarea>
                </div>
            </div>
            
            <div class="form-actions">
                <button class="layui-btn" lay-submit lay-filter="save">提交回复</button>
                <button type="button" class="layui-btn layui-btn-primary" onclick="parent.layer.closeAll()">取消</button>
            </div>
        </form>
    </div>
    
    <script src="/static/backend/layui/layui.js"></script>
    <script>
        layui.use(['form', 'layer'], function() {
            var form = layui.form;
            var layer = layui.layer;
            
            // 监听表单提交
            form.on('submit(save)', function(data) {
                layui.$.ajax({
                    url: '/backend/feedbacks/saveReply',
                    type: 'POST',
                    data: data.field,
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1});
                            setTimeout(function() {
                                parent.layer.closeAll();
                            }, 1000);
                        } else {
                            layer.msg(res.msg, {icon: 5});
                        }
                    },
                    error: function() {
                        layer.msg('回复失败', {icon: 5});
                    }
                });
                return false;
            });
        });
    </script>
</body>
</html>