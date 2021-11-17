<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <script>
        const lang_codes = <?php echo json_encode($lang_codes);?>;
        const prod_desc_list = <?php echo json_encode($prod_desc_list);?>;

        layui.use('element', function () {
            let element = layui.element;
            for (let lang_idx in lang_codes) {
                let lang_code = lang_codes[lang_idx];
                let prod_name = '';
                let prod_desc = '';
                let prod_desc_m = '';
                let meta_title = '';
                let meta_keywords = '';
                let meta_desc = '';
                if (prod_desc_list[lang_code] != undefined) {
                    prod_name = prod_desc_list[lang_code].product_name;
                    prod_desc = prod_desc_list[lang_code].product_description;
                    prod_desc_m = prod_desc_list[lang_code].product_description_m;
                    meta_title = prod_desc_list[lang_code].meta_title;
                    meta_keywords = prod_desc_list[lang_code].meta_keywords;
                    meta_desc = prod_desc_list[lang_code].meta_description;
                }

                let lay_id = 'prod_desc_' + lang_idx;
                let html = '';
                if (lang_idx == 0) {
                    html += '<div class="layui-form-item">';
                    html += '<label class="layui-form-label">商品名称 <span class="hd-color-red">*</span></label>';
                    html += '<div class="layui-input-block">';
                    html += '<input type="text" class="layui-input" name="prod_name[' + lang_code + ']" maxlength="300"' +
                        ' value="' + prod_name + '" />';
                    html += '</div></div>';
                } else {
                    html += '<div class="layui-form-item">';
                    html += '<label class="layui-form-label">商品名称</label>';
                    html += '<div class="layui-input-block">';
                    html += '<input type="text" class="layui-input" name="prod_name[' + lang_code + ']" maxlength="300"' +
                        ' placeholder="不填则默认为[' + lang_codes[0].toLocaleUpperCase() + ']的商品名称" value="' + prod_name + '" />';
                    html += '</div></div>';
                }
                html += '<div class="layui-form-item">';
                html += '<label class="layui-form-label">商品描述（电脑端）</label>';
                html += '<div class="layui-input-block">';
                html += '<div class="prod_desc" id="prod_desc_' + lang_code + '">' + prod_desc + '</div>';
                html += '<textarea id="textarea_prod_desc_' + lang_code + '" class="layui-hide" name="prod_desc[' + lang_code + ']">' + prod_desc + '</textarea>';
                html += '</div></div>';
                html += '<div class="layui-form-item">';
                html += '<label class="layui-form-label">商品描述（移动端）</label>';
                html += '<div class="layui-input-block">';
                html += '<div class="prod_desc_m" id="prod_desc_m_' + lang_code + '">' + prod_desc_m + '</div>';
                html += '<textarea id="textarea_prod_desc_m_' + lang_code + '" class="layui-hide" name="prod_desc_m[' + lang_code + ']">' + prod_desc_m + '</textarea>';
                html += '</div></div>';
                html += '<div class="layui-form-item">';
                html += '<label class="layui-form-label">Meta 标题</label>';
                html += '<div class="layui-input-block">';
                html += '<input type="text" class="layui-input" name="meta_title[' + lang_code + ']" maxlength="300"' +
                    ' placeholder="用于 SEO 优化，不填则默认为商品名称" value="' + meta_title + '" />';
                html += '</div></div>';
                html += '<div class="layui-form-item">';
                html += '<label class="layui-form-label">Meta 关键词</label>';
                html += '<div class="layui-input-block">';
                html += '<textarea class="layui-textarea" name="meta_keywords[' + lang_code + ']" maxlength="500"' +
                    ' placeholder="用于 SEO 优化">' + meta_keywords + '</textarea>';
                html += '</div></div>';
                html += '<div class="layui-form-item">';
                html += '<label class="layui-form-label">Meta 描述</label>';
                html += '<div class="layui-input-block">';
                html += '<textarea class="layui-textarea" name="meta_desc[' + lang_code + ']" maxlength="1000"' +
                    ' placeholder="用于 SEO 优化">' + meta_desc + '</textarea>';
                html += '</div></div>';
                element.tabAdd('prod_desc_list', {
                    id: lay_id,
                    title: lang_code.toLocaleUpperCase(),
                    content: html
                });
            }
            element.tabChange('prod_desc_list', 'prod_desc_0');
        });
    </script>
    <div class="layui-fluid">
        <form class="layui-form hd-padding-top30 hd-padding-bottom30" method="post" autocomplete="off">
            <div class="layui-form-item">
                <label class="layui-form-label">商品状态</label>
                <div class="layui-input-block">
                    <?php
                    $prod_status = $prod_info['product_status'] ?? 0;
                    switch ((int)$prod_status) {
                        case 1:
                            echo '<input type="radio" name="prod_status" checked value="1" title="上架中"/>';
                            echo '<input type="radio" name="prod_status" value="2" title="下架中"/>';
                            break;
                        case 2:
                            echo '<input type="radio" name="prod_status" value="1" title="上架中"/>';
                            echo '<input type="radio" name="prod_status" checked value="2" title="下架中"/>';
                            break;
                        default:
                            echo '<input type="radio" name="prod_status" checked value="0" title="待处理"/>';
                            break;
                    }
                    ?>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">商品类目</label>
                <div class="layui-input-block">
                    <div id="cate_select"></div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">商品排序</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input hd-int-only" name="prod_sort" maxlength="5"
                           value="<?php echo $prod_info['sort'] ?? ''; ?>"/>
                </div>
                <div class="layui-form-mid layui-word-aux">排序由小到大，不填则默认为0</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">商品 URL</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="prod_url" maxlength="100"
                           placeholder="不填写默认使用商品名称生成"
                           value="<?php echo $prod_info['product_url'] ?? ''; ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">商品重量</label>
                <div class="layui-input-block">
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input hd-float-only" name="weight" maxlength="12"
                               placeholder="不填则默认为0"
                               value="<?php echo $prod_info['weight'] ?? ''; ?>"/>
                    </div>
                    <label class="layui-form-label">重量单位</label>
                    <div class="layui-input-inline">
                        <select name="weight_unit">
                            <option value="">请选择单位</option>
                            <?php
                            if ($weight_units) {
                                foreach ($weight_units as $text => $unit) {
                                    $selected = '';
                                    if (isset($prod_info['weight_unit']) && $unit == $prod_info['weight_unit']) {
                                        $selected = ' selected ';
                                    }
                                    echo '<option value="', xss_text($unit), '"', $selected, '>', xss_text($text), '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="layui-form-mid layui-word-aux">重量不为空时，单位必选</div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">商品尺寸</label>
                <div class="layui-input-block">
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input hd-float-only" name="length" maxlength="12"
                               placeholder="长，不填则默认为0"
                               value="<?php echo $prod_info['length'] ?? ''; ?>"/>
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input hd-float-only" name="width" maxlength="12"
                               placeholder="宽，不填则默认为0"
                               value="<?php echo $prod_info['width'] ?? ''; ?>"/>
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input hd-float-only" name="height" maxlength="12"
                               placeholder="高，不填则默认为0"
                               value="<?php echo $prod_info['height'] ?? ''; ?>"/>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">尺寸单位</label>
                <div class="layui-input-inline">
                    <select name="size_unit">
                        <option value="">请选择单位</option>
                        <?php
                        if ($size_units) {
                            foreach ($size_units as $text => $unit) {
                                $selected = '';
                                if (isset($prod_info['size_unit']) && $unit == $prod_info['size_unit']) {
                                    $selected = ' selected ';
                                }
                                echo '<option value="', xss_text($unit), '"', $selected, '>', xss_text($text), '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">尺寸不为空时，单位必选</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">商品属性组</label>
                <div class="layui-input-inline">
                    <?php
                    if (!empty($attr_group_list)) {
                        foreach ($attr_group_list as $group_id => $attr_group) {
                            $checked = isset($prod_info['attr_group_ids'][$group_id]) ? ' checked ' : '';
                            echo '<input lay-filter="hd-attr-group-bind" type="checkbox" ', $checked, ' value="', $group_id, '" title="', $attr_group['group_name'], '" lay-skin="primary"/>';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    添加 SKU
                    <i class="layui-icon layui-icon-add-circle hd-cursor-pointer hd-color-blue hd-font-size-18"
                       id="btn_add_sku"></i>
                </label>
                <div class="layui-input-block" style="max-height: 600px; overflow-y: scroll;">
                    <table class="layui-table" style="margin: 0;">
                        <thead>
                        <tr>
                            <th class="hd-align-center" width="20%">SKU</th>
                            <th class="hd-align-center">SKU 详情</th>
                            <th class="hd-align-center" width="10%">操作</th>
                        </tr>
                        </thead>
                        <tbody id="sku_list"><?php include 'box_sku_info.php'; ?></tbody>
                    </table>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <div class="layui-tab layui-tab-brief" lay-filter="prod_desc_list">
                        <ul class="layui-tab-title"></ul>
                        <div class="layui-tab-content"></div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item" id="hd-bottom-options">
                <input type="hidden" name="hash_tk" value="<?php echo $csrf_token; ?>"/>
                <input class="layui-btn" type="submit" lay-submit lay-filter="prod_edit"
                       value="<?php echo xss_text('save', true); ?>"/>
                <input class="layui-btn layui-btn-primary hd-layer-close" type="button"
                       value="<?php echo xss_text('cancel', true); ?>"/>
            </div>
        </form>
    </div>
    <script src="/static/wangEditor.min.js"></script>
    <script src="/static/layui/xm-select.js"></script>
    <script>
        xmSelect.render({
            el: '#cate_select',
            filterable: true,
            name: 'category_id',
            height: 'auto',
            tips: '请选择商品分类',
            radio: true,
            clickClose: true,
            model: {
                icon: 'hidden'
            },
            prop: {
                name: 'category_name',
                value: 'product_category_id'
            },
            tree: {
                show: true,
                strict: false,
                expandedKeys: true,
                showLine: false
            },
            initValue: [<?php echo $prod_info['product_category_id'] ?? 0;?>],
            data: <?php echo json_encode($cate_tree_list);?>
        });
    </script>
    <script>
        var editors = {};
        var mEditors = {};
        const attr_group_list = <?php echo json_encode($attr_group_list)?>;
        const attr_value_list = <?php echo json_encode($attr_value_list)?>;
        layui.use(['form', 'jquery'], function () {
            if ($('.prod_desc').length > 0) {
                $('.prod_desc').each(function () {
                    let id = $(this).attr('id');
                    let lang = id.replace('prod_desc_', '');

                    editors[lang] = new wangEditor('#' + id);
                    editors[lang].config.onchange = function (html) {
                        $('#textarea_' + id).val(html);
                    };
                    editors[lang].create();
                });
            }

            if ($('.prod_desc_m').length > 0) {
                $('.prod_desc_m').each(function () {
                    let id = $(this).attr('id');
                    let lang = id.replace('prod_desc_m_', '');

                    mEditors[lang] = new wangEditor('#' + id);
                    mEditors[lang].config.placeholder = '不填则默认为商品描述（电脑端）';
                    mEditors[lang].config.onchange = function (html) {
                        $('#textarea_' + id).val(html);
                    };
                    mEditors[lang].create();
                });
            }

            hdImg.init({
                elem: '.sku_images',
                url: '/spadmin/upload-image',
                initFolders: <?php echo json_encode($upload_folders);?>,
                imgSelectCallback: function () {
                    if (hdImg.imgSelected.length == 0) {
                        layer.alert('请选择图片', hdImg.openAlertCfg);
                        return;
                    }

                    let imgHtml = '<div style="position: relative; display: inline-block; margin: 10px; border: 1px solid #ccc;">' +
                        '-INPUT-HIDDEN-<img src="-IMG-SRC-" /><i class="layui-icon layui-icon-close-fill hd-btn-del-image" ' +
                        ' style="position: absolute; top: 0; right: 0; font-size: 20px; color: #FF5722; cursor: pointer;"></i></div>';


                    for (let idx in hdImg.imgSelected) {
                        if ($('#list_img_' + hdImg.imgBoxIdx + ' img').length >= 10) {
                            layer.alert('每项最多可添加10张图片', hdImg.openAlertCfg);
                            break;
                        }

                        if ($('#list_img_' + hdImg.imgBoxIdx + ' img[src="' + hdImg.imgSelected[idx] + '"]').length == 0) {
                            let inputName = $('input.hd-input-sku').eq(hdImg.imgBoxIdx).attr('name')
                                .replace('[sku]', '[image][' + $('#list_img_' + hdImg.imgBoxIdx).find('img').length + ']');

                            $('#list_img_' + hdImg.imgBoxIdx).append(
                                imgHtml.replace('-IMG-SRC-', hdImg.imgSelected[idx])
                                    .replace('-INPUT-HIDDEN-', '<input type="hidden" name="' + inputName + '" value="' + hdImg.imgSelected[idx] + '" />')
                            );
                        }
                    }

                    if (hdImg.layerIdx != null) {
                        layer.close(hdImg.layerIdx);
                    }
                }
            });

            function get_sku_attr_html(group_id, sku) {
                let attr_values = attr_value_list[group_id];
                if (attr_values == undefined || attr_group_list[group_id] == undefined) {
                    return '';
                }

                let attr_html = '<div class="layui-form-item hd-attr-group-' + group_id + '">' +
                    '<label class="layui-form-label">属性组：<span>' + attr_group_list[group_id]['group_name'] + '</span></label>' +
                    '<div class="layui-input-inline">' +
                    '<select class="hd-attr-value" name="sku_data[' + sku + '][attr_values][' + group_id + ']">' +
                    '<option value="0">请选择属性值</option>';

                for (let k in attr_values) {
                    attr_html += '<option value="' + attr_values[k]['attr_value_id'] + '">' + attr_values[k]['value_name'] + '</option>';
                }
                attr_html += '</select></div></div>';

                return attr_html;
            }

            $('#btn_add_sku').click(function () {
                let sku_idx = $('#sku_list tr').get().length;
                let attr_html = '';
                $('input[lay-filter="hd-attr-group-bind"]:checked').each(function () {
                    attr_html += get_sku_attr_html($(this).val(), sku_idx);
                });

                let sku_html = $('#tpl_sku_info').html().replaceAll('-IDX-', sku_idx).replace('-ATTR-LIST-', attr_html);
                $('#sku_list').append(sku_html);

                layui.form.render('select');
                hdImg.init();
            });
            $(document).on('click', '.btn_del_sku', function () {
                $(this).parent('td').parent('tr').remove();
            });

            layui.form.on('checkbox(hd-attr-group-bind)', function (data) {
                if (data.elem.checked) {
                    $('.hd-attr-list').each(function () {
                        $(this).append(get_sku_attr_html(data.value, $(this).data('sku')));
                    });
                } else {
                    $('.hd-attr-group-' + data.value).remove();
                }
                layui.form.render('select');
            });

            layui.form.on('submit(prod_edit)', function (formObj) {
                form_submit(window.location.href, formObj.field);
                return false;
            });
        });
    </script>
<?php
include 'tpl_sku_info.php';
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
