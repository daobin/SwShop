<?php
$widget_params['shopping_nav_img'] = 'step3.png';
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params);
?>
    <div class="container">
        <div class="page-header">
            <h2>
                Successful
            </h2>
        </div>
        <?php if (!empty($order_info)) { ?>
            <div class="row hd-margin-top-30">
                <div class="col-md-2 col-sm-3 col-xs-6"><b>Order Number:</b></div>
                <div class="col-md-10 col-sm-9 col-xs-6"><?php echo $order_info['order_number'];?></div>
            </div>
            <div class="row hd-margin-top-15">
                <div class="col-md-2 col-sm-3 col-xs-6"><b>Order Total:</b></div>
                <div class="col-md-10 col-sm-9 col-xs-6">
                    <?php echo format_price_total($order_info['order_total'], $order_currency);?>
                </div>
            </div>
            <div class="row hd-margin-top-30">
                <div class="col-xs-12">
                    <a href="/order/<?php echo $order_info['order_number'];?>.html" class="btn btn-warning">Check Order</a>
                    <span class="hd-padding-left-15"></span>
                    <a class="btn btn-default" href="/">Continue Shopping</a>
                </div>
            </div>
            <div class="row hd-margin-top-30">
                <div class="col-xs-12 hd-color-888">
                    <div><?php echo xss_text('order_being_processed', $lang_code); ?></div>
                    <div class="hd-margin-top-10"><?php echo xss_text('item_delivered_time', $lang_code); ?></div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row hidden-xs hidden-sm hd-margin-top-130">
                <div class="col-md-5 text-right">
                    <img src="/static/index/default/cart.png"/>
                </div>
                <div class="col-md-7">
                    <div class="text-danger hd-font-size-24">You have no orders</div>
                    <div class="hd-margin-top-15">
                        Go to <a href="/" class="text-warning">Home Page</a> and select items.
                    </div>
                </div>
            </div>
            <div class="row visible-xs visible-sm hd-margin-top-60">
                <div class="col-md-5 text-center">
                    <img src="/static/index/default/cart.png"/>
                </div>
                <div class="col-md-7 text-center">
                    <div class="text-danger hd-font-size-24">You have no orders</div>
                    <div class="hd-margin-top-15">
                        Go to <a href="/" class="text-warning">Home Page</a> and select items.
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params);
