<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid">
        <form class="layui-form hd-margin-top-30" method="post" autocomplete="off">
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
                    if (strtolower($config_group) == 'css') {
                        echo '<div class="layui-collapse" lay-accordion>';
                        if (!empty($config_value)) {
                            foreach ($config_value as $theme => $css) {
                                echo '<div class="layui-colla-item">';
                                echo '<h2 class="layui-colla-title">前台模板【', $theme, '】</h2>';
                                echo '<div class="layui-colla-content layui-show">';
                                if (is_array($css) && !empty($css)) {
                                    foreach ($css as $name) {
                                        $css_content = '';
                                        $css_file = ROOT_DIR . 'public/static/index/' . strtolower($theme) . '/' . strtolower($name) . '.css';
                                        if (file_exists($css_file)) {
                                            $css_content = file_get_contents($css_file);
                                        }

                                        echo '<div class="layui-form-item">';
                                        echo '<label class="layui-form-label">', $name, '.css</label>';
                                        echo '<div class="layui-input-block">';
                                        echo '<textarea class="layui-textarea" name="css_list[', $theme, '][', $name, ']" style="height: 200px;">', $css_content, '</textarea></div></div>';
                                    }
                                }
                                echo '</div></div>';
                            }
                        }
                        echo '</div>';
                        // 避免 JS 解析错误
                        $config_value = json_encode([]);
                        echo '<input type="hidden" name="config_value" value="{}"/>';
                    } else {
                        switch (strtolower($value_type)) {
                            case 'password':
                                echo '<textarea class="layui-textarea" name="config_value" placeholder="' . hide_chars($config_value) . '"></textarea>';
                                break;
                            case 'list':
                                $config_value_arr = [];
                                if (!empty($config_value)) {
                                    foreach ($config_value as $name => $val) {
                                        $config_value_arr[] = [
                                            'name' => $name,
                                            'value' => $name . '=' . $val,
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
                    }
                    ?>
                </div>
            </div>
            <div class="layui-form-item hd-margin-top-30">
                <div class="layui-input-block">
                    <input type="hidden" name="hash_tk" value="<?php echo $csrf_token; ?>"/>
                    <input type="hidden" name="config_group" value="<?php echo strtolower($config_group); ?>"/>
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
            <?php if (strtolower($value_type) == 'list') { ?>
            if ($('#value_select').length == 1) {
                xmSelect.render({
                    el: '#value_select',
                    name: 'config_value',
                    height: 'auto',
                    tips: '请选择配置',
                    data: <?php echo $config_value;?>,
                    filterable: true,
                    searchTips: '请选择配置，若不存在配置请输入新增，名称与值可用等号分隔，如 Name=Value',
                    create: function (val) {
                        if ($.trim(val) == '') {
                            return;
                        }

                        val = val.split('=', 2);
                        if (val.length != 2) {
                            val[1] = val[0];
                        }
                        return {
                            name: $.trim(val[0]),
                            value: $.trim(val[0]) + '=' + $.trim(val[1])
                        };
                    }
                });
            }
            <?php } ?>

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
