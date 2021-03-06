<?php
$widget_params['tkd_title'] = 'My Order - ' . $website_name;
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">My Order</li>
        </ol>
    </div>
    <div class="container">
        <ul class="nav nav-pills nav-justified bg-info hd-border-radius-4">
            <li><a href="/account.html">My Profile</a></li>
            <li><a href="/password.html">Change Password</a></li>
            <li><a href="/address.html">My Address</a></li>
            <li class="active"><a>My Order</a></li>
            <li><a href="/logout.html">Logout</a></li>
        </ul>
        <div class="page-header">
            <h2 class="hd-color-333">My Order</h2>
        </div>
        <?php if (empty($order_list)) { ?>
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
        <?php } else { ?>
            <div class="hd-margin-top-30">
                <?php
                foreach ($order_list as $order_id => $order_info) {
                    $order_currency = $currency_list[$order_info['currency_code']] ?? [];
                    ?>
                    <div class="hd-order-one panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-8 hd-font-weight-bold">
                                    Order #<?php echo $order_info['order_number']; ?>
                                    |
                                    Date: <?php echo date('Y-m-d', $order_info['created_at']); ?>
                                    |
                                    Total: <?php echo format_price_total($order_info['order_total'], $order_currency); ?>
                                </div>
                                <div class="col-md-4 text-right">
                                    Order Status: <b class="text-danger">
                                        <?php echo $order_statuses[$order_info['order_status_id']] ?? ''; ?>
                                    </b>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <?php
                                    $prod_idx = 0;
                                    foreach ($order_info['prod_list'] as $sku => $prod_info) {
                                        $prod_id = $prod_info['product_id'];
                                        $prod_name = xss_text($prod_info['product_name']);
                                        $prod_link = process_url_string($prod_name) . '-p' . $prod_id . '.html';
                                        $prod_img = '';
                                        if (!empty($prod_img_list[$prod_id])) {
                                            $prod_img = $oss_access_host . $prod_img_list[$prod_id]['image_path'] . '/' . $prod_img_list[$prod_id]['image_name'] . '?' . $prod_img_list[$prod_id]['updated_at'];
                                        }
                                        $sku_attrs = [];
                                        if (!empty($sku_attr_list[$sku])) {
                                            foreach ($sku_attr_list[$sku] as $attr_value => $attr_info) {
                                                $sku_attrs[] = xss_text($attr_value);
                                                if (!empty($attr_info['image_path']) && !empty($attr_info['image_name'])) {
                                                    $prod_img = $oss_access_host . $attr_info['image_path'] . '/' . $attr_info['image_name'] . '?' . $attr_info['updated_at'];
                                                }
                                            }
                                        }
                                        $prod_img = str_replace('_d_d', '_100_100', $prod_img);
                                        if ($prod_idx > 0) {
                                            echo '<hr/>';
                                        }
                                        $prod_idx++;
                                        ?>
                                        <div class="row">
                                            <div class="col-md-1">
                                                <a href="/<?php echo $prod_link; ?>">
                                                    <img src="<?php echo $prod_img; ?>"/>
                                                </a>
                                            </div>
                                            <div class="col-md-6 hd-prod-title">
                                                <a href="/<?php echo $prod_link; ?>" title="<?php echo $prod_name; ?>">
                                                    <?php
                                                    if (!empty($sku_attrs)) {
                                                        echo ' <span class="hd-color-888">[ ', implode(", ", $sku_attrs), ' ]</span>';
                                                        echo '<span class="hd-padding-left-15">', $prod_name, '</span>';
                                                    } else {
                                                        echo '<span>', $prod_name, '</span>';
                                                    }
                                                    ?>
                                                </a>
                                            </div>
                                            <div class="col-md-2 col-md-offset-1 hd-v-center">
                                                <?php echo $prod_info['qty']; ?>
                                            </div>
                                            <div class="col-md-2 hd-v-center">
                                                <?php echo format_price_total($prod_info['price'], $order_currency); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="col-md-2 text-right hd-v-center">
                                    <a href="/order/<?php echo $order_info['order_number']; ?>.html"
                                       class="btn btn-info">Order Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php
            if ($page_total > 1) {
                echo '<div class="row hd-margin-top-60"><ul class="pager">';
                if ($page > 1) {
                    echo '<li class="previous"><a href="?page=', $page - 1, '"><span>&larr;</span> Previous</a></li>';
                }
                if ($page_total > $page) {
                    echo '<li class="next"><a href="?page=', $page + 1, '">Next <span>&rarr;</span></a></li>';
                }
                echo '</ul></div>';
            }
        }
        ?>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
