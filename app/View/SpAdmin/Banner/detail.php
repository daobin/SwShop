<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid">
        <form class="layui-form hd-margin-top30" method="post" autocomplete="off">
            <div class="layui-form-item">
                <label class="layui-form-label">广告项目</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" disabled value="<?php echo xss_text($banner_info['title']); ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">广告编码</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" disabled value="<?php echo xss_text($banner_info['code']); ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">广告状态</label>
                <div class="layui-input-block">
                    <?php
                    if (empty($banner_info['banner_status'])) {
                        echo '<input type="checkbox" name="banner_status" lay-skin="switch" lay-text="开启|关闭"/>';
                    } else {
                        echo '<input type="checkbox" name="banner_status" lay-skin="switch" lay-text="开启|关闭" checked/>';
                    }
                    ?>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">广告图片</label>
                <div class="layui-input-block" id="image_list">

                </div>
            </div>
            <div class="layui-form-item hd-margin-top30">
                <div class="layui-input-block">
                    <input type="hidden" name="hash_tk" value="<?php echo $csrf_token; ?>"/>
                    <input class="layui-btn" type="submit" lay-submit lay-filter="banner_edit"
                           value="<?php echo xss_text('save', true); ?>"/>
                    <input class="layui-btn layui-btn-primary hd-layer-close" type="button"
                           value="<?php echo xss_text('cancel', true); ?>"/>
                </div>
            </div>
        </form>
    </div>
    <script>
        layui.use(['form'], function () {
            hdImg.init({
                elem: '#image_list',
                url: '/spadmin/upload-image',
                uploaderSize: 1020,
                initFolders: JSON.parse('<?php echo json_encode($upload_folders);?>')
            });

            layui.form.on('submit(banner_edit)', function (formObj) {
                form_submit(window.location.href, formObj.field);
                return false;
            });
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
