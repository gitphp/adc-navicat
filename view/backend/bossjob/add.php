<div class="form-container">
    <form class="layui-form" id="jobForm">
        <div class="form-group">
            <div class="form-group-title">基本信息</div>
            <div class="layui-form-item">
                <label class="layui-form-label">职位名称</label>
                <div class="layui-input-block">
                    <input type="text" name="job_title" placeholder="请输入职位名称" lay-verify="required" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">所属部门</label>
                <div class="layui-input-block">
                    <input type="text" name="department" placeholder="请输入所属部门" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">工作地点</label>
                <div class="layui-input-block">
                    <input type="text" name="workplace" placeholder="请输入工作地点" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">经验要求</label>
                    <div class="layui-input-inline" style="width: 150px;">
                        <input type="text" name="experience" placeholder="如：3-5年" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">学历要求</label>
                    <div class="layui-input-inline" style="width: 150px;">
                        <input type="text" name="education" placeholder="如：本科" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">薪资范围</label>
                    <div class="layui-input-inline" style="width: 150px;">
                        <input type="text" name="salary_range" placeholder="如：10K-20K" class="layui-input">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <div class="form-group-title">详细信息</div>
            <div class="layui-form-item">
                <label class="layui-form-label">职位描述</label>
                <div class="layui-input-block">
                    <textarea name="description" placeholder="请输入职位描述" class="layui-textarea" rows="4"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">任职要求</label>
                <div class="layui-input-block">
                    <textarea name="requirements" placeholder="请输入任职要求" class="layui-textarea" rows="4"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">福利待遇</label>
                <div class="layui-input-block">
                    <textarea name="benefits" placeholder="请输入福利待遇" class="layui-textarea" rows="3"></textarea>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <div class="form-group-title">发布设置</div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">是否急聘</label>
                    <div class="layui-input-inline" style="width: 100px;">
                        <input type="radio" name="is_hot" value="1" title="是">
                        <input type="radio" name="is_hot" value="0" title="否" checked>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">状态</label>
                    <div class="layui-input-inline" style="width: 150px;">
                        <select name="job_status">
                            <option value="1">待发布</option>
                            <option value="2">发布中</option>
                            <option value="3">已关闭</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">过期时间</label>
                    <div class="layui-input-inline" style="width: 200px;">
                        <input type="datetime" name="expire_at" placeholder="请选择过期时间" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">排序权重</label>
                    <div class="layui-input-inline" style="width: 100px;">
                        <input type="number" name="job_sort" value="0" min="0" class="layui-input">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button class="layui-btn" lay-submit lay-filter="save">保存</button>
            <button type="button" class="layui-btn layui-btn-primary" onclick="parent.layer.closeAll()">取消</button>
        </div>
    </form>
</div>

<script>
    layui.use(['form', 'layer', 'laydate'], function() {
        var form = layui.form;
        var layer = layui.layer;
        var laydate = layui.laydate;
        
        // 初始化日期选择器
        laydate.render({
            elem: '[name="expire_at"]',
            type: 'datetime',
            format: 'yyyy-MM-dd HH:mm:ss'
        });
        
        // 监听表单提交
        form.on('submit(save)', function(data) {
            layui.$.ajax({
                url: '/backend/bossjob/save',
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