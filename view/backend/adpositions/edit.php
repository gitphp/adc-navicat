<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑广告</title>
    <link rel="stylesheet" href="/static/backend/layui/css/layui.css">
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #f0f2f5;
            overflow-y: auto;
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
            width: 130px;
        }
        .layui-input-block {
            margin-left: 160px;
        }
        .form-group {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #e0e0e0;
        }
        .form-group:last-child {
            border-bottom: none;
        }
        .form-group-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form class="layui-form" id="adPositionsForm">
            <input type="hidden" name="id" value="<?= $adPosition->id ?>">
            
            <!-- 基本信息 -->
            <div class="form-group">
                <div class="form-group-title">基本信息</div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">广告标题</label>
                    <div class="layui-input-block">
                        <input type="text" name="ad_title" value="<?= htmlspecialchars($adPosition->ad_title) ?>" placeholder="请输入广告标题" lay-verify="required" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">副标题/描述</label>
                    <div class="layui-input-block">
                        <textarea name="subtitle" placeholder="请输入广告副标题或描述" class="layui-textarea" rows="2"><?= htmlspecialchars($adPosition->subtitle) ?></textarea>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">广告位编码</label>
                    <div class="layui-input-block">
                        <select name="position_code" lay-verify="required" lay-search>
                            <option value="">请选择广告位</option>
                            <?php foreach ($adSlots as $slot): ?>
                            <option value="<?= $slot['slot_code'] ?>" <?= $adPosition->position_code == $slot['slot_code'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($slot['slot_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- 素材信息 -->
            <div class="form-group">
                <div class="form-group-title">素材信息</div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">封面图URL</label>
                    <div class="layui-input-block">
                        <input type="text" name="cover_url" value="<?= htmlspecialchars($adPosition->cover_url) ?>" placeholder="请输入封面图URL" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">移动端封面</label>
                    <div class="layui-input-block">
                        <input type="text" name="cover_mobile" value="<?= htmlspecialchars($adPosition->cover_mobile) ?>" placeholder="请输入移动端封面图URL" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">缩略图URL</label>
                    <div class="layui-input-block">
                        <input type="text" name="cover_thumb" value="<?= htmlspecialchars($adPosition->cover_thumb) ?>" placeholder="请输入缩略图URL" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">视频URL</label>
                    <div class="layui-input-block">
                        <input type="text" name="video_url" value="<?= htmlspecialchars($adPosition->video_url) ?>" placeholder="请输入视频广告URL" class="layui-input">
                    </div>
                </div>
            </div>
            
            <!-- 跳转设置 -->
            <div class="form-group">
                <div class="form-group-title">跳转设置</div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">跳转类型</label>
                    <div class="layui-input-block">
                        <input type="radio" name="link_type" value="1" title="站内链接" <?= $adPosition->link_type == 1 ? 'checked' : '' ?>>
                        <input type="radio" name="link_type" value="2" title="站外链接" <?= $adPosition->link_type == 2 ? 'checked' : '' ?>>
                        <input type="radio" name="link_type" value="3" title="小程序" <?= $adPosition->link_type == 3 ? 'checked' : '' ?>>
                        <input type="radio" name="link_type" value="4" title="无跳转" <?= $adPosition->link_type == 4 ? 'checked' : '' ?>>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">跳转链接</label>
                    <div class="layui-input-block">
                        <input type="text" name="link_url" value="<?= htmlspecialchars($adPosition->link_url) ?>" placeholder="请输入跳转链接地址" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">小程序AppId</label>
                    <div class="layui-input-block">
                        <input type="text" name="app_id" value="<?= htmlspecialchars($adPosition->app_id) ?>" placeholder="跳转类型为小程序时填写" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">小程序路径</label>
                    <div class="layui-input-block">
                        <input type="text" name="app_path" value="<?= htmlspecialchars($adPosition->app_path) ?>" placeholder="跳转类型为小程序时填写" class="layui-input">
                    </div>
                </div>
            </div>
            
            <!-- 投放设置 -->
            <div class="form-group">
                <div class="form-group-title">投放设置</div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">投放平台</label>
                    <div class="layui-input-block">
                        <input type="radio" name="platform" value="1" title="全部" <?= $adPosition->platform == 1 ? 'checked' : '' ?>>
                        <input type="radio" name="platform" value="2" title="PC端" <?= $adPosition->platform == 2 ? 'checked' : '' ?>>
                        <input type="radio" name="platform" value="3" title="移动端" <?= $adPosition->platform == 3 ? 'checked' : '' ?>>
                        <input type="radio" name="platform" value="4" title="小程序" <?= $adPosition->platform == 4 ? 'checked' : '' ?>>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">设备类型</label>
                    <div class="layui-input-block">
                        <input type="radio" name="device_type" value="1" title="全部" <?= $adPosition->device_type == 1 ? 'checked' : '' ?>>
                        <input type="radio" name="device_type" value="2" title="iOS" <?= $adPosition->device_type == 2 ? 'checked' : '' ?>>
                        <input type="radio" name="device_type" value="3" title="Android" <?= $adPosition->device_type == 3 ? 'checked' : '' ?>>
                        <input type="radio" name="device_type" value="4" title="其他" <?= $adPosition->device_type == 4 ? 'checked' : '' ?>>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">用户定向</label>
                    <div class="layui-input-block">
                        <input type="radio" name="target_user_type" value="0" title="全部用户" <?= $adPosition->target_user_type == 0 ? 'checked' : '' ?>>
                        <input type="radio" name="target_user_type" value="1" title="新用户" <?= $adPosition->target_user_type == 1 ? 'checked' : '' ?>>
                        <input type="radio" name="target_user_type" value="2" title="老用户" <?= $adPosition->target_user_type == 2 ? 'checked' : '' ?>>
                        <input type="radio" name="target_user_type" value="3" title="VIP用户" <?= $adPosition->target_user_type == 3 ? 'checked' : '' ?>>
                        <input type="radio" name="target_user_type" value="4" title="指定用户组" <?= $adPosition->target_user_type == 4 ? 'checked' : '' ?>>
                    </div>
                </div>
            </div>
            
            <!-- 时间设置 -->
            <div class="form-group">
                <div class="form-group-title">时间设置</div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">投放开始时间</label>
                    <div class="layui-input-block">
                        <input type="datetime-local" name="start_time" value="<?= date('Y-m-d\TH:i', strtotime($adPosition->start_time)) ?>" lay-verify="required" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">投放结束时间</label>
                    <div class="layui-input-block">
                        <input type="datetime-local" name="end_time" value="<?= date('Y-m-d\TH:i', strtotime($adPosition->end_time)) ?>" lay-verify="required" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">展示时间类型</label>
                    <div class="layui-input-block">
                        <input type="radio" name="show_time_type" value="0" title="全天" <?= $adPosition->show_time_type == 0 ? 'checked' : '' ?>>
                        <input type="radio" name="show_time_type" value="1" title="自定义时间段" <?= $adPosition->show_time_type == 1 ? 'checked' : '' ?>>
                    </div>
                </div>
            </div>
            
            <!-- 高级设置 -->
            <div class="form-group">
                <div class="form-group-title">高级设置</div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">排序权重</label>
                    <div class="layui-input-block">
                        <input type="number" name="sort" value="<?= $adPosition->sort ?>" min="0" placeholder="值越大越靠前" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">展示频率</label>
                    <div class="layui-input-block">
                        <input type="radio" name="display_frequency" value="1" title="每人每天1次" <?= $adPosition->display_frequency == 1 ? 'checked' : '' ?>>
                        <input type="radio" name="display_frequency" value="2" title="每人每小时1次" <?= $adPosition->display_frequency == 2 ? 'checked' : '' ?>>
                        <input type="radio" name="display_frequency" value="3" title="无限次" <?= $adPosition->display_frequency == 3 ? 'checked' : '' ?>>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">每日展示限制</label>
                    <div class="layui-input-block">
                        <input type="number" name="daily_impression_limit" value="<?= $adPosition->daily_impression_limit ?>" min="0" placeholder="0表示不限制" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">每日点击限制</label>
                    <div class="layui-input-block">
                        <input type="number" name="daily_click_limit" value="<?= $adPosition->daily_click_limit ?>" min="0" placeholder="0表示不限制" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">计费方式</label>
                    <div class="layui-input-block">
                        <input type="radio" name="cost_type" value="1" title="CPM" <?= $adPosition->cost_type == 1 ? 'checked' : '' ?>>
                        <input type="radio" name="cost_type" value="2" title="CPC" <?= $adPosition->cost_type == 2 ? 'checked' : '' ?>>
                        <input type="radio" name="cost_type" value="3" title="CPT" <?= $adPosition->cost_type == 3 ? 'checked' : '' ?>>
                        <input type="radio" name="cost_type" value="4" title="CPA" <?= $adPosition->cost_type == 4 ? 'checked' : '' ?>>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">预算金额</label>
                    <div class="layui-input-block">
                        <input type="number" name="budget" step="0.01" value="<?= $adPosition->budget ?>" placeholder="投放预算" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label class="layui-form-label">出价金额</label>
                    <div class="layui-input-block">
                        <input type="number" name="bid_price" step="0.01" value="<?= $adPosition->bid_price ?>" placeholder="CPM/CPC出价" class="layui-input">
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button class="layui-btn" lay-submit lay-filter="save">保存</button>
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
                    url: '/backend/adpositions/update',
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
                        layer.msg('保存失败', {icon: 5});
                    }
                });
                return false;
            });
        });
    </script>
</body>
</html>