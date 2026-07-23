<div class="form-container">
    <form class="layui-form" id="categoryForm">
        <input type="hidden" name="id" value="<?= $category['id'] ?>">
        
        <div class="layui-form-item">
            <label class="layui-form-label">父级分类</label>
            <div class="layui-input-block">
                <select name="parent_id">
                    <option value="0">顶级分类</option>
                    <?php echo renderCategoryOptions($categories, 0, $category['id']); ?>
                </select>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">分类名称</label>
            <div class="layui-input-block">
                <input type="text" name="cat_name" value="<?= htmlspecialchars($category['cat_name']) ?>" placeholder="请输入分类名称" lay-verify="required" class="layui-input">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">URL别名</label>
            <div class="layui-input-block">
                <input type="text" name="cat_url" value="<?= htmlspecialchars($category['cat_url']) ?>" placeholder="如：company-news" class="layui-input">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">分类描述</label>
            <div class="layui-input-block">
                <textarea name="description" placeholder="请输入分类描述" class="layui-textarea" rows="2"><?= htmlspecialchars($category['description']) ?></textarea>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">排序权重</label>
            <div class="layui-input-block">
                <input type="number" name="cat_sort" value="<?= $category['cat_sort'] ?>" min="0" class="layui-input">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="radio" name="status" value="1" title="启用" <?= $category['status'] == 1 ? 'checked' : '' ?>>
                <input type="radio" name="status" value="0" title="禁用" <?= $category['status'] == 0 ? 'checked' : '' ?>>
            </div>
        </div>
        
        <div class="form-actions">
            <button class="layui-btn" lay-submit lay-filter="save">保存</button>
            <button type="button" class="layui-btn layui-btn-primary" onclick="parent.layer.closeAll()">取消</button>
        </div>
    </form>
</div>

<?php
/**
 * 递归渲染分类选项（排除当前分类及其子分类）
 * @param array $categories 分类树
 * @param int $level 层级
 * @param string $excludeId 排除的分类ID
 */
function renderCategoryOptions(array $categories, int $level = 0, string $excludeId = ''): string
{
    $html = '';
    $prefix = str_repeat('└── ', $level);
    
    foreach ($categories as $category) {
        // 排除当前分类及其子分类
        if ($category['id'] == $excludeId) {
            continue;
        }
        
        $selected = $category['id'] == $category['parent_id'] ? 'selected' : '';
        $html .= '<option value="' . $category['id'] . '" ' . $selected . '>' . $prefix . htmlspecialchars($category['cat_name']) . '</option>';
        
        if (isset($category['children']) && !empty($category['children'])) {
            $html .= renderCategoryOptions($category['children'], $level + 1, $excludeId);
        }
    }
    
    return $html;
}
?>

<script>
    layui.use(['form', 'layer'], function() {
        var form = layui.form;
        var layer = layui.layer;
        
        // 设置选中的父级分类
        form.val('categoryForm', {
            'parent_id': '<?= $category['parent_id'] ?>'
        });
        
        // 监听表单提交
        form.on('submit(save)', function(data) {
            layui.$.ajax({
                url: '/backend/articlecategory/update',
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