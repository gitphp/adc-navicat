<div class="form-container">
    <form class="layui-form" id="categoryForm">
        <div class="layui-form-item">
            <label class="layui-form-label">父级分类</label>
            <div class="layui-input-block">
                <select name="parent_id" lay-filter="parent_id">
                    <option value="0">顶级分类</option>
                    <?php echo renderCategoryOptions($categories); ?>
                </select>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">分类名称</label>
            <div class="layui-input-block">
                <input type="text" name="cat_name" placeholder="请输入分类名称" lay-verify="required" class="layui-input">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">URL别名</label>
            <div class="layui-input-block">
                <input type="text" name="cat_url" placeholder="如：company-news" class="layui-input">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">分类描述</label>
            <div class="layui-input-block">
                <textarea name="description" placeholder="请输入分类描述" class="layui-textarea" rows="2"></textarea>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">排序权重</label>
            <div class="layui-input-block">
                <input type="number" name="cat_sort" value="0" min="0" class="layui-input">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="radio" name="status" value="1" title="启用" checked>
                <input type="radio" name="status" value="0" title="禁用">
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
 * 递归渲染分类选项
 * @param array $categories 分类树
 * @param int $level 层级
 */
function renderCategoryOptions(array $categories, int $level = 0): string
{
    $html = '';
    $prefix = str_repeat('└── ', $level);
    
    foreach ($categories as $category) {
        $selected = $level == 0 && isset($parent_id) && $category['id'] == $parent_id ? 'selected' : '';
        $html .= '<option value="' . $category['id'] . '" ' . $selected . '>' . $prefix . htmlspecialchars($category['cat_name']) . '</option>';
        
        if (isset($category['children']) && !empty($category['children'])) {
            $html .= renderCategoryOptions($category['children'], $level + 1);
        }
    }
    
    return $html;
}
?>

<script>
    layui.use(['form', 'layer'], function() {
        var form = layui.form;
        var layer = layui.layer;
        
        // 设置默认父级
        <?php if (isset($parent_id) && $parent_id != '0'): ?>
        form.val('categoryForm', {
            'parent_id': '<?= $parent_id ?>'
        });
        <?php endif; ?>
        
        // 监听表单提交
        form.on('submit(save)', function(data) {
            layui.$.ajax({
                url: '/backend/articlecategory/save',
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