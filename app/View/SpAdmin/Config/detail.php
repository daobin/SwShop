<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false]);
?>
    <div class="layui-fluid">
        <form class="layui-form hd-margin-top30" method="post" autocomplete="off">
            <div class="layui-form-item">
                <label class="layui-form-label">配置项</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" disabled value="<?php echo xss_text($config_name); ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">配置值</label>
                <div class="layui-input-block">
                    <?php
                    switch (strtolower($value_type)) {
                        case 'password':
                            echo '<textarea class="layui-textarea" name="config_value" placeholder="' . hide_chars($config_value) . '"></textarea>';
                            break;
                        case 'list':
                            $config_value_arr = [];
                            if(!empty($config_value)){
                                foreach ($config_value as $val) {
                                    $config_value_arr[] = [
                                        'name' => $val,
                                        'value' => $val,
                                        'selected' => true
                                    ];
                                }
                            }
                            $config_value = json_encode($config_value_arr);
                            unset($config_value_arr);

                            echo '<div id="value_select"></div>';
                            break;
                        default:
                            echo '<textarea class="layui-textarea" name="config_value">' . $config_value . '</textarea>';
                    }
                    ?>
                </div>
            </div>
            <div class="layui-form-item hd-margin-top30">
                <div class="layui-input-block">
                    <input type="hidden" name="hash_tk" value="<?php echo $csrf_token; ?>"/>
                    <input type="hidden" name="value_type" value="<?php echo strtolower($value_type); ?>"/>
                    <input class="layui-btn" type="submit" lay-submit lay-filter="cfg_edit"
                           value="<?php echo xss_text('save', true); ?>"/>
                    <input class="layui-btn layui-btn-primary hd-layer-close" type="button"
                           value="<?php echo xss_text('cancel', true); ?>"/>
                </div>
            </div>
        </form>
    </div>
    <script src="/static/layui/xm-select.js"></script>
    <script>
        layui.use(['form'], function () {
            if ($('#value_select').length == 1) {
                xmSelect.render({
                    el: '#value_select',
                    name: 'config_value',
                    height: 'auto',
                    tips: '请选择配置',
                    data: JSON.parse('<?php echo $config_value;?>'),
                    filterable: true,
                    searchTips: '请选择配置，若不存在配置请输入新增',
                    create: function (val) {
                        return {
                            name: val,
                            value: val
                        };
                    }
                });
            }

            layui.form.on('submit(cfg_edit)', function (formObj) {
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
