<?php
$widget_params['shopping_nav_img'] = 'step2.png';
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params);
?>
    <div class="container">
        <div class="page-header">
            <h2>
                Order Confirmation
                <a href="/shopping/cart.html" class="btn btn-link pull-right">&lt;&lt;&nbsp;Back to cart</a>
            </h2>
        </div>
        <?php include 'error.php';?>
        <h3 class="hd-margin-top-30">Shipping Address</h3>
        <div class="row">
            <div class="col-md-10" style="line-height: 30px;">
                <?php
                if (!empty($shipping_address)) {
                    echo xss_text($shipping_address['first_name'] . ' ' . $shipping_address['last_name']);
                    echo ', ', xss_text($shipping_address['street_address']);
                    if (!empty($shipping_address['street_address_sub'])) {
                        echo ', ', xss_text($shipping_address['street_address_sub']);
                    }
                    echo ', ', xss_text($shipping_address['city']);
                    echo ', ', xss_text($shipping_address['zone_name'] . ' ' . $shipping_address['postcode']);
                    echo ', ', xss_text($shipping_address['country_name']);
                    echo ', ', xss_text($shipping_address['telephone']);
                }
                ?>
            </div>
            <div class="col-md-2">
                <a class="btn btn-sm btn-primary" href="/address.html?from=confirmation">Edit address</a>
            </div>
        </div>
        <h3 class="hd-margin-top-30">Order Summary</h3>
        <table id="hd-cart-products" class="table">
            <tbody>
            <tr>
                <th style="max-width: 30%;" class="hidden-xs">Product</th>
                <th class="visible-xs">Product</th>
                <th class="text-center">Price</th>
                <th class="text-center">Quantity</th>
                <th class="text-center hidden-xs">Total</th>
            </tr>
            <?php
            if (!empty($order_summary['prod_list'])) {
                foreach ($order_summary['prod_list'] as $prod_info) {
                    $prod_link = '/%s-p%s.html';
                    $prod_link = sprintf($prod_link, process_url_string($prod_info['product_name']), $prod_info['product_id']);
                    $prod_name = xss_text($prod_info['product_name']);
                    $prod_img = $prod_info['product_img'];
                    $prod_img = $oss_access_host . $prod_img['image_path'] . '/' . $prod_img['image_name'] . '?' . $prod_img['updated_at'];
                    $prod_img = str_replace('_d_d', '_100_100', $prod_img);
                    ?>
                    <tr>
                        <td class="product">
                            <div class="hd-display-inline-block">
                                <a href="<?php echo $prod_link; ?>" title="<?php echo $prod_name; ?>">
                                    <img src="<?php echo $prod_img; ?>">
                                </a>
                            </div>
                            <div class="hd-display-inline-block">
                                <p>SKU: GT0001</p>
                                <p class="hidden-xs hidden-sm">
                                    <a href="<?php echo $prod_link; ?>"
                                       title="<?php echo $prod_name; ?>"><?php echo $prod_name; ?></a>
                                </p>
                            </div>
                        </td>
                        <td class="text-center"><?php echo $prod_info['price_text']; ?></td>
                        <td class="text-center"><?php echo $prod_info['qty']; ?></td>
                        <td class="text-center hidden-xs hd-prod-total"><?php echo $prod_info['total_text']; ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
            <tr>
                <th colspan="4">Shipping Method</th>
            </tr>
            <tr>
                <td colspan="4" id="hd-shipping-list">
                    <?php
                    if (!empty($shipping_list)) {
                        foreach ($shipping_list as $idx => $shipping) {
                            echo '<div><label class="hd-font-weight-normal hd-cursor-pointer">';
                            if ($idx === 0) {
                                echo '<input type="radio" name="shipping_method" checked value="', xss_text($shipping['method_code']), '"/> ';
                            } else {
                                echo '<input type="radio" name="shipping_method" value="', xss_text($shipping['method_code']), '"/> ';
                            }
                            echo xss_text($shipping['method_name']);
                            echo ' (', empty($shipping['note']) ? '' : (xss_text($shipping['note']) . ', '), format_price(0, $currency, 1, true), ')';
                            echo '</label></div>';
                        }
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-right">
                    <?php
                    if (!empty($order_summary['totals'])) {
                        foreach ($order_summary['totals'] as $class => $total) {
                            if ($class == 'total') {
                                echo '<div class="hd-font-weight-bold hd-margin-top-15">';
                            } else {
                                echo '<div class="hd-margin-top-10">';
                            }
                            echo $total['title'], ': <span class="hd-color-price hd-padding-left-15">', $total['text'], '</span></div>';
                        }
                    }
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
        <form method="post" autocomplete="off" id="hd-form-place-order" action="/shopping/payment.html">
            <h3 class="hd-margin-top-30">Payment Method</h3>
            <div id="hd-payment-list" class="hd-margin-top-10">
                <?php
                if (!empty($payment_list)) {
                    foreach ($payment_list as $idx => $payment) {
                        echo '<div><label class="hd-font-weight-normal hd-cursor-pointer">';
                        if ($idx === 0) {
                            echo '<input type="radio" name="payment_method" checked value="', xss_text($payment['method_code']), '"/>';
                        } else {
                            echo '<input type="radio" name="payment_method" value="', xss_text($payment['method_code']), '"/>';
                        }
                        echo ' Checkout with ', xss_text($payment['method_name']);
                        echo '</label></div>';
                    }
                }
                ?>
            </div>
            <div class="hd-margin-top-60 text-right">
                <input type="hidden" name="address_id"
                       value="<?php echo $shipping_address['customer_address_id'] ?? 0; ?>"/>
                <input type="hidden" name="hash_tk" value="<?php echo $hash_tk ?? ''; ?>"/>
                <input type="submit" class="btn btn-lg btn-warning" value="Place Order"/>
            </div>
        </form>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params);
