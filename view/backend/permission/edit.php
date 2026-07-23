<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑权限</title>
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
        .layui-form-item {
            margin-bottom: 15px;
        }
        .layui-form-label {
            width: 120px;
        }
        .layui-input-block {
            margin-left: 150px;
        }
        .form-footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            text-align: right;
        }
        .method-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .method-option {
            padding: 4px 10px;
            border: 1px solid #e6e6e6;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .method-option.active {
            background: #1E9FFF;
            color: #fff;
            border-color: #1E9FFF;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-title">编辑权限</div>
        
        <form class="layui-form" action="" method="post" id="permissionForm">
            <input type="hidden" name="id" value="<?= htmlspecialchars($permission->id) ?>">
            
            <div class="layui-form-item">
                <label class="layui-form-label">权限名称 <span style="color: red;">*</span></label>
                <div class="layui-input-block">
                    <input type="text" name="per_name" id="per_name" required lay-verify="required" 
                           placeholder="请输入权限名称" class="layui-input" 
                           value="<?= htmlspecialchars($permission->per_name) ?>">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">权限标识 <span style="color: red;">*</span></label>
                <div class="layui-input-block">
                    <input type="text" name="per_code" id="per_code" required lay-verify="required" 
                           placeholder="请输入权限标识（如：user:delete）" class="layui-input" 
                           value="<?= htmlspecialchars($permission->per_code) ?>">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">权限类型 <span style="color: red;">*</span></label>
                <div class="layui-input-block">
                    <select name="per_type" id="per_type" required lay-verify="required" class="layui-input" onchange="typeChange()">
                        <?php foreach ($per_type_options as $key => $value) : ?>
                        <option value="<?= $key ?>" <?php if ($permission->per_type == $key) : ?>selected<?php endif; ?>>
                            <?= htmlspecialchars($value) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">父级权限</label>
                <div class="layui-input-block">
                    <select name="parent_id" id="parent_id" class="layui-input">
                        <option value="0" <?php if ($permission->parent_id == 0) : ?>selected<?php endif; ?>>无（顶级权限）</option>
                        <?php foreach ($parent_options as $option) : ?>
                        <option value="<?= $option['id'] ?>" <?php if ($permission->parent_id == $option['id']) : ?>selected<?php endif; ?>>
                            <?= htmlspecialchars($option['per_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">路径</label>
                <div class="layui-input-block">
                    <input type="text" name="per_path" id="per_path" 
                           placeholder="前端路由路径或API路径" class="layui-input" 
                           value="<?= htmlspecialchars($permission->per_path) ?>">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">HTTP方法</label>
                <div class="layui-input-block">
                    <div class="method-options">
                        <div class="method-option" onclick="selectMethod('GET')">GET</div>
                        <div class="method-option" onclick="selectMethod('POST')">POST</div>
                        <div class="method-option" onclick="selectMethod('PUT')">PUT</div>
                        <div class="method-option" onclick="selectMethod('DELETE')">DELETE</div>
                        <div class="method-option" onclick="selectMethod('')">清空</div>
                    </div>
                    <input type="hidden" name="per_method" id="per_method" value="<?= htmlspecialchars($permission->per_method) ?>">
                </div>
            </div>
            
            <div class="layui-form-item" id="iconField">
                <label class="layui-form-label">菜单图标</label>
                <div class="layui-input-block">
                    <input type="text" name="per_icon" id="per_icon" 
                           placeholder="请输入图标名称" class="layui-input" 
                           value="<?= htmlspecialchars($permission->per_icon) ?>">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">排序号</label>
                <div class="layui-input-block">
                    <input type="number" name="per_sort" id="per_sort" 
                           placeholder="请输入排序号" class="layui-input" style="width: 150px;"
                           value="<?= htmlspecialchars($permission->per_sort) ?>">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">权限状态 <span style="color: red;">*</span></label>
                <div class="layui-input-block">
                    <input type="radio" name="per_status" value="1" title="启用" 
                           <?php if ($permission->per_status == 1) : ?>checked<?php endif; ?>>
                    <input type="radio" name="per_status" value="0" title="禁用" 
                           <?php if ($permission->per_status == 0) : ?>checked<?php endif; ?>>
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
            
            // 权限类型切换
            function typeChange() {
                var type = document.getElementById('per_type').value;
                var iconField = document.getElementById('iconField');
                
                if (type === 'menu') {
                    iconField.style.display = '';
                } else {
                    iconField.style.display = 'none';
                }
            }
            
            // 选择HTTP方法
            function selectMethod(method) {
                document.getElementById('per_method').value = method;
                
                // 更新样式
                var options = document.querySelectorAll('.method-option');
                options.forEach(function(opt) {
                    if (opt.textContent === method || (method === '' && opt.textContent === '清空')) {
                        opt.classList.add('active');
                    } else {
                        opt.classList.remove('active');
                    }
                });
            }
            
            // 提交表单
            window.submitForm = function() {
                var perName = document.getElementById('per_name').value.trim();
                var perCode = document.getElementById('per_code').value.trim();
                
                if (!perName) {
                    layer.msg('请输入权限名称', {icon: 5});
                    return;
                }
                
                if (!perCode) {
                    layer.msg('请输入权限标识', {icon: 5});
                    return;
                }
                
                // 获取表单数据
                var formData = {
                    id: document.querySelector('input[name="id"]').value,
                    per_name: perName,
                    per_code: perCode,
                    per_type: document.getElementById('per_type').value,
                    parent_id: document.getElementById('parent_id').value,
                    per_path: document.getElementById('per_path').value,
                    per_method: document.getElementById('per_method').value,
                    per_icon: document.getElementById('per_icon').value,
                    per_sort: document.getElementById('per_sort').value,
                    per_status: form.val('permissionForm').per_status,
                };
                
                layui.$.ajax({
                    url: '/backend/permission/update',
                    type: 'POST',
                    data: formData,
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
            
            // 初始化
            typeChange();
            var currentMethod = document.getElementById('per_method').value;
            selectMethod(currentMethod);
        });
    </script>
</body>
</html>
