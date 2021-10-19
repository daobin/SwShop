<?php
$first_img_src = '';
$default_sku = reset($sku_arr);
$prod_name = xss_text($prod_info['desc']['product_name'] ?? '');

$tkdTitle = empty($prod_info['desc']['meta_title']) ? $prod_name : $prod_info['desc']['meta_title'];
$widget_params['tkd_title'] = $prod_name . ' - ' . $website_name;
$widget_params['tkd_keywords'] = empty($prod_info['desc']['meta_keywords']) ? $prod_name : $prod_info['desc']['meta_keywords'];
$widget_params['tkd_description'] = empty($prod_info['desc']['meta_description']) ? $prod_name : $prod_info['desc']['meta_description'];

\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <?php
            $cate_name = '';
            if (!empty($cate_level)) {
                foreach ($cate_level as $level_info) {
                    $level_name = xss_text($level_info['category_name']);
                    if ($prod_info['product_category_id'] == $level_info['product_category_id']) {
                        $cate_name = $level_name;
                        break;
                    }

                    $cate_link = $level_info['category_url'] . '-c' . $level_info['product_category_id'] . '.html';
                    echo '<li><a href="/', $cate_link, '">', $level_name, '</a></li>';
                }
            }
            ?>
            <li class="active"><?php echo $cate_name; ?></li>
        </ol>
    </div>
    <div class="container hd-margin-top-30">
        <div class="row">
            <div class="col-md-6">
                <?php if (!empty($sku_img_list[$default_sku])) { ?>
                    <div id="hd-main-image-loop" class="carousel">
                        <div class="carousel-inner">
                            <?php
                            foreach ($sku_img_list[$default_sku] as $sku_img) {
                                $img_src = $oss_access_host . $sku_img['image_path'] . '/' . $sku_img['image_name'] . '?' . $sku_img['updated_at'];
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
                    <div class="col-md-3 hd-font-weight-bold">SKU :</div>
                    <div class="col-md-9 hd-sku"><?php echo $default_sku; ?></div>
                </div>
                <div class="row hd-margin-top-30">
                    <div class="col-md-3 hd-font-weight-bold">Ship From :</div>
                    <div class="col-md-9">US Warehouse (Arrivals in 5-7 business days)</div>
                </div>
                <?php
                if (!empty($sku_arr)) {
                    echo '<div class="row hd-margin-top-30" id="hd-sku-img-select">';
                    foreach ($sku_arr as $sku) {
                        if (empty($sku_img_list[$sku])) {
                            continue;
                        }

                        $sku_img = reset($sku_img_list[$sku]);
                        $img_src = $oss_access_host . $sku_img['image_path'] . '/' . $sku_img['image_name'] . '?' . $sku_img['updated_at'];
                        $img_src = str_replace('_d_d', '_100_100', $img_src);
                        if ($sku == $default_sku) {
                            echo '<img class="active" data-sku="', $sku, '" src="', $img_src, '" />';
                        } else {
                            echo '<img data-sku="', $sku, '" src="', $img_src, '" />';
                        }
                    }
                    echo '</div>';
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
                    services. Express shipping freight depends on the quantity of goods.All orders are shipped out via
                    UPS or USPS or Fedex or Yodel or Royal Mail or Herms.</p>
                <h3>Payment</h3>
                <p>
                    Glarrymusic.com accepts Paypal, Visa, MasterCard, JCB and America Express. Glarrymusic.com secured
                    by Godaddy SSL, all information we collect are secured.
                </p>
            </div>
        </div>
    </div>
    <input type="hidden" id="hd-sku" value="<?php echo $default_sku; ?>"/>
    <input type="hidden" id="hd-cart-tk" value="<?php echo $cart_tk ?? ''; ?>"/>
<?php if ($device == 'PC') { ?>
    <script src="/static/jquery/jquery.zoom.min.js"></script>
    <script>
        function init_zoom_sku_img_list() {
            $('#hd-main-image-loop .item img').click(function () {
                let imgSrc = $(this).attr('src');

                $('#hd-main-image-loop').zoom({
                    url: imgSrc,
                    callback: function () {
                        $('#hd-main-image-loop .zoomImg').css('opacity', '1');
                    },
                    onZoomOut: function () {
                        $('#hd-main-image-loop .zoomImg').remove();
                    }
                });
                $(document).on('click', '#hd-main-image-loop .zoomImg', function () {
                    $('#hd-main-image-loop .zoomImg').remove();
                });
            });
        }
    </script>
<?php } else { ?>
    <script>
        function init_zoom_sku_img_list() {
            // Nothing
        }
    </script>
<?php } ?>
    <script>
        var first_img_src = '<?php echo $first_img_src;?>';
        var img_list = <?php echo json_encode($sku_img_list);?>;
        var qty_price_list = <?php echo json_encode($sku_qty_price_list);?>;
        $('#hd-sku-img-select img').click(function () {
            if ($(this).hasClass('active')) {
                return;
            }

            let sku = $(this).data('sku');
            if (qty_price_list[sku] == undefined || img_list[sku] == undefined) {
                return;
            }

            $('#hd-main-image-loop .carousel-inner').html(function () {
                first_img_src = '';
                let img_html = '';
                for (let sort in img_list[sku]) {
                    let img_src = '<?php echo $oss_access_host;?>' + img_list[sku][sort]['image_path'] + '/';
                    img_src += img_list[sku][sort]['image_name'] + '?' + img_list[sku][sort]['updated_at'];
                    img_src = img_src.replace('_d_d', '_800_800');

                    if (first_img_src == '') {
                        first_img_src = img_src;
                        img_html += '<div class="item active"><img src="' + img_src + '"/></div>';
                    } else {
                        img_html += '<div class="item"><img src="' + img_src + '"/></div>';
                    }
                }

                return img_html;
            });

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

            $('#hd-sku-img-select img').removeClass('active');
            $(this).addClass('active');

            init_zoom_sku_img_list();
        });

        init_zoom_sku_img_list();
    </script>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
