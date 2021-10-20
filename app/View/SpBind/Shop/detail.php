<?php
$redirect_code = $shop_info['shop_domain2_redirect_code'] ?? 0;
\App\Helper\TemplateHelper::widget('sp_bind', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-bottom30">
        <form class="layui-form hd-margin-top-30" method="post" autocomplete="off">
            <div class="layui-form-item">
                <label class="layui-form-label">
                    店铺名称
                    <span class="layui-font-red">*</span>
                </label>
                <div class="layui-input-inline hd-width-500">
                    <input type="text" class="layui-input" name="shop_name" maxlength="100"
                           value="<?php echo $shop_info['shop_name'] ?? ''; ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    主域名
                    <span class="layui-font-red">*</span>
                </label>
                <div class="layui-input-inline hd-width-500">
                    <input type="text" class="layui-input" name="shop_domain" maxlength="100"
                           value="<?php echo $shop_info['shop_domain'] ?? ''; ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">第2主域名</label>
                <div class="layui-input-inline hd-width-500">
                    <input type="text" class="layui-input" name="shop_domain2" maxlength="100"
                           value="<?php echo $shop_info['shop_domain2'] ?? ''; ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">第2主域名是否跳转主域名</label>
                <div class="layui-input-inline hd-width-500">
                    <select class="layui-select" name="redirect_code">
                        <option <?php echo $redirect_code == 0 ? ' selected ' : ''; ?> value="0">不跳转</option>
                        <option <?php echo $redirect_code == 301 ? ' selected ' : ''; ?> value="301">永久跳转</option>
                        <option <?php echo $redirect_code == 302 ? ' selected ' : ''; ?> value="302">临时跳转</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">店铺状态</label>
                <div class="layui-input-block">
                    <?php
                    $shop_status = $shop_info['shop_status'] ?? 0;
                    if ($shop_status == 1) {
                        echo '<input type="radio" name="shop_status" checked value="1" title="开启"/>';
                        echo '<input type="radio" name="shop_status" value="0" title="关闭"/>';
                    } else {
                        echo '<input type="radio" name="shop_status" value="1" title="开启"/>';
                        echo '<input type="radio" name="shop_status" checked value="0" title="关闭"/>';
                    }
                    ?>
                </div>
            </div>
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
        layui.use(['form'], function () {
            layui.form.on('submit(admin_edit)', function (formObj) {
                form_submit(window.location.href, formObj.field);

                return false;
            });
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_bind', 'footer');