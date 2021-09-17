<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid">
        <form class="layui-form hd-margin-top-30" method="post" autocomplete="off">
            <div class="layui-form-item">
                <label class="layui-form-label">支付方式</label>
                <div class="layui-input-block hd-width-500">
                    <select name="code">
                        <option value="">请选择支付方式</option>
                        <?php
                        if (!empty($payments)) {
                            foreach ($payments as $code => $text) {
                                if ($payment_code == $code) {
                                    echo '<option value="', $code, '" selected>', $text, '</option>';
                                } else {
                                    echo '<option value="', $code, '">', $text, '</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input hd-int-only" name="sort" maxlength="5"
                           value="<?php echo $sort ?? ''; ?>"/>
                </div>
                <div class="layui-form-mid layui-word-aux">排序由小到大，不填则默认为0</div>
            </div>
            <?php
            if (!empty($cfg_list)) {
                foreach ($cfg_list as $cfg) {
                    $cfg['config_value'] = trim($cfg['config_value']);
                    echo '<div class="layui-form-item"><label class="layui-form-label">', $cfg['config_name'], '</label>';
                    echo '<div class="layui-input-block hd-width-500">';
                    if ($cfg['value_type'] == 'password') {
                        echo '<input type="text" class="layui-input" name="cfg_list[', $cfg['config_key'], ']" placeholder="', hide_chars($cfg['config_value']), '"/>';
                    } else {
                        echo '<input type="text" class="layui-input" name="cfg_list[', $cfg['config_key'], ']" value="', $cfg['config_value'], '"/>';
                    }
                    echo '</div></div>';
                }
            }
            ?>
            <div class="layui-form-item hd-margin-top-30">
                <div class="layui-input-block">
                    <input type="hidden" name="hash_tk" value="<?php echo $csrf_token; ?>"/>
                    <input class="layui-btn" type="submit" lay-submit lay-filter="payment_edit"
                           value="<?php echo xss_text('save', true); ?>"/>
                    <input class="layui-btn layui-btn-primary hd-layer-close" type="button"
                           value="<?php echo xss_text('cancel', true); ?>"/>
                </div>
            </div>
        </form>
    </div>

    <script>
        layui.use(['form'], function () {
            layui.form.on('submit(payment_edit)', function (formObj) {
                form_submit(window.location.href, formObj.field);

                return false;
            });
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
