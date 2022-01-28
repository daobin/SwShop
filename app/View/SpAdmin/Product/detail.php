<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <style>
        .layui-table-view {
            margin: 0 !important;
        }

        .sku-images img {
            width: 80px;
        }

        .sortable-placeholder {
            width: 250px;
            height: 50px;
            border: 1px dashed #FFB800;
        }
    </style>
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
    <div class="layui-fluid" id="prod-edit">
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
                <label class="layui-form-label">商品主图</label>
                <div class="layui-input-block">
                    <div class="sku-images">
                        <div class="layui-fluid">
                            <div class="layui-col-xs9" id="list_img_0">

                                <?php
                                if (!empty($prod_image_list)) {
                                    foreach ($prod_image_list as $img_sort => $prod_image) {
                                        $prod_img_src = str_replace('_d_d', '_300_300', $prod_image['image_name']) . '?' . $prod_image['updated_at'];
                                        ?>
                                        <div style="position: relative; display: inline-block; margin: 10px; border: 1px solid #ccc;">
                                            <input type="hidden" name="prod_images[]"
                                                   value="<?php echo $prod_image['image_path'] . '/' . $prod_image['image_name']; ?>"/>
                                            <img src="<?php echo $oss_access_host . $prod_image['image_path'] . '/' . $prod_img_src; ?>"/>
                                            <i class="layui-icon layui-icon-close-fill hd-btn-del-image"
                                               style="position: absolute; top: 0; right: 0; font-size: 20px; color: #FF5722; cursor: pointer;"></i>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="layui-col-xs3" style="text-align: right;">
                                <a class="layui-btn layui-btn-warm hd-btn-open-image">
                                    <i class="layui-icon layui-icon-share"></i> 选择商品图片</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">商品属性</label>
                <div class="layui-input-block">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <a class="layui-btn layui-btn-sm layui-btn-radius layui-btn-normal"
                               id="add-attr-group">添加属性</a>
                        </div>
                        <div class="layui-card-body" id="attr-list-box">
                            <?php
                            if (!empty($attr_group_list) && !empty($attr_value_list)) {
                                $grp_idx = 1;
                                foreach ($attr_value_list as $group_id => $val_list) {
                                    ?>
                                    <div class="hd-padding-bottom30 attr-list">
                                        <div class="layui-form-item">
                                            <label>属性 <?php echo $grp_idx; ?></label>
                                            <div class="hd-inline-block hd-width-150">
                                                <select class="attr-group-select" lay-filter="attr-group"
                                                        name="attr_group_ids[]">
                                                    <option value="0">请选择属性</option>
                                                    <?php
                                                    foreach ($attr_group_list as $grp_id => $grp_info) {
                                                        $selected = $grp_id == $group_id ? ' selected ' : '';
                                                        echo '<option ', $selected, ' value="', $grp_id, '">', $grp_info['group_name'], '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <?php
                                            if ($grp_idx == 1) {
                                                $checked = empty($attr_image_list[$group_id]) ? '' : 'checked ';
                                                echo '&nbsp;&nbsp;<input type="checkbox" id="add-attr-img" title="添加图片" lay-skin="primary" ', $checked, ' lay-filter="add-attr-img"/>';
                                            } else {
                                                echo '&nbsp;&nbsp;<a class="hd-color-blue del-attr-group">删除属性</a>';
                                            }
                                            ?>
                                        </div>
                                        <hr/>
                                        <div class="attr-list-box hd-inline-block">
                                            <?php
                                            $val_list = array_unique($val_list);
                                            foreach ($val_list as $attr_value) {
                                                ?>
                                                <div class="hd-inline-block hd-margin-right-30">
                                                    <input class="layui-input hd-inline-block hd-margin-right-10 hd-width-150 attr-value"
                                                           type="text" name="attr_list[<?php echo $grp_idx; ?>][]"
                                                           placeholder="请输入属性值"
                                                           value="<?php echo $attr_value; ?>"/>
                                                    <?php
                                                    if ($grp_idx == 1 && !empty($attr_image_list[$group_id])) {
                                                        echo '<a class="attr-img" data-groupid="', $group_id, '">';
                                                        if (empty($attr_image_list[$group_id][$attr_value])) {
                                                            echo '<i class="layui-icon layui-icon-picture"></i></a>';
                                                        } else {
                                                            $attr_img_src = str_replace('_d_d', '_100_100', $attr_image_list[$group_id][$attr_value]);
                                                            $attr_img_src = $oss_access_host . $attr_img_src;
                                                            echo '<img src="', $attr_img_src, '" style="width: 30px;" />';
                                                            echo '<input type="hidden" name="attr_images[', $group_id, '][', $attr_value, ']" value="', $attr_img_src, '" /></a>';
                                                        }
                                                    }
                                                    ?>
                                                    &nbsp;<a class="hd-color-blue move-attr-value">移动</a>
                                                    &nbsp;<a class="hd-color-blue del-attr-value">删除</a>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <input class="layui-input hd-inline-block hd-width-150 add-attr-value"
                                               type="text" data-grpidx="<?php echo $grp_idx; ?>"
                                               placeholder="请输入属性值"/>
                                    </div>
                                    <?php
                                    $grp_idx++;
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    商品列表
                </label>
                <div class="layui-input-block" style="max-height: 600px; overflow-y: scroll;">
                    <table id="sku_table" lay-filter="sku_table"></table>
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
    <script type="text/html" id="attr-tpl">
        <div class="hd-padding-bottom30 attr-list">
            <div class="layui-form-item">
                <label>属性 __IDX__</label>
                <div class="hd-inline-block hd-width-150">
                    <select class="attr-group-select" lay-filter="attr-group" name="attr_group_ids[]">
                        <option value="0">请选择属性</option>
                        <?php
                        foreach ($attr_group_list as $grp_id => $grp_info) {
                            echo '<option value="', $grp_id, '">', $grp_info['group_name'], '</option>';
                        }
                        ?>
                    </select>
                </div>
                __OPT__
            </div>
            <hr/>
            <div class="attr-list-box hd-inline-block"></div>
            <input class="layui-input hd-inline-block hd-width-150 add-attr-value"
                   type="text" data-grpidx="__IDX__" placeholder="请输入属性值"/>
        </div>
    </script>
    <script src="/static/wangEditor.min.js"></script>
    <script src="/static/layui/xm-select.js"></script>
    <script src="/static/jquery/jquery-3.6.0.min.js"></script>
    <script src="/static/jquery/jquery-ui.min.js"></script>
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

        var editors = {};
        var mEditors = {};
        const attr_group_list = <?php echo json_encode($attr_group_list)?>;
        const attr_value_list = <?php echo json_encode($attr_value_list)?>;
        const attr_image_list = <?php echo json_encode($attr_image_list)?>;
        const qty_price_list = <?php echo json_encode($qty_price_list)?>;

        var sku_data_obj = {};
        for (let grp_id in attr_value_list) {
            let grp_name = attr_group_list[grp_id].group_name;
            for (let sku in attr_value_list[grp_id]) {
                if (sku_data_obj[sku] == undefined) {
                    sku_data_obj[sku] = {};
                }
                sku_data_obj[sku][grp_name] = attr_value_list[grp_id][sku];
            }
        }
        for (let sku in qty_price_list) {
            if (sku_data_obj[sku] == undefined) {
                continue;
            }
            sku_data_obj[sku]['qty'] = qty_price_list[sku]['-'].qty;
            sku_data_obj[sku]['price'] = qty_price_list[sku]['-'].price;
            sku_data_obj[sku]['list_price'] = qty_price_list[sku]['-'].list_price;
        }

        var save_skus = [];
        layui.use(['form', 'table'], function () {
            layui.table.render({
                id: 'sku_table',
                elem: '#sku_table',
                cols: [],
                data: [],
                done: function (res, curr, count) {
                    $("[data-field='lowerHairPath']").css('display', 'none');
                    $(".layui-table-main tr").each(function (index, val) {
                        $($(".layui-table-fixed-l .layui-table-body tbody tr")[index]).height($(val).height());
                        $($(".layui-table-fixed-r .layui-table-body tbody tr")[index]).height($(val).height());
                    });
                    //动态监听表头高度变化，冻结行跟着改变高度
                    $(".layui-table-header tr").resize(function () {
                        $(".layui-table-header tr").each(function (index, val) {
                            $($(".layui-table-fixed .layui-table-header table tr")[index]).height($(val).height());
                        });
                    });
                    //初始化高度，使得冻结行表头高度一致
                    $(".layui-table-header tr").each(function (index, val) {
                        $($(".layui-table-fixed .layui-table-header table tr")[index]).height($(val).height());
                    });
                    //动态监听表体高度变化，冻结行跟着改变高度
                    $(".layui-table-body tr").resize(function () {
                        $(".layui-table-body tr").each(function (index, val) {
                            $($(".layui-table-fixed .layui-table-body table tr")[index]).height($(val).height());
                        });
                    });
                    //初始化高度，使得冻结行表体高度一致
                    $(".layui-table-body tr").each(function (index, val) {
                        $($(".layui-table-fixed .layui-table-body table tr")[index]).height($(val).height());
                    });

                    save_skus = [];
                    let sku_data = res.data;
                    sku_data.forEach(function (dt) {
                        save_skus.push(dt.sku);
                    })
                }
            });

            function reset_sku_list() {
                let sku_cols = [];

                sku_cols.push({
                    field: 'sku',
                    title: 'SKU',
                    fixed: 'left',
                    width: 200
                });

                let attr_list_bind = [];
                $('select.attr-group-select').each(function (i) {
                    let grp_id = $(this).val();
                    if (grp_id == '0') {
                        $('.attr-list:eq(' + i + ') input.attr-value').parent('div').remove();
                        return;
                    }

                    let attr_name = $(this).find('option:selected').text();
                    let attr_bind = [];
                    let add_col = false;
                    $('.attr-list:eq(' + i + ') input.attr-value').each(function () {
                        let attr_value = $.trim($(this).val());
                        if (attr_value == '') {
                            return;
                        }

                        add_col = true;
                        attr_bind.push(attr_name + '_' + attr_value);
                    });
                    if (add_col) {
                        sku_cols.push({
                            field: attr_name,
                            title: attr_name,
                            width: 200
                        });
                        if (attr_list_bind.length == 0) {
                            attr_list_bind = attr_bind;
                        } else {
                            let attr_list_bind_new = [];
                            let attr_list_bind_temp = attr_list_bind.slice();
                            for (let idx in attr_list_bind) {
                                for (let idx2 in attr_bind) {
                                    attr_list_bind_new.push(attr_list_bind_temp[idx] + '_' + attr_bind[idx2]);
                                }
                            }
                            attr_list_bind = attr_list_bind_new.slice();
                        }
                    }
                });
                sku_cols.push({
                    field: 'qty',
                    title: '数量',
                    width: 120,
                    templet: function (dt) {
                        return '<input type="text" class="layui-input hd-int-only sku-qty" data-sku="' + dt.sku + '" value="' + dt.qty + '" />';
                    }
                });
                sku_cols.push({
                    field: 'price',
                    title: '售价（<?php echo $currency['symbol_left'] . $currency['symbol_right'];?>）',
                    width: 150,
                    templet: function (dt) {
                        return '<input type="text" class="layui-input hd-float-only sku-price" data-sku="' + dt.sku + '" value="' + dt.price + '" />';
                    }
                });
                sku_cols.push({
                    field: 'list_price',
                    title: '市场价（<?php echo $currency['symbol_left'] . $currency['symbol_right'];?>）',
                    width: 150,
                    templet: function (dt) {
                        return '<input type="text" class="layui-input hd-float-only sku-list-price" data-sku="' + dt.sku + '" value="' + dt.list_price + '" />';
                    }
                });

                let sku_data_list = [];
                if (attr_list_bind.length > 0) {
                    attr_list_bind.forEach(function (attr_bind) {
                        let has_sku = false;
                        if (Object.keys(sku_data_obj).length > 0) {
                            for (let sku in sku_data_obj) {
                                let bind_str = [];
                                for (let attr_name in sku_data_obj[sku]) {
                                    if (['qty', 'price', 'list_price'].indexOf(attr_name) > -1) {
                                        continue;
                                    }
                                    bind_str.push(attr_name + '_' + sku_data_obj[sku][attr_name]);
                                }
                                if (attr_bind == bind_str.join('_')) {
                                    has_sku = true;
                                    let sku_data = {sku: sku};
                                    for (let idx in sku_cols) {
                                        let field = sku_cols[idx].field;
                                        if (sku_data_obj[sku][field] != undefined) {
                                            sku_data[field] = sku_data_obj[sku][field];
                                        }
                                    }
                                    sku_data.qty = sku_data_obj[sku].qty;
                                    sku_data.price = sku_data_obj[sku].price;
                                    sku_data.price = sku_data_obj[sku].price;
                                    sku_data_list.push(sku_data);
                                }
                            }
                        }

                        if (has_sku) {
                            return;
                        }

                        let attr_bind_arr = attr_bind.split('_').slice();

                        let sku = 'T' + Math.floor(Math.random() * 1000000000000);
                        let sku_data = {sku: sku};
                        for (let bind_idx in attr_bind_arr) {
                            bind_idx = parseInt(bind_idx);
                            if (bind_idx % 2 == 0) {
                                for (let idx in sku_cols) {
                                    let field = sku_cols[idx].field;
                                    if (attr_bind_arr[bind_idx] == field) {
                                        sku_data[field] = attr_bind_arr[bind_idx + 1];
                                        if (sku_data_obj[sku] == undefined) {
                                            sku_data_obj[sku] = {};
                                        }
                                        sku_data_obj[sku][field] = attr_bind_arr[bind_idx + 1];
                                    }
                                }
                            }
                        }
                        sku_data.qty = '';
                        sku_data.price = '';
                        sku_data.list_price = '';
                        sku_data_list.push(sku_data);

                        sku_data_obj[sku].qty = '';
                        sku_data_obj[sku].price = '';
                        sku_data_obj[sku].list_price = '';
                    });
                }

                layui.table.reload('sku_table', {
                    cols: [sku_cols],
                    data: sku_data_list
                });
            }

            reset_sku_list();

            $('.attr-list-box').sortable({
                handle: '.move-attr-value',
                containment: 'parent',
                placeholder: 'sortable-placeholder',
                update: function () {
                    reset_sku_list();
                }
            });

            $(document).on('blur', '.sku-qty', function () {
                let sku = $(this).data('sku');
                if (sku_data_obj[sku] != undefined) {
                    sku_data_obj[sku].qty = $(this).val();
                }
            });

            $(document).on('blur', '.sku-price', function () {
                let sku = $(this).data('sku');
                if (sku_data_obj[sku] != undefined) {
                    sku_data_obj[sku].price = $(this).val();
                }
            });

            $(document).on('blur', '.sku-list-price', function () {
                let sku = $(this).data('sku');
                if (sku_data_obj[sku] != undefined) {
                    sku_data_obj[sku].list_price = $(this).val();
                }
            });

            layui.form.on('checkbox(add-attr-img)', function (data) {
                if (data.elem.checked) {
                    let first_grp_id = $.trim($('.attr-group-select:eq(0)').val());
                    let attr_img = '<a class="attr-img" data-groupid="' + first_grp_id + '"><i class="layui-icon layui-icon-picture"></i></a>';
                    $('.attr-list-box:eq(0) .attr-value').each(function () {
                        if ($(this).siblings('.attr-img').length == 0) {
                            $(this).after(attr_img);
                        }
                    });
                } else {
                    $('.attr-list-box:eq(0) .attr-img').remove();
                }
            });

            $('#add-attr-group').click(function () {
                let grpidx = $('#attr-list-box input.add-attr-value:last').data('grpidx');
                grpidx = grpidx == undefined ? 0 : grpidx;
                let grphtml = $('#attr-tpl').html().replaceAll('__IDX__', parseInt(grpidx) + 1);
                if (grpidx == 0) {
                    grphtml = grphtml.replace('__OPT__', '&nbsp;&nbsp;<input type="checkbox" id="add-attr-img" title="添加图片" lay-skin="primary" lay-filter="add-attr-img"/>');
                } else {
                    grphtml = grphtml.replace('__OPT__', '&nbsp;&nbsp;<a class="hd-color-blue del-attr-group">删除属性</a>');
                }
                $('#attr-list-box').append(grphtml);
                layui.form.render('checkbox');
                layui.form.render('select');

                $('.attr-list-box').sortable({
                    handle: '.move-attr-value',
                    containment: 'parent',
                    placeholder: 'sortable-placeholder',
                    update: function () {
                        reset_sku_list();
                    }
                });
            });

            layui.form.on('select(attr-group)', function (data) {
                let select_id = $('select.attr-group-select').index(data.elem);

                if (data.value == '0') {
                    $('.attr-list:eq(' + select_id + ') input.attr-value').parent('div').remove();
                    return;
                }

                let d = false;
                $('select.attr-group-select').each(function (i) {
                    if ($(this).val() == data.value && select_id != i) {
                        d = true;
                    }
                });

                if (d) {
                    layer.alert('选择属性重复', hdImg.openAlertCfg);
                    $('select.attr-group-select:eq(' + select_id + ')').val('0');
                    $('.attr-list:eq(' + select_id + ') input.attr-value').parent('div').remove();
                    layui.form.render('select');
                }

                reset_sku_list();
            });

            $(document).on('blur', '.attr-value', function () {
                let attr_val = $.trim($(this).val());
                let attr_val_id = $(this).parent('div').parent('div').find('input.attr-value').index($(this));
                if (attr_val == '') {
                    layer.alert('请输入属性值', hdImg.openAlertCfg);
                    return false;
                }
                let d = false;
                $(this).parent('div').parent('div').find('input.attr-value').each(function (i) {
                    if (attr_val.toLowerCase() == $.trim($(this).val()).toLowerCase() && attr_val_id != i) {
                        d = true;
                    }
                });
                if (d) {
                    layer.alert('填写属性值重复', hdImg.openAlertCfg);
                    $(this).val('');
                    return false;
                }
                reset_sku_list();
            }).on('keydown', '.attr-value', function (e) {
                e.stopPropagation();
                if (e.keyCode == 13) {
                    let attr_val = $.trim($(this).val());
                    let attr_val_id = $(this).parent('div').parent('div').find('input.attr-value').index($(this));
                    if (attr_val == '') {
                        layer.alert('请输入属性值', hdImg.openAlertCfg);
                        return false;
                    }
                    let d = false;
                    $(this).parent('div').parent('div').find('input.attr-value').each(function (i) {
                        if (attr_val.toLowerCase() == $.trim($(this).val()).toLowerCase() && attr_val_id != i) {
                            d = true;
                        }
                    });
                    if (d) {
                        layer.alert('填写属性值重复', hdImg.openAlertCfg);
                        $(this).val('');
                        return false;
                    }
                    reset_sku_list();
                    return false;
                }
            });
            $(document).on('blur', '.add-attr-value', function () {
                let attr_val = $.trim($(this).val());
                if (attr_val == '') {
                    return false;
                }
                let d = false;
                $(this).parent('div').find('input.attr-value').each(function () {
                    if (attr_val.toLowerCase() == $.trim($(this).val()).toLowerCase()) {
                        d = true;
                    }
                });
                if (d) {
                    layer.alert('填写属性值重复', hdImg.openAlertCfg);
                    $(this).val('');
                    return false;
                }

                let attr_val_input = '<div class="hd-inline-block hd-margin-right-30">' +
                    '<input class="layui-input hd-inline-block hd-margin-right-10 hd-width-150 attr-value" type="text" placeholder="请输入属性值"' +
                    ' name="attr_list[' + $(this).data('grpidx') + '][]" value="' + attr_val + '"/>' + "\r\n";
                if ($('#add-attr-img').prop('checked') && $(this).data('grpidx') == '1') {
                    let first_grp_id = $.trim($('.attr-group-select:eq(0)').val());
                    attr_val_input += '<a class="attr-img" data-groupid="' + first_grp_id + '"><i class="layui-icon layui-icon-picture"></i></a>' + "\r\n";
                }
                $(this).siblings('.attr-list-box').append(attr_val_input +
                    '&nbsp;<a class="hd-color-blue move-attr-value">移动</a>' + "\r\n" +
                    '&nbsp;<a class="hd-color-blue del-attr-value">删除</a></div>' + "\r\n");

                $(this).val('');
                reset_sku_list();
            }).on('keydown', '.add-attr-value', function (e) {
                e.stopPropagation();
                if (e.keyCode == 13) {
                    let attr_val = $.trim($(this).val());
                    if (attr_val == '') {
                        return false;
                    }
                    let d = false;
                    $(this).parent('div').find('input.attr-value').each(function () {
                        if (attr_val.toLowerCase() == $.trim($(this).val()).toLowerCase()) {
                            d = true;
                        }
                    });
                    if (d) {
                        layer.alert('填写属性值重复', hdImg.openAlertCfg);
                        $(this).val('');
                        return false;
                    }

                    let attr_val_input = '<div class="hd-inline-block hd-margin-right-30">' +
                        '<input class="layui-input hd-inline-block hd-margin-right-10 hd-width-150 attr-value" type="text" placeholder="请输入属性值"' +
                        ' name="attr_list[' + $(this).data('grpidx') + '][]" value="' + attr_val + '"/>' + "\r\n";
                    if ($('#add-attr-img').prop('checked') && $(this).data('grpidx') == '1') {
                        let first_grp_id = $.trim($('.attr-group-select:eq(0)').val());
                        attr_val_input += '<a class="attr-img" data-groupid="' + first_grp_id + '"><i class="layui-icon layui-icon-picture"></i></a>' + "\r\n";
                    }
                    $(this).siblings('.attr-list-box').append(attr_val_input +
                        '&nbsp;<a class="hd-color-blue move-attr-value">移动</a>' + "\r\n" +
                        '&nbsp;<a class="hd-color-blue del-attr-value">删除</a></div>' + "\r\n");

                    $(this).val('');
                    reset_sku_list();
                    return false;
                }
            });

            $(document).on('click', '.del-attr-group', function () {
                let idx = $('.del-attr-group').index($(this)) + 1;
                $('div.attr-list:eq(' + idx + ')').remove();
                reset_sku_list();
            });

            $(document).on('click', '.del-attr-value', function () {
                $(this).parent('div').remove();
                reset_sku_list();
            });

            var attr_img_idx = -1;
            $(document).on('click', '.attr-img', function () {
                attr_img_idx = $('.attr-img').index($(this));
                $('.hd-btn-open-image').click();
            });

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
                elem: '.sku-images',
                url: '/spadmin/upload-image',
                initFolders: <?php echo json_encode($upload_folders);?>,
                imgSelectCallback: function () {
                    // 商品属性图片
                    if (attr_img_idx > -1) {
                        if (hdImg.imgSelected.length != 1) {
                            layer.alert('请选择一张图片', hdImg.openAlertCfg);
                            return;
                        }

                        let attr_img_elem = $('.attr-img').eq(attr_img_idx);
                        let group_id = $.trim(attr_img_elem.data('groupid'));
                        let attr_val = $.trim(attr_img_elem.siblings('.attr-value').val());

                        let attr_img_content = '<img src="' + hdImg.imgSelected[0] + '" style="width: 30px;" />';
                        attr_img_content += '<input type="hidden" name="attr_images[' + group_id + '][' + attr_val + ']" value="' + hdImg.imgSelected[0] + '" />';
                        attr_img_elem.html(attr_img_content);

                        attr_img_idx = -1;
                        if (hdImg.layerIdx != null) {
                            layer.close(hdImg.layerIdx);
                        }
                        return;
                    }

                    if (hdImg.imgSelected.length == 0) {
                        layer.alert('请选择图片', hdImg.openAlertCfg);
                        return;
                    }

                    // 商品主图片
                    let imgHtml = '<div style="position: relative; display: inline-block; margin: 10px; border: 1px solid #ccc;">' +
                        '-INPUT-HIDDEN-<img src="-IMG-SRC-" /><i class="layui-icon layui-icon-close-fill hd-btn-del-image" ' +
                        ' style="position: absolute; top: 0; right: 0; font-size: 20px; color: #FF5722; cursor: pointer;"></i></div>';


                    for (let idx in hdImg.imgSelected) {
                        if ($('#list_img_' + hdImg.imgBoxIdx + ' img').length >= 20) {
                            layer.alert('每项最多可添加20张图片', hdImg.openAlertCfg);
                            break;
                        }

                        let hasImg = false;
                        let selectSrc = $.trim(hdImg.imgSelected[idx]).split('?').shift().toLowerCase();
                        $('#list_img_' + hdImg.imgBoxIdx + ' img').each(function () {
                            if ($.trim($(this).attr('src')).split('?').shift().toLowerCase() == selectSrc) {
                                hasImg = true;
                            }
                        });
                        if (!hasImg) {
                            $('#list_img_' + hdImg.imgBoxIdx).append(
                                imgHtml.replace('-IMG-SRC-', hdImg.imgSelected[idx])
                                    .replace('-INPUT-HIDDEN-', '<input type="hidden" name="prod_images[]" value="' + hdImg.imgSelected[idx] + '" />')
                            );
                        }
                    }

                    if (hdImg.layerIdx != null) {
                        layer.close(hdImg.layerIdx);
                    }
                }
            });

            layui.form.on('submit(prod_edit)', function (formObj) {
                formObj.field.sku_data = {};
                save_skus.forEach(function (sku) {
                    if (sku_data_obj[sku] != undefined) {
                        formObj.field.sku_data[sku] = sku_data_obj[sku];
                    }
                });

                form_submit(window.location.href, formObj.field);
                return false;
            });
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
