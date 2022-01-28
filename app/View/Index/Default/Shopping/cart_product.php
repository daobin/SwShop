<div class="container hd-display-none">
    <div class="page-header">
        <h2>Shopping Cart</h2>
    </div>
    <div class="row hidden-xs hidden-sm hd-margin-top-130">
        <div class="col-md-5 text-right">
            <img src="/static/index/default/cart.png"/>
        </div>
        <div class="col-md-7">
            <div class="text-danger hd-font-size-24">Your shopping cart is empty</div>
            <?php if (empty($customer_id)) { ?>
                <div class="hd-margin-top-30">
                    <a href="/login.html" class="text-warning">Sign In</a> to see what you left in shopping cart
                    last time.
                </div>
            <?php } ?>
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
            <div class="text-danger hd-font-size-24">Your shopping cart is empty</div>
            <?php if (empty($customer_id)) { ?>
                <div class="hd-margin-top-30">
                    <a href="/login.html" class="text-warning">Sign In</a> to see what you left in shopping cart
                    last time.
                </div>
            <?php } ?>
            <div class="hd-margin-top-15">
                Go to <a href="/" class="text-warning">Home Page</a> and select items.
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="page-header hd-border-none">
        <h2 class="hd-color-333">Shopping Cart</h2>
    </div>
    <?php include 'error.php'; ?>
    <table id="hd-cart-products" class="table hd-margin-top-30">
        <tr>
            <th style="max-width: 30%;" class="hidden-xs">Product</th>
            <th class="visible-xs">Product</th>
            <th class="text-center">Price</th>
            <th class="text-center">Quantity</th>
            <th class="text-center hidden-xs">Total</th>
            <th class="text-center">Options</th>
        </tr>
        <?php
        $sub_total = 0;
        foreach ($cart_list as $sku => $cart_info) {
            $prod_id = $cart_info['product_id'];
            $prod_name = xss_text($prod_name_list[$prod_id]);
            $prod_link = $prod_url_list[$prod_id] . '-p' . $prod_id . '.html';
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
            $cart_qty = (int)$cart_info['qty'];
            $prod_qty = 0;
            $price = 0;
            if (isset($sku_qty_price_list[$sku])) {
                $prod_qty = (int)$sku_qty_price_list[$sku]['qty'];
                $price = (float)$sku_qty_price_list[$sku]['price'];
            }
            $price_total = (float)format_price($price, $currency, $cart_qty);
            if ($prod_qty > 0) {
                $sub_total += $price_total;
            }
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
                <td class="text-center">
                    <?php
                    if ($prod_qty > 0) {
                        echo format_price($price, $currency, 1, true);
                    }
                    ?>
                </td>
                <td class="text-center">
                    <?php
                    if ($prod_qty <= 0) {
                        echo '<b class="text-danger">', $sold_out_text, '</b>';
                    } else {
                        ?>
                        <div class="input-group hd-width-max-150 hd-width-min-100 hd-margin-left-right-auto hd-up-cart-prod">
                            <div class="btn input-group-addon hd-prod-qty-minus">-</div>
                            <input type="text" class="form-control hd-prod-qty-val text-center"
                                   data-qty="<?php echo $prod_qty; ?>" data-val="<?php echo $cart_qty; ?>"
                                   value="<?php echo $cart_qty; ?>"/>
                            <div class="btn input-group-addon hd-prod-qty-plus" data-qty="<?php echo $prod_qty; ?>">+
                            </div>
                        </div>
                        <input type="hidden" class="hd-sku" value="<?php echo $sku; ?>"/>
                    <?php } ?>
                </td>
                <td class="text-center hidden-xs hd-prod-total">
                    <?php
                    if ($prod_qty > 0) {
                        echo format_price_total($price_total, $currency);
                    }
                    ?>
                </td>
                <td class="text-center">
                    <a class="btn btn-sm btn-danger" data-toggle="popover" data-sku="<?php echo $sku; ?>">
                        <i class="glyphicon glyphicon-trash hd-font-size-18"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <hr/>
    <div class="row hd-margin-top-bottom-60 clearfix">
        <div class="col-md-6 hd-font-size-18 text-success hidden-xs hidden-sm">
            <i class="fa fa-lock hd-font-size-24"></i>
            <span>Shopping is Safe and Secure</span>
        </div>
        <div class="col-md-6 hd-font-size-24 hd-color-333 text-right">
            <b class="hd-prod-box">Sub-Total: <span class="price" id="hd-cart-sub-total">
                    <?php echo format_price_total($sub_total, $currency); ?></span></b>
        </div>
    </div>
    <div id="hd-checkout" class="row hd-margin-top-bottom-60">
        <div class="col-md-3 col-xs-6">
            <a class="btn btn-lg btn-default" href="/">Continue Shopping</a>
        </div>
        <div class="col-md-9 text-right hidden-xs hidden-sm">
            <!--img class="hd-display-inline-block hd-margin-right-15 hd-btn-pp"
                 src="/static/index/default/buy-now-with-paypal.png"/-->
            <?php
            if (empty($sold_out_sku)) {
                echo '<a class="btn btn-lg btn-warning" href="/shopping/confirmation.html">Checkout</a>';
            } else {
                echo '<a class="btn btn-lg btn-warning" disabled="" href="javascript:void(0);">Checkout</a>';
            }
            ?>
        </div>
        <div class="col-xs-6 text-right visible-xs visible-sm">
            <?php
            if (empty($sold_out_sku)) {
                echo '<a class="btn btn-lg btn-warning" href="/shopping/confirmation.html">Checkout</a>';
            } else {
                echo '<a class="btn btn-lg btn-warning" disabled="" href="javascript:void(0);">Checkout</a>';
            }
            ?>
        </div>
        <div class="col-xs-12 visible-xs visible-sm">
            <!--img class="hd-display-inline-block hd-margin-top-bottom-15 hd-btn-pp"
                 src="/static/index/default/buy-now-with-paypal.png"/-->
        </div>
    </div>
</div>
<input type="hidden" id="hd-cart-tk" value="<?php echo $cart_tk ?? ''; ?>"/>
