<?php
$widget_params['tkd_title'] = 'Order Tracking - ' . $website_name;
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Order Tracking</li>
        </ol>
    </div>
    <div class="container">
        <div class="page-header">
            <h2 class="hd-color-333">
                Order Tracking
                <?php if ($order_info) { ?>
                    <a class="btn btn-info pull-right" href="/order-tracking.html">
                        &lt;&lt; Back
                    </a>
                <?php } else { ?>
                    <small class="hd-font-size-18 hd-margin-left-15 hidden-xs hidden-sm">
                        Please type in your email address and order number to display the order information.
                    </small>
                <?php } ?>
            </h2>
        </div>
        <?php if ($order_info) { ?>
            <div class="panel panel-default hd-margin-top-30" id="hd-order-info">
                <div class="panel-heading">Order Information</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2">Order ID:</div>
                        <div class="col-md-4"><?php echo $order_info['order_number']; ?></div>
                        <div class="col-md-2">Date:</div>
                        <div class="col-md-4"><?php echo date('Y-m-d', $order_info['created_at']); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">Shipping Method:</div>
                        <div class="col-md-4"><?php echo $order_info['shipping_method']; ?></div>
                        <div class="col-md-2">Payment Method:</div>
                        <div class="col-md-4"><?php echo $order_info['payment_method']; ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">Shipping Address:</div>
                        <div class="col-md-10">
                            <?php
                            echo xss_text($order_address['first_name'] . ' ' . $order_address['last_name']);
                            echo ', ', xss_text($order_address['street_address']);
                            if (!empty($order_address['street_address_sub'])) {
                                echo ', ', xss_text($order_address['street_address_sub']);
                            }
                            echo ', ', xss_text($order_address['city']);
                            echo ', ', xss_text($order_address['zone_name'] . ' ' . $order_address['postcode']);
                            echo ', ', xss_text($order_address['country_name']);
                            echo ', ', xss_text($order_address['telephone']);
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">Tracking Info:</div>
                        <div class="col-md-10"></div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default hd-margin-top-30" id="hd-order-sh">
                <div class="panel-heading">Order Status History</div>
                <div class="panel-body">
                    <?php
                    $status_idx = 0;
                    foreach ($history_list as $status_info) {
                        if ($status_info['is_show'] == 0) {
                            continue;
                        }
                        if ($status_idx > 0) {
                            echo '<hr/>';
                        }
                        $status_idx++;
                        ?>
                        <div class="row">
                            <div class="col-md-2 text-center"><?php echo date('Y-m-d H:i:s', $status_info['created_at']); ?></div>
                            <div class="col-md-2 hd-color-666">
                                <b><?php echo $order_statuses[$status_info['order_status_id']]; ?></b></div>
                            <div class="col-md-8">
                                <?php
                                $search = 'customer service center';
                                $replace = '<a href="/customer-service.html" class="btn-link">customer service center</a>';
                                echo str_replace($search, $replace, $status_info['comment']);
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <table id="hd-cart-products" class="table hd-margin-top-60">
                <tbody>
                <tr>
                    <th style="max-width: 30%;" class="hidden-xs">Product</th>
                    <th class="visible-xs">Product</th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center hidden-xs">Total</th>
                </tr>
                <?php
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
                    ?>
                    <tr>
                        <td class="product">
                            <div class="hd-display-inline-block">
                                <a href="/<?php echo $prod_link; ?>">
                                    <img src="<?php echo $prod_img; ?>"/>
                                </a>
                            </div>
                            <div class="hd-display-inline-block">
                                <p>SKU: <?php echo $sku; ?></p>
                                <p class="hidden-xs hidden-sm">
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
                                </p>
                            </div>
                        </td>
                        <td class="text-center"><?php echo format_price_total($prod_info['price'], $order_currency); ?></td>
                        <td class="text-center"><?php echo $prod_info['qty']; ?></td>
                        <td class="text-center hidden-xs hd-prod-total">
                            <?php echo format_price_total($prod_info['price'] * $prod_info['qty'], $order_currency); ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="4" class="text-right hd-font-size-18">
                        <?php
                        foreach ($total_list as $class => $total) {
                            if ($class == 'total') {
                                echo '<div class="hd-margin-top-15 hd-font-weight-bold">', $total['ot_title'], ':';
                            } else {
                                echo '<div class="hd-margin-top-15">', $total['ot_title'], ':';
                            }
                            echo '<span class="hd-color-price hd-padding-left-15">', $total['ot_text'], '</span></div>';
                        }
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <div>
                <a class="btn btn-info pull-right" href="/order-tracking.html">
                    &lt;&lt; Back
                </a>
            </div>
        <?php } else { ?>
            <form class="form-horizontal" method="post" autocomplete="off">
                <?php if ($is_post) { ?>
                    <div class="form-group hd-margin-top-30">
                        <div class="col-md-8 col-md-offset-2 hd-font-size-18 text-danger">
                            <?php echo '<i class="glyphicon glyphicon-remove"></i>&nbsp;', xss_text('email_no_match_order', $lang_code); ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group hd-margin-top-30">
                    <label class="control-label col-md-2">Email Address <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="email" value="<?php echo $email ?? ''; ?>"/>
                    </div>
                </div>
                <div class="form-group hd-margin-top-30">
                    <label class="control-label col-md-2">Order Number <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="number" value="<?php echo $number ?? ''; ?>"/>
                    </div>
                </div>
                <div class=" form-group hd-margin-top-30">
                    <div class="col-md-8 col-md-offset-2">
                        <input type="hidden" name="hash_tk" value="<?php echo $hash_tk ?? ''; ?>"/>
                        <input type="submit" class="btn btn-warning" value="Tracking"/>
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
