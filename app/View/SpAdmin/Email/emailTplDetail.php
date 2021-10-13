<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid">
        <form class="layui-form hd-margin-top-30" method="post" autocomplete="off">
            <div class="layui-form-item">
                <label class="layui-form-label">邮件标题</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="subject" maxlength="120"
                           value="<?php echo $tpl_info['subject']; ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">内容样式</label>
                <div class="layui-input-block">
                    <div style="max-width: 900px; padding-top: 5px;">
                        <?php
                        $template = ROOT_DIR . 'public/email/' . $tpl_info['template'] . '.html';
                        if (file_exists($template)) {
                            echo file_get_contents($template);
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="layui-form-item hd-margin-top-60">
                <div class="layui-input-block">
                    <input type="hidden" name="hash_tk" value="<?php echo $csrf_token; ?>"/>
                    <input class="layui-btn" type="submit" lay-submit lay-filter="currency_edit"
                           value="<?php echo xss_text('save', true); ?>"/>
                    <input class="layui-btn layui-btn-primary hd-layer-close" type="button"
                           value="<?php echo xss_text('cancel', true); ?>"/>
                </div>
            </div>
        </form>
    </div>

    <script>
        layui.use(['form'], function () {
            layui.form.on('submit(currency_edit)', function (formObj) {
                form_submit(window.location.href, formObj.field);

                return false;
            });
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
