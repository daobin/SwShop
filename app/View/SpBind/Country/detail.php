<?php
\App\Helper\TemplateHelper::widget('sp_bind', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-bottom30">
        <form class="layui-form hd-margin-top-30" method="post" autocomplete="off">
            <div class="layui-form-item">
                <label class="layui-form-label">国家名称</label>
                <div class="layui-input-inline hd-width-500">
                    <input type="text" class="layui-input" name="name" maxlength="30"
                           value="<?php echo $country_info['country_name'] ?? ''; ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">国家编码 2位</label>
                <div class="layui-input-inline hd-width-100">
                    <input type="text" class="layui-input" name="code2" maxlength="2"
                           value="<?php echo $country_info['iso_code_2'] ?? ''; ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">国家编码 3位</label>
                <div class="layui-input-inline hd-width-100">
                    <input type="text" class="layui-input" name="code3" maxlength="3"
                           value="<?php echo $country_info['iso_code_3'] ?? ''; ?>"/>
                </div>
            </div>
            <?php if (!empty($country_info)) { ?>
                <div class="layui-form-item">
                    <label class="layui-form-label">洲省地区</label>
                    <div class="layui-input-block" id="hd-zone-list" style="color: #888;">
                        <button class="layui-btn layui-btn-sm layui-btn-warm layui-btn-radius">在线同步更新</button>
                        <div class="layui-row hd-margin-top-30" style="max-height: 300px;">
                            <?php
                            if (!empty($zone_list)) {
                                foreach ($zone_list as $zone) {
                                    echo '<div class="layui-col-xs2">* ', $zone['zone_name'], ' ( ', $zone['zone_code'], ' )</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="layui-form-item hd-margin-top-30">
                <div class="layui-input-block">
                    <input type="hidden" name="hash_tk" value="<?php echo $csrf_token ?? ''; ?>"/>
                    <input class="layui-btn" type="submit" lay-submit lay-filter="admin_edit"
                           value="<?php echo xss_text('save', true); ?>"/>
                    <input class="layui-btn layui-btn-primary hd-layer-close" type="button"
                           value="<?php echo xss_text('cancel', true); ?>"/>
                </div>
            </div>
        </form>
    </div>
    </div>
    <script>
        layui.use(['form', 'jquery'], function () {
            layui.form.on('submit(admin_edit)', function (formObj) {
                form_submit(window.location.href, formObj.field);

                return false;
            });

            $('#hd-zone-list>button').click(function () {
                $.ajax({
                    type: 'post',
                    url: '/spbind/synczones',
                    data: {
                        country_code: $('input[name="code2"]').val(),
                        hash_tk: $('input[name="hash_tk"]').val()
                    },
                    success: function (res) {
                        console.log(res);
                        if (res.msg != undefined && res.msg != '') {
                            layer.alert(res.msg, open_alert_cfg);
                        }

                        if (res.status == 'success' && res.zone_list != undefined) {
                            $('#hd-zone-list>div').html('');
                            for (let zone_code in res.zone_list) {
                                $('#hd-zone-list>div').append('<div class="layui-col-xs2">* ' + res.zone_list[zone_code] + ' ( ' + zone_code + ' )</div>');
                            }
                        }
                    },
                    error: function () {
                        layer.alert('未知错误，请稍候刷新页面重试！', open_alert_cfg);
                    }
                });
                return false;
            });
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_bind', 'footer');