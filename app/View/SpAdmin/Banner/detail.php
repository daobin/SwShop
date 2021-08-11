<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid">
        <form class="layui-form hd-margin-top30" method="post" autocomplete="off">
            <div class="layui-form-item">
                <label class="layui-form-label">广告项目</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" disabled
                           value="<?php echo xss_text($banner_info['title']); ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">广告编码</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" disabled
                           value="<?php echo xss_text($banner_info['code']); ?>"/>
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
                    <div class="layui-fluid">
                        <div class="layui-row">
                            <div class="layui-col-xs9" id="list_img_0">
                                <?php
                                if (!empty($banner_info['image_list'])) {
                                    foreach ($banner_info['image_list'] as $sort => $image_info) {
                                        $image_src = $oss_access_host.$image_info['image_path'].'/'.$image_info['image_name'];
                                        ?>
                                        <div class="layui-row hd-padding-bottom30">
                                            <div class="layui-col-xs3">
                                                <input type="hidden" name="image_list[<?php echo $sort; ?>]" value="<?php echo $image_src; ?>"/>
                                                <img style="display: block; width: 100%;" src="<?php echo $image_src; ?>"/>
                                            </div>
                                            <div class="layui-col-xs9">
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label">跳转链接</label>
                                                    <div class="layui-input-block">
                                                        <input type="text" class="layui-input"
                                                               name="link[<?php echo $sort; ?>]" value="<?php echo xss_text($image_info['window_link']);?>"/>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label">新窗口打开</label>
                                                    <div class="layui-input-block">
                                                        <?php
                                                        if (empty($image_info['is_new_window'])) {
                                                            echo '<input type="checkbox" name="is_new[', $sort, ']" lay-skin="switch" lay-text="是|否"/>';
                                                        } else {
                                                            echo '<input type="checkbox" name="is_new[', $sort, ']" lay-skin="switch" lay-text="是|否" checked/>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label">
                                                        <i class="layui-icon layui-icon-delete btn_del_image"> 删除</i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="layui-col-xs3" style="text-align: right;">
                                <a class="layui-btn layui-btn-warm hd-btn-open-image">
                                    <i class="layui-icon layui-icon-share"></i> 选择商品图片
                                </a>
                            </div>
                        </div>
                    </div>
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
    <script type="text/html" id="tpl_img_info">
        <div class="layui-row hd-padding-bottom30">
            <div class="layui-col-xs3">
                <input type="hidden" name="image_list[0]" value="-IMG-SRC-"/>
                <img style="display: block; width: 100%;" src="-IMG-SRC-"/>
            </div>
            <div class="layui-col-xs9">
                <div class="layui-form-item">
                    <label class="layui-form-label">跳转链接</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" name="link[0]" value=""/>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">新窗口打开</label>
                    <div class="layui-input-block">
                        <?php
                        if (empty($banner_info['is_new_window'])) {
                            echo '<input type="checkbox" name="is_new[0]" lay-skin="switch" lay-text="是|否"/>';
                        } else {
                            echo '<input type="checkbox" name="is_new[0]" lay-skin="switch" lay-text="是|否" checked/>';
                        }
                        ?>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <i class="layui-icon layui-icon-delete btn_del_image"> 删除</i>
                    </label>
                </div>
            </div>
        </div>
    </script>
    <script>
        layui.use(['form'], function () {
            hdImg.init({
                elem: '#image_list',
                url: '/spadmin/upload-image',
                uploaderSize: 1020,
                initFolders: JSON.parse('<?php echo json_encode($upload_folders);?>'),
                imgSelectCallback: function () {
                    if (hdImg.imgSelected.length == 0) {
                        layer.alert('请选择图片', hdImg.openAlertCfg);
                        return;
                    }

                    for (let idx in hdImg.imgSelected) {
                        if ($('#list_img_' + hdImg.imgBoxIdx + ' img').length >= 10) {
                            layer.alert('每项最多可添加10张图片', hdImg.openAlertCfg);
                            break;
                        }

                        if ($('#list_img_' + hdImg.imgBoxIdx + ' img[src="' + hdImg.imgSelected[idx] + '"]').length == 0) {
                            let imgHtml = $.trim($('#tpl_img_info').html()).replaceAll('[0]', '[' + hdImg.imgBoxIdx + ']');
                            $('#list_img_' + hdImg.imgBoxIdx).append(imgHtml.replaceAll('-IMG-SRC-', hdImg.imgSelected[idx]));
                        }
                    }

                    layui.form.render('checkbox');

                    if (hdImg.layerIdx != null) {
                        layer.close(hdImg.layerIdx);
                    }
                }
            });

            $(document).on('click', 'i.btn_del_image', function () {
                let idx = $('i.btn_del_image').index($(this));
                $('#list_img_0>div').eq(idx).remove();
            });

            layui.form.on('submit(banner_edit)', function (formObj) {
                form_submit(window.location.href, formObj.field);
                return false;
            });
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
