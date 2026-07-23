<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>分配角色</title>
    <link rel="stylesheet" href="/static/backend/layui/css/layui.css">
    <style>
        body {
            padding: 20px;
            background: #f5f7fa;
        }
        .form-container {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        }
        .form-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .user-info {
            margin-bottom: 20px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        .user-info label {
            display: inline-block;
            width: 80px;
            color: #666;
        }
        .role-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .role-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .role-item:last-child {
            border-bottom: none;
        }
        .role-item input[type="checkbox"] {
            margin-right: 10px;
        }
        .role-name {
            flex: 1;
        }
        .role-type {
            font-size: 12px;
            color: #999;
        }
        .form-footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-title">分配角色</div>
        
        <div class="user-info">
            <label>用户名：</label><span><?= htmlspecialchars($user->user_name) ?></span>&nbsp;&nbsp;
            <label>昵称：</label><span><?= htmlspecialchars($user->user_nick) ?></span>&nbsp;&nbsp;
            <label>手机号：</label><span><?= htmlspecialchars($user->user_mobile) ?></span>
        </div>
        
        <form class="layui-form" action="" method="post" id="roleForm">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user->id) ?>">
            
            <div class="layui-form-item">
                <label class="layui-form-label">选择角色</label>
                <div class="layui-input-block">
                    <div class="role-list">
                        <?php foreach ($roles as $role) : ?>
                        <div class="role-item">
                            <input type="checkbox" name="role_ids[]" value="<?= $role->id ?>" 
                                <?php if (in_array($role->id, $user_role_ids)) : ?>checked<?php endif; ?>
                                lay-skin="primary" title="">
                            <span class="role-name"><?= htmlspecialchars($role->role_name) ?></span>
                            <span class="role-type">(<?= $role->getTypeText() ?>)</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="form-footer">
                <button type="button" class="layui-btn" onclick="submitForm()">提交</button>
                <button type="button" class="layui-btn layui-btn-primary" onclick="closeDialog()">取消</button>
            </div>
        </form>
    </div>
    
    <script src="/static/backend/layui/layui.js"></script>
    <script>
        layui.use(['form', 'layer'], function() {
            var form = layui.form;
            var layer = layui.layer;
            
            form.render();
            
            // 提交表单
            window.submitForm = function() {
                var roleIds = [];
                var checkboxes = document.querySelectorAll('input[name="role_ids[]"]:checked');
                checkboxes.forEach(function(checkbox) {
                    roleIds.push(checkbox.value);
                });
                
                layui.$.ajax({
                    url: '/backend/user/updateRoles',
                    type: 'POST',
                    data: {
                        user_id: document.querySelector('input[name="user_id"]').value,
                        role_ids: roleIds,
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        layer.load(2);
                    },
                    success: function(res) {
                        layer.closeAll('loading');
                        if (res.code === 1) {
                            layer.msg(res.msg, {icon: 1, time: 1000}, function() {
                                var index = parent.layer.getFrameIndex(window.name);
                                parent.layer.close(index);
                            });
                        } else {
                            layer.msg(res.msg, {icon: 5});
                        }
                    },
                    error: function() {
                        layer.closeAll('loading');
                        layer.msg('提交失败', {icon: 5});
                    }
                });
            }
            
            // 关闭弹窗
            window.closeDialog = function() {
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
            }
        });
    </script>
</body>
</html>
