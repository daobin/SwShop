<?php
$first_img_src = '';
$default_sku = !empty($sku_arr) ? reset($sku_arr) : '';
$prod_name = xss_text($prod_info['desc']['product_name'] ?? '');

$tkdTitle = empty($prod_info['desc']['meta_title']) ? $prod_name : $prod_info['desc']['meta_title'];
$widget_params['tkd_title'] = $prod_name . ' - ' . $website_name;
$widget_params['tkd_keywords'] = empty($prod_info['desc']['meta_keywords']) ? $prod_name : $prod_info['desc']['meta_keywords'];
$widget_params['tkd_description'] = empty($prod_info['desc']['meta_description']) ? $prod_name : $prod_info['desc']['meta_description'];

$attr_value_skus = [];
if (!empty($prod_info['attr_value_list'])) {
    foreach ($prod_info['attr_value_list'] as $group_id => $sku_attr) {
        foreach ($sku_attr as $sku => $attr_value) {
            $attr_value_skus[$group_id][$attr_value][$sku] = $sku;
        }
    }
}

\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <?php
            if (!empty($cate_level)) {
                foreach ($cate_level as $level_info) {
                    $level_name = xss_text($level_info['category_name']);
                    $cate_link = $level_info['category_url'] . '-c' . $level_info['product_category_id'] . '.html';
                    echo '<li><a href="/', $cate_link, '">', $level_name, '</a></li>';
                }
            }
            ?>
            <li class="active"><?php echo $prod_name; ?></li>
        </ol>
    </div>
    <div class="container hd-margin-top-30">
        <div class="row">
            <div class="col-md-6">
                <?php if (!empty($prod_img_list)) { ?>
                    <div id="hd-main-image-loop" class="carousel">
                        <div class="carousel-inner">
                            <?php
                            foreach ($prod_img_list as $prod_img) {
                                $img_src = $oss_access_host . $prod_img['image_path'] . '/' . $prod_img['image_name'] . '?' . $prod_img['updated_at'];
                                $img_src = str_replace('_d_d', '_800_800', $img_src);
                                echo '<div class="item ', ($first_img_src ? '' : ' active '), '">';
                                echo '<img src="', $img_src, '"/>';
                                echo '</div>';

                                $first_img_src = $first_img_src ? $first_img_src : $img_src;
                            }
                            ?>
                        </div>

                        <a class="left carousel-control" data-slide="prev" href="#hd-main-image-loop">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" data-slide="next" href="#hd-main-image-loop">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-6" id="hd-prod-info">
                <div class="hd-margin-top-30 visible-xs visible-sm">&nbsp;</div>
                <h1><?php echo $prod_name; ?></h1>
                <div class="row hd-margin-top-30">
                    <span class="col-xs-2 hd-font-weight-bold">SKU :</span>
                    <span class="hd-sku"><?php echo $default_sku; ?></span>
                </div>
                <?php
                if (!empty($attr_group_list)) {
                    foreach ($attr_group_list as $group_id => $group_info) {
                        if (empty($prod_info['attr_value_list'][$group_id])) {
                            continue;
                        }

                        $active_attr_value = $prod_info['attr_value_list'][$group_id][$default_sku] ?? '';
                        if (empty($prod_info['attr_image_list'][$group_id])) {
                            echo '<div class="row hd-margin-top-10">';
                            echo '<span class="col-xs-2 hd-font-weight-bold">', xss_text($group_info['group_name']), ':</span>';
                            foreach ($attr_value_skus[$group_id] as $attr_value => $skus) {
                                if ($attr_value == $active_attr_value) {
                                    echo '<a class="hd-sku-attr-select active">' . $attr_value . '</a>';
                                } else {
                                    echo '<a class="hd-sku-attr-select">' . $attr_value . '</a>';
                                }
                            }
                            echo '<input type="hidden" class="active_attr_value" data-group="', $group_id, '" value="', $active_attr_value, '" />';
                            echo '</div>';
                        } else {
                            echo '<div class="hd-margin-top-30" id="hd-sku-img-select">';
                            foreach ($prod_info['attr_image_list'][$group_id] as $attr_value => $img_src) {
                                if ($attr_value == $active_attr_value) {
                                    echo '<img class="active" src="', $img_src, '" data-attr="', $attr_value, '" />';
                                } else {
                                    echo '<img src="', $img_src, '" data-attr="', $attr_value, '" />';
                                }
                            }
                            echo '<input type="hidden" class="active_attr_value" data-group="', $group_id, '" value="', $active_attr_value, '" />';
                            echo '</div>';
                        }
                    }
                }
                ?>
                <div class="row hd-margin-top-30">
                    <div class="col-md-12 hd-prod-box">
                        <?php
                        $qty = 0;
                        if (!empty($sku_qty_price_list[$default_sku]['price'])) {
                            $qty = (int)$sku_qty_price_list[$default_sku]['qty'];

                            echo '<span class="price hd-display-inline-block hd-margin-right-15">';
                            echo $sku_qty_price_list[$default_sku]['price_text'], '</span>';

                            if ($sku_qty_price_list[$default_sku]['price_off']) {
                                echo '<span class="origin hd-display-inline-block hd-margin-right-15">&nbsp;';
                                echo $sku_qty_price_list[$default_sku]['list_price_text'] . '&nbsp;</span>';

                                echo '<span class="off hd-display-inline-block hd-margin-right-15">';
                                echo $sku_qty_price_list[$default_sku]['price_off'], '</span>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="row hd-margin-top-30">
                    <div class="col-md-4">
                        <div class="input-group hd-width-150">
                            <div class="btn input-group-addon hd-prod-qty-minus">-</div>
                            <input type="text" id="hd-prod-qty-val" class="form-control hd-prod-qty-val text-center"
                                   data-qty="<?php echo $qty; ?>" data-val="1" value="1"/>
                            <div class="btn input-group-addon hd-prod-qty-plus" data-qty="<?php echo $qty; ?>">+</div>
                        </div>
                    </div>
                </div>
                <div class="row hd-margin-top-30">
                    <div class="col-md-4">
                        <button class="btn btn-lg btn-warning" id="hd-add-to-cart">Add To Cart</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="hd-margin-top-bottom-60">
            <div id="hd-prod-detail-scroll" class="navbar navbar-default hd-left-top-0 hidden-xs hidden-sm">
                <ul class="nav navbar-nav">
                    <li class="active"><a>Product Description</a></li>
                    <li><a>Shipping & Payment</a></li>
                </ul>
            </div>
            <div id="hd-prod-desc"
                 class="hd-prod-detail-content"><?php echo $prod_info['desc']['product_description'] ?? ''; ?></div>
            <div id="hd-shipping-payment" class="hd-prod-detail-content hd-margin-top-bottom-60">
                <h3>Shipping</h3>
                <p>Generally speaking, we arrange for shipment within 48 hours after placing the order. Individual
                    orders may be delayed due to temporary shortages. We provide free shipping and paid courier
                    services. Express shipping freight depends on the quantity of goods.</p>
                <h3>Payment</h3>
                <p>
                    <?php echo $website_name; ?> accepts Paypal, Visa, MasterCard, JCB and America
                    Express. <?php echo $website_name; ?> secured
                    by SSL, all information we collect are secured.
                </p>
            </div>
        </div>
    </div>
    <input type="hidden" id="hd-sku" value="<?php echo $default_sku; ?>"/>
    <input type="hidden" id="hd-cart-tk" value="<?php echo $cart_tk ?? ''; ?>"/>
    <script>
        var first_img_src = '<?php echo $first_img_src;?>';
        var attr_value_skus = <?php echo json_encode($attr_value_skus);?>;
        var qty_price_list = <?php echo json_encode($sku_qty_price_list);?>;

        function resetSkuShow() {
            let skus = [];
            $('input.active_attr_value').each(function () {
                let group = $.trim($(this).data('group'));
                let attr = $.trim($(this).val());
                if (skus.length == 0) {
                    skus = Object.keys(attr_value_skus[group][attr]);
                } else {
                    skus = Object.keys(attr_value_skus[group][attr]).filter(function (sku) {
                        return skus.indexOf(sku) !== -1;
                    });
                }
            });

            let sku = skus.shift();
            if (qty_price_list[sku] == undefined) {
                return;
            }

            $('#hd-prod-info .hd-prod-box').html(function () {
                let qty_price_html = '<span class="price hd-display-inline-block hd-margin-right-15">' + qty_price_list[sku]['price_text'] + '</span>';
                if (qty_price_list[sku]['price_off'] != '') {
                    qty_price_html += '<span class="origin hd-display-inline-block hd-margin-right-15">&nbsp;' + qty_price_list[sku]['list_price_text'] + '&nbsp;</span>' +
                        '<span class="off hd-display-inline-block hd-margin-right-15">' + qty_price_list[sku]['price_off'] + '</span>';
                }
                return qty_price_html;
            });

            $('.hd-sku').text(sku);
            $('#hd-sku').val(sku);
            $('.hd-prod-qty-plus, #hd-prod-qty-val').data('qty', qty_price_list[sku]['qty']);
            $('#hd-prod-qty-val').val(1);
        }

        $('a.hd-sku-attr-select').click(function () {
            if ($(this).hasClass('active')) {
                return;
            }

            $(this).parent('div').find('.hd-sku-attr-select').removeClass('active');
            $(this).addClass('active');

            $(this).siblings('.active_attr_value').val($.trim($(this).text()));
            resetSkuShow();
        });

        $('#hd-sku-img-select img').click(function () {
            if ($(this).hasClass('active')) {
                return;
            }

            $('#hd-sku-img-select img').removeClass('active');
            $(this).addClass('active');

            $(this).siblings('.active_attr_value').val($.trim($(this).data('attr')));
            resetSkuShow();
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
