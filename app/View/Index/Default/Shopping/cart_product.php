<div class="container">
    <div class="page-header hd-border-none">
        <h2 class="hd-color-333">Shopping Cart</h2>
    </div>
    <table id="hd-cart-products" class="table hd-margin-top-30">
        <tr>
            <th width="500" class="hidden-xs">Product</th>
            <th class="visible-xs">Product</th>
            <th class="text-center">Price</th>
            <th class="text-center">Quantity</th>
            <th class="text-center hidden-xs">Total</th>
            <th class="text-center">Options</th>
        </tr>
        <tr>
            <td class="product">
                <div class="hd-display-inline-block">
                    <img src="https://sw-shop.oss-cn-hongkong.aliyuncs.com/sp_1/prod_img/gitar/5cca9f4f0701acbbe99de13236b249dc_100_100.jpg"/>
                </div>
                <div class="hd-display-inline-block">
                    <p>SKU: GT0001</p>
                    <p class="hidden-xs hidden-sm">
                        <a href="/ppp-p1.html" title="">
                            Glarry ST3 Electric Guitar Blue
                            Glarry ST3 Electric Guitar Blue
                            Glarry ST3 Electric Guitar Blue
                            Glarry ST3 Electric Guitar Blue
                            Glarry ST3 Electric Guitar Blue
                        </a>
                    </p>
                </div>
            </td>
            <td class="text-center">$180.00</td>
            <td class="text-center">
                <div class="input-group hd-width-150 hd-margin-left-right-auto hd-up-cart-prod">
                    <div class="btn input-group-addon hd-prod-qty-minus">-</div>
                    <input type="text" class="form-control hd-prod-qty-val text-center" value="1"/>
                    <div class="btn input-group-addon hd-prod-qty-plus">+</div>
                    <input type="hidden" class="hd-sku" value="GT0001"/>
                </div>
            </td>
            <td class="text-center hidden-xs">$180.00</td>
            <td class="text-center">
                <i data-toggle="popover" title="ABC" data-content="ARE" class="glyphicon glyphicon-trash hd-color-888 hd-cart-prod-remove hd-font-size-18 hd-cursor-pointer"></i>
            </td>
        </tr>
    </table>
    <div class="row hd-margin-top-bottom-60 clearfix">
        <div class="col-md-6 hd-font-size-18 text-success">
            <i class="fa fa-lock hd-font-size-24"></i>
            <span>Shopping is Safe and Secure</span>
        </div>
        <div class="col-md-6 hd-font-size-24 hd-color-333 text-right">
            <b class="hd-prod-box">Sub-Total: <span class="price" id="hd-cart-sub-total">$180.00</span></b>
        </div>
    </div>
    <div id="hd-checkout" class="row hd-margin-top-bottom-60">
        <div class="col-md-3">
            <a class="btn btn-lg btn-default" href="/">Continue Shopping</a>
        </div>
        <div class="col-md-9 text-right">
            <img class="hd-display-inline-block hd-margin-right-15"
                 src="/static/index/default/buy-now-with-paypal.png"/>
            <a class="btn btn-lg btn-warning" href="/shopping/confirmation.html">Checkout</a>
        </div>
    </div>
</div>
<input type="hidden" id="hd-cart-tk" value="<?php echo $cart_tk ?? ''; ?>"/>
<script>
    $('body>div.container').css('min-height', ($(window).height() - 269) + 'px');
</script>
