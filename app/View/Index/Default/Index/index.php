<?php
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="container">
        <div class="hd-height-15 visible-xs">&nbsp;</div>
        <?php if (!empty($loop_banner)) { ?>
            <div id="hd-banner-loop" class="carousel" data-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $img_idx = 0;
                    foreach ($loop_banner as $banner_img) {
                        $active = $img_idx == 0 ? 'active' : '';
                        $img_src = $oss_access_host . $banner_img['image_path'] . '/' . $banner_img['image_name'];

                        $img_html = '<img src="' . $img_src . '"/>';
                        if ($banner_img['window_link']) {
                            $img_html = '<a href="' . $banner_img['window_link'] . '"';
                            if ($banner_img['is_new_window']) {
                                $img_html .= ' target="_blank"';
                            }
                            $img_html .= '><img src="' . $img_src . '"/></a>';
                        }

                        $img_idx++;
                        ?>
                        <div class="item <?php echo $active; ?>">
                            <?php echo $img_html; ?>
                            <div class="carousel-caption"></div>
                        </div>
                    <?php } ?>
                </div>

                <a class="left carousel-control" data-slide="prev" href="#hd-banner-loop">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" data-slide="next" href="#hd-banner-loop">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>

                <ol class="carousel-indicators">
                    <?php
                    for ($idx = 0; $idx < $img_idx; $idx++) {
                        if ($idx == 0) {
                            echo '<li data-target="#hd-banner-loop" data-slide-to="', $idx, '" class="active"></li>';
                        } else {
                            echo '<li data-target="#hd-banner-loop" data-slide-to="', $idx, '"></li>';
                        }
                    }
                    ?>
                </ol>
            </div>
        <?php } ?>
        <div class="row hd-margin-top-bottom-60" id="hd-flag-guarantee">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="hd-best-price">
                    <p></p>
                    <p>Best Price</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="hd-free-shipping">
                    <p></p>
                    <p>Free Shipping</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="hd-quality-guarantee">
                    <p></p>
                    <p>Quality Guarantee</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="hd-money-back">
                    <p></p>
                    <p>30 Days Money Back</p>
                </div>
            </div>
        </div>
        <?php if (!empty($featured_prods)) { ?>
            <div class="page-header text-center">
                <h2>Featured Products</h2>
            </div>
            <div class="row">
                <?php
                foreach ($featured_prods as $prod_info) {
                    $prod_link = $prod_info['product_url'] . '-p' . $prod_info['product_id'] . '.html';
                    $prod_img = $oss_access_host . $prod_info['image_path'] . '/' . $prod_info['image_name'] . '?' . $prod_info['updated_at'];
                    $prod_img = str_replace('_d_d', '_300_300', $prod_img);
                    ?>
                    <div class="col-md-3 hd-prod-box">
                        <div class="thumbnail">
                            <a href="/<?php echo $prod_link; ?>">
                                <img alt="<?php echo xss_text($prod_info['product_name']); ?>" src="<?php echo $prod_img; ?>"/>
                            </a>
                            <div class="caption">
                                <a href="/<?php echo $prod_link; ?>" class="title"
                                   title="<?php echo xss_text($prod_info['product_name']); ?>">
                                    <?php echo xss_text($prod_info['product_name']); ?>
                                </a>
                                <div class="price"><?php echo format_price($prod_info['price'], $currency, 1, true); ?></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php
        }
        echo empty($index_bottom_text['config_value']) ? '' : $index_bottom_text['config_value'];
        ?>
    </div>
<?php if ($device !== 'PC') { ?>
    <script src="/static/hammer.min.js"></script>
    <script>
        $('#hd-banner-loop a.carousel-control').hide();

        var banner_loop_ele = document.getElementById('hd-banner-loop');
        var hammer_manager = new Hammer.Manager(banner_loop_ele);
        var hammer_swipe = new Hammer.Swipe();
        hammer_manager.add(hammer_swipe);
        hammer_manager.on('swipeleft', function () {
            $('#hd-banner-loop a.right').click();
        }).on('swiperight', function () {
            $('#hd-banner-loop a.left').click();
        });
    </script>
    <?php
}
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
