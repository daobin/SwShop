<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid">
        <form class="layui-form hd-margin-top-30" method="post" autocomplete="off">
            <div class="layui-form-item">
                <label class="layui-form-label">国家</label>
                <div class="layui-input-block">
                    <select name="country_id">
                        <option value="">请选择国家</option>
                        <?php
                        if ($country_list) {
                            foreach ($country_list as $id => $country) {
                                if ($country_id == $id) {
                                    echo '<option value="', $id, '" selected>', $country['country_name'], '</option>';
                                } else {
                                    echo '<option value="', $id, '">', $country['country_name'], '</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">风险国家</label>
                <div class="layui-input-block">
                    <?php
                    $is_high_risk = $country_info['is_high_risk'] ?? 0;
                    if ((int)$is_high_risk == 1) {
                        echo '<input type="checkbox" name="is_high_risk" lay-skin="switch" lay-text="是|否" checked/>';
                    } else {
                        echo '<input type="checkbox" name="is_high_risk" lay-skin="switch" lay-text="是|否"/>';
                    }
                    ?>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input hd-int-only" name="sort" maxlength="5"
                           value="<?php echo $country_info['sort'] ?? ''; ?>"/>
                </div>
                <div class="layui-form-mid layui-word-aux">排序由小到大，不填则默认为0</div>
            </div>
            <div class="layui-form-item hd-margin-top-30">
                <div class="layui-input-block">
                    <input type="hidden" name="hash_tk" value="<?php echo $csrf_token; ?>"/>
                    <input class="layui-btn" type="submit" lay-submit lay-filter="country_edit"
                           value="<?php echo xss_text('save', true); ?>"/>
                    <input class="layui-btn layui-btn-primary hd-layer-close" type="button"
                           value="<?php echo xss_text('cancel', true); ?>"/>
                </div>
            </div>
        </form>
    </div>

    <script>
        layui.use(['form'], function () {
            layui.form.on('submit(country_edit)', function (formObj) {
                form_submit(window.location.href, formObj.field);

                return false;
            });
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
