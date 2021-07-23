<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false]);
?>
    <div class="layui-fluid">
        <form class="layui-form margin-top30" method="post" autocomplete="off">
            <div class="layui-form-item">
                <label class="layui-form-label">父级类目</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" disabled value="<?php echo xss_text($config_name); ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">配置值</label>
                <div class="layui-input-block">
                    <?php
                    if (strtolower($value_type) == 'password') {
                        echo '<textarea class="layui-textarea" name="config_value" placeholder="' . hide_chars($config_value) . '"></textarea>';
                    } else {
                        echo '<textarea class="layui-textarea" name="config_value">' . $config_value . '</textarea>';
                    }
                    ?>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input type="hidden" name="hash_tk" value="<?php echo $csrf_token; ?>"/>
                    <input class="layui-btn" type="submit" lay-submit lay-filter="cate_edit"
                           value="<?php echo xss_text('save', true); ?>"/>
                </div>
            </div>
        </form>
    </div>
    <script>
        layui.use(['form'], function () {
            layui.form.on('submit(cate_edit)', function (formObj) {
                if ($.trim(formObj.field.config_value) == '') {
                    layer.confirm('配置值是否保存为空？', open_ask_cfg, function (idx) {
                        form_submit(window.location.href, formObj.field);
                        layer.close(idx);
                    });
                } else {
                    form_submit(window.location.href, formObj.field);
                }

                return false;
            });
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
