<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑文章</title>
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
        .form-footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            text-align: right;
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        .form-row .layui-form-item {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-title">编辑文章</div>
        
        <form class="layui-form" action="" method="post" id="articleForm">
            <input type="hidden" name="id" value="<?= htmlspecialchars($article->id) ?>">
            
            <!-- 基础信息 -->
            <div class="layui-form-item">
                <label class="layui-form-label">文章标题</label>
                <div class="layui-input-block">
                    <input type="text" name="title" value="<?= htmlspecialchars($article->title) ?>" lay-verify="required" placeholder="请输入文章标题" class="layui-input" style="width: 600px;">
                </div>
            </div>
            
            <div class="form-row">
                <div class="layui-form-item">
                    <label class="layui-form-label">副标题</label>
                    <div class="layui-input-block">
                        <input type="text" name="subtitle" value="<?= htmlspecialchars($article->subtitle) ?>" placeholder="请输入副标题" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">分类</label>
                    <div class="layui-input-block">
                        <select name="category_id">
                            <option value="0">未分类</option>
                            <?php echo $this->renderCategoryOptions($category_tree, 0); ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="layui-form-item">
                    <label class="layui-form-label">来源</label>
                    <div class="layui-input-block">
                        <select name="source">
                            <?php foreach ($source_options as $option) : ?>
                            <option value="<?= $option['value'] ?>" <?php if ($article->source == $option['value']) echo 'selected'; ?>><?= $option['label'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">原文链接</label>
                    <div class="layui-input-block">
                        <input type="text" name="source_url" value="<?= htmlspecialchars($article->source_url) ?>" placeholder="转载时填写原文链接" class="layui-input">
                    </div>
                </div>
            </div>
            
            <!-- 封面图 -->
            <div class="layui-form-item">
                <label class="layui-form-label">封面图</label>
                <div class="layui-input-block">
                    <input type="text" name="art_cover" id="art_cover" value="<?= htmlspecialchars($article->art_cover) ?>" placeholder="请输入封面图URL" class="layui-input" style="width: 500px; display: inline-block;">
                    <button type="button" class="layui-btn" onclick="uploadCover()">上传图片</button>
                    <?php if ($article->art_cover) : ?>
                    <div id="cover-preview" style="margin-top: 10px;">
                        <img id="cover-image" src="<?= htmlspecialchars($article->art_cover) ?>" style="max-width: 200px; max-height: 150px; border: 1px solid #eee;">
                    </div>
                    <?php else : ?>
                    <div id="cover-preview" style="margin-top: 10px; display: none;">
                        <img id="cover-image" src="" style="max-width: 200px; max-height: 150px; border: 1px solid #eee;">
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- 内容类型切换 -->
            <div class="layui-form-item">
                <label class="layui-form-label">内容类型</label>
                <div class="layui-input-block">
                    <div class="layui-tab" lay-filter="contentType">
                        <ul class="layui-tab-title">
                            <?php foreach ($content_type_options as $option) : ?>
                            <li data-value="<?= $option['value'] ?>"><?= $option['label'] ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="layui-tab-content">
                            <div class="layui-tab-item layui-show">
                                <textarea name="art_content" id="richTextEditor" lay-verify="required" placeholder="请输入文章内容" class="layui-textarea" style="height: 300px;"><?= htmlspecialchars($article->art_content) ?></textarea>
                            </div>
                            <div class="layui-tab-item">
                                <textarea name="art_content_md" id="markdownEditor" placeholder="请输入Markdown内容" class="layui-textarea" style="height: 300px;"><?= htmlspecialchars($article->art_content) ?></textarea>
                            </div>
                            <div class="layui-tab-item">
                                <textarea name="art_content_text" id="plainTextEditor" placeholder="请输入纯文本内容" class="layui-textarea" style="height: 300px;"><?= htmlspecialchars($article->art_content) ?></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="content_type" value="<?= $article->content_type ?>" id="content_type">
                </div>
            </div>
            
            <!-- 文章摘要 -->
            <div class="layui-form-item">
                <label class="layui-form-label">文章摘要</label>
                <div class="layui-input-block">
                    <textarea name="summary" placeholder="文章摘要（自动截取或手动填写）" class="layui-textarea" style="width: 600px; height: 80px;"><?= htmlspecialchars($article->summary) ?></textarea>
                    <button type="button" class="layui-btn layui-btn-xs" onclick="generateSummary()">自动生成</button>
                </div>
            </div>
            
            <!-- 状态设置 -->
            <div class="layui-form-item">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-block">
                    <select name="art_status">
                        <?php foreach ($status_options as $option) : ?>
                        <option value="<?= $option['value'] ?>" <?php if ($article->art_status == $option['value']) echo 'selected'; ?>><?= $option['label'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <!-- 开关设置 -->
            <div class="form-row">
                <div class="layui-form-item">
                    <label class="layui-form-label">置顶</label>
                    <div class="layui-input-block">
                        <input type="radio" name="is_top" value="1" title="是" <?php if ($article->is_top == 1) echo 'checked'; ?>>
                        <input type="radio" name="is_top" value="0" title="否" <?php if ($article->is_top == 0) echo 'checked'; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">原创</label>
                    <div class="layui-input-block">
                        <input type="radio" name="is_original" value="1" title="是" <?php if ($article->is_original == 1) echo 'checked'; ?>>
                        <input type="radio" name="is_original" value="0" title="否" <?php if ($article->is_original == 0) echo 'checked'; ?>>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">允许评论</label>
                    <div class="layui-input-block">
                        <input type="radio" name="is_commentable" value="1" title="是" <?php if ($article->is_commentable == 1) echo 'checked'; ?>>
                        <input type="radio" name="is_commentable" value="0" title="否" <?php if ($article->is_commentable == 0) echo 'checked'; ?>>
                    </div>
                </div>
            </div>
            
            <!-- SEO设置 -->
            <div class="layui-form-item">
                <label class="layui-form-label">SEO标题</label>
                <div class="layui-input-block">
                    <input type="text" name="seo_title" value="<?= htmlspecialchars($article->seo_title) ?>" placeholder="为空时取文章标题" class="layui-input" style="width: 600px;">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">SEO关键词</label>
                <div class="layui-input-block">
                    <input type="text" name="seo_keywords" value="<?= htmlspecialchars($article->seo_keywords) ?>" placeholder="请输入SEO关键词，多个用逗号分隔" class="layui-input" style="width: 600px;">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">SEO描述</label>
                <div class="layui-input-block">
                    <textarea name="seo_description" placeholder="请输入SEO描述" class="layui-textarea" style="width: 600px; height: 80px;"><?= htmlspecialchars($article->seo_description) ?></textarea>
                </div>
            </div>
            
            <!-- 提交按钮 -->
            <div class="form-footer">
                <button type="button" class="layui-btn layui-btn-primary" onclick="saveDraft()">存为草稿</button>
                <button type="button" class="layui-btn" onclick="submitForm()">保存修改</button>
                <button type="button" class="layui-btn layui-btn-normal" onclick="publishArticle()">发布文章</button>
                <button type="button" class="layui-btn layui-btn-primary" onclick="closeDialog()">取消</button>
            </div>
        </form>
    </div>
    
    <script src="/static/backend/layui/layui.js"></script>
    <script>
        layui.use(['form', 'layer', 'element'], function() {
            var form = layui.form;
            var layer = layui.layer;
            var element = layui.element;
            
            form.render();
            
            // 设置默认内容类型tab
            var contentType = '<?= $article->content_type ?>';
            element.tabChange('contentType', contentType - 1);
            
            // 内容类型切换
            element.on('tab(contentType)', function(data) {
                var value = $(this).data('value');
                document.getElementById('content_type').value = value;
            });
            
            // 设置默认分类
            form.val('articleForm', {
                category_id: <?= $article->category_id ?>
            });
            
            // 上传封面（模拟）
            function uploadCover() {
                layer.msg('请上传图片到服务器后填写URL', {icon: 6});
            }
            
            // 自动生成摘要
            function generateSummary() {
                var content = getCurrentContent();
                var text = content.replace(/<[^>]*>/g, ''); // 去除HTML标签
                text = text.replace(/\s+/g, ' '); // 去除空白
                if (text.length > 150) {
                    text = text.substring(0, 150) + '...';
                }
                document.querySelector('textarea[name="summary"]').value = text;
            }
            
            // 获取当前编辑器内容
            function getCurrentContent() {
                var contentType = document.getElementById('content_type').value;
                if (contentType == 1) {
                    return document.getElementById('richTextEditor').value;
                } else if (contentType == 2) {
                    return document.getElementById('markdownEditor').value;
                } else {
                    return document.getElementById('plainTextEditor').value;
                }
            }
            
            // 切换内容到当前类型
            function syncContent() {
                var contentType = document.getElementById('content_type').value;
                var content = getCurrentContent();
                
                // 将内容同步到主字段
                document.querySelector('textarea[name="art_content"]').value = content;
                document.getElementById('markdownEditor').value = content;
                document.getElementById('plainTextEditor').value = content;
            }
            
            // 存为草稿
            function saveDraft() {
                document.querySelector('select[name="art_status"]').value = 1;
                submitForm();
            }
            
            // 保存修改
            function submitForm() {
                syncContent();
                
                form.verify({
                    title: function(value) {
                        if (!value) {
                            return '请输入文章标题';
                        }
                    }
                });
                
                if (!form.checkValid()) {
                    return;
                }
                
                layui.$.ajax({
                    url: '/backend/article/update',
                    type: 'POST',
                    data: form.val('articleForm'),
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
            
            // 发布文章
            function publishArticle() {
                document.querySelector('select[name="art_status"]').value = 4;
                syncContent();
                
                form.verify({
                    title: function(value) {
                        if (!value) {
                            return '请输入文章标题';
                        }
                    }
                });
                
                if (!form.checkValid()) {
                    return;
                }
                
                layer.confirm('确定要发布这篇文章吗？', {
                    icon: 3,
                    title: '提示'
                }, function(index) {
                    layer.close(index);
                    
                    layui.$.ajax({
                        url: '/backend/article/update',
                        type: 'POST',
                        data: form.val('articleForm'),
                        dataType: 'json',
                        beforeSend: function() {
                            layer.load(2);
                        },
                        success: function(res) {
                            layer.closeAll('loading');
                            if (res.code === 1) {
                                layer.msg('发布成功', {icon: 1, time: 1000}, function() {
                                    var index = parent.layer.getFrameIndex(window.name);
                                    parent.layer.close(index);
                                });
                            } else {
                                layer.msg(res.msg, {icon: 5});
                            }
                        },
                        error: function() {
                            layer.closeAll('loading');
                            layer.msg('发布失败', {icon: 5});
                        }
                    });
                });
            }
            
            // 关闭弹窗
            function closeDialog() {
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
            }
        });
    </script>
    
    <?php
    /**
     * 递归渲染分类选项
     * @param array $categories 分类数据
     * @param int $level 当前层级
     */
    function renderCategoryOptions(array $categories, int $level): string
    {
        $html = '';
        foreach ($categories as $category) {
            $prefix = str_repeat('└── ', $level);
            $html .= '<option value="' . $category['id'] . '">' . $prefix . htmlspecialchars($category['category_name']) . '</option>';
            
            if (isset($category['children']) && !empty($category['children'])) {
                $html .= renderCategoryOptions($category['children'], $level + 1);
            }
        }
        return $html;
    }
    ?>
</body>
</html>
