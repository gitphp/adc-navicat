<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>角色权限配置</title>
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
        .role-info {
            margin-bottom: 20px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        .role-info label {
            display: inline-block;
            width: 80px;
            color: #666;
        }
        .permission-list {
            max-height: 400px;
            overflow-y: auto;
        }
        .permission-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .permission-item:last-child {
            border-bottom: none;
        }
        .permission-item input[type="checkbox"] {
            margin-right: 10px;
        }
        .permission-name {
            flex: 1;
        }
        .permission-code {
            font-size: 12px;
            color: #999;
            margin-right: 10px;
        }
        .permission-type {
            font-size: 12px;
            color: #999;
        }
        .type-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
        }
        .type-menu {
            background: #ecf5ff;
            color: #409eff;
        }
        .type-button {
            background: #f0f9eb;
            color: #67c23a;
        }
        .type-api {
            background: #f5f0ff;
            color: #9b59b6;
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
        <div class="form-title">角色权限配置</div>
        
        <div class="role-info">
            <label>角色名称：</label><span><?= htmlspecialchars($role->role_name) ?></span>&nbsp;&nbsp;
            <label>角色标识：</label><span><?= htmlspecialchars($role->role_code) ?></span>
        </div>
        
        <form class="layui-form" action="" method="post" id="permissionForm">
            <input type="hidden" name="role_id" value="<?= htmlspecialchars($role->id) ?>">
            
            <div class="layui-form-item">
                <label class="layui-form-label">选择权限</label>
                <div class="layui-input-block">
                    <div class="permission-list">
                        <?php foreach ($permissions as $permission) : ?>
                        <div class="permission-item">
                            <input type="checkbox" name="permission_ids[]" value="<?= $permission->id ?>" 
                                <?php if (in_array($permission->id, $permission_ids)) : ?>checked<?php endif; ?>
                                lay-skin="primary" title="">
                            <span class="permission-name"><?= htmlspecialchars($permission->per_name) ?></span>
                            <span class="permission-code"><?= htmlspecialchars($permission->per_code) ?></span>
                            <?php if ($permission->per_type === 'menu') : ?>
                            <span class="type-badge type-menu">菜单</span>
                            <?php elseif ($permission->per_type === 'button') : ?>
                            <span class="type-badge type-button">按钮</span>
                            <?php else : ?>
                            <span class="type-badge type-api">接口</span>
                            <?php endif; ?>
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
            function submitForm() {
                var permissionIds = [];
                var checkboxes = document.querySelectorAll('input[name="permission_ids[]"]:checked');
                checkboxes.forEach(function(checkbox) {
                    permissionIds.push(checkbox.value);
                });
                
                layui.$.ajax({
                    url: '/backend/role/savePermission',
                    type: 'POST',
                    data: {
                        role_id: document.querySelector('input[name="role_id"]').value,
                        permission_ids: permissionIds,
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
            function closeDialog() {
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
            }
        });
    </script>
</body>
</html>
