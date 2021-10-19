<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid">
        <form id="hd-form-cfg" class="layui-form hd-margin-top-30" method="post" autocomplete="off">
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
                    $config_value = $config_value ?? '';
                    switch (strtolower($value_type)) {
                        case 'image':
                            echo '<div><img src="', $oss_access_host, $config_value, '"/></div>';
                            echo '<div class="hd-margin-top-30"><input type="file" name="file"/></div>';
                            break;
                        case 'radio':
                            if ($config_value == 'open') {
                                echo '<input type="checkbox" name="config_value" lay-skin="switch" lay-text="开启|关闭" checked/>';
                            } else {
                                echo '<input type="checkbox" name="config_value" lay-skin="switch" lay-text="开启|关闭"/>';
                            }
                            break;
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
                            if ($config_key == 'TIMEZONE') {
                                echo '<select class="layui-select" name="config_value">';
                                $timezones = get_timezones();
                                foreach ($timezones as $timezone => $timezoneText) {
                                    $selected = $timezone == $config_value ? ' selected ' : '';
                                    echo '<option ', $selected, ' value="', $timezone, '">', $timezoneText, '</option>';
                                }
                                echo '</select>';
                            } else {
                                echo '<textarea class="layui-textarea" name="config_value">' . $config_value . '</textarea>';
                            }
                    }
                    ?>
                </div>
            </div>
            <div class="layui-form-item hd-margin-top-30">
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
        layui.use(['form', 'jquery'], function () {
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
                switch ($.trim(formObj.field.value_type)) {
                    case 'image':
                        if ($.trim($('input[name="file"]').val()) == '') {
                            layer.alert('请选择站点 LOGO', open_alert_cfg);
                        } else {
                            var formData = new FormData(document.getElementById('hd-form-cfg'));
                            $.ajax({
                                type: 'post',
                                url: window.location.href,
                                data: formData,
                                cache: false,
                                processData: false,
                                contentType: false,
                                success: function (res) {
                                    if (res.msg != undefined && res.msg != '') {
                                        layer.alert(res.msg, open_alert_cfg, function (idx) {
                                            if (res.url != undefined && res.url != '') {
                                                window.location.href = res.url;
                                            } else if (res.status == 'success') {
                                                // 由于保存操作是在子窗口进行，所以在父窗口刷新页面
                                                parent.location.reload(true);
                                            } else {
                                                layer.close(idx);
                                            }
                                        });
                                    } else {
                                        if (res.url != undefined && res.url != '') {
                                            window.location.href = res.url;
                                        } else if (res.status == 'success') {
                                            // 由于保存操作是在子窗口进行，所以在父窗口刷新页面
                                            parent.location.reload(true);
                                        }
                                    }
                                },
                                error: function () {
                                    layer.alert('未知错误，请稍候刷新页面重试！', open_alert_cfg);
                                }
                            });
                        }
                        break;
                    case 'radio':
                        if ($.trim(formObj.field.config_value) == '') {
                            formObj.field.config_value = 'close';
                        } else {
                            formObj.field.config_value = 'open';
                        }
                        form_submit(window.location.href, formObj.field);
                        break;
                    default:
                        if ($.trim(formObj.field.config_value) == '') {
                            layer.confirm('配置值是否保存为空？', open_ask_cfg, function (idx) {
                                form_submit(window.location.href, formObj.field);
                                layer.close(idx);
                            });
                        } else {
                            form_submit(window.location.href, formObj.field);
                        }
                        break;
                }

                return false;
            });
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
