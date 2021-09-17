<?php
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
                <?php if ($is_post) { ?>
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
        <?php if ($is_post) { ?>
            <div class="panel panel-default hd-margin-top-30" id="hd-order-info">
                <div class="panel-heading">Order Information</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2">Order ID:</div>
                        <div class="col-md-4">SP20210914</div>
                        <div class="col-md-2">Date:</div>
                        <div class="col-md-4">2021-09-14</div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">Shipping Method:</div>
                        <div class="col-md-4">Free Shipping</div>
                        <div class="col-md-2">Payment Method:</div>
                        <div class="col-md-4">PayPal</div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">Shipping Address:</div>
                        <div class="col-md-10">
                            Bertha RR, 699 Snider Street, Englewood, American Samoa, 80112, United States, 720-249-7522
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
                    <div class="row"></div>
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
                <tr>
                    <td class="product">
                        <div class="hd-display-inline-block">
                            <a href="/folk-pop-guitar-p1.html" title="My Guitar">
                                <img src="https://sw-shop.oss-cn-hongkong.aliyuncs.com/sp_1/prod_img/gitar/5cca9f4f0701acbbe99de13236b249dc_100_100.jpg?1630302700">
                            </a>
                        </div>
                        <div class="hd-display-inline-block">
                            <p>SKU: GT0001</p>
                            <p class="hidden-xs hidden-sm">
                                <a href="/folk-pop-guitar-p1.html" title="My Guitar">
                                    My GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy
                                    Guitar
                                    My GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy Guitar
                                    My GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy Guitar0000000
                                </a>
                            </p>
                        </div>
                    </td>
                    <td class="text-center">$2.00</td>
                    <td class="text-center">2</td>
                    <td class="text-center hidden-xs hd-prod-total">$4.00</td>
                </tr>
                <tr>
                    <td class="product">
                        <div class="hd-display-inline-block">
                            <a href="/folk-pop-guitar-p1.html" title="My Guitar">
                                <img src="https://sw-shop.oss-cn-hongkong.aliyuncs.com/sp_1/prod_img/gitar/5cca9f4f0701acbbe99de13236b249dc_100_100.jpg?1630302700">
                            </a>
                        </div>
                        <div class="hd-display-inline-block">
                            <p>SKU: GT0001</p>
                            <p class="hidden-xs hidden-sm">
                                <a href="/folk-pop-guitar-p1.html" title="My Guitar">
                                    My GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy
                                    Guitar
                                    My GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy Guitar
                                    My GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy GuitarMy Guitar0000000
                                </a>
                            </p>
                        </div>
                    </td>
                    <td class="text-center">$2.00</td>
                    <td class="text-center">2</td>
                    <td class="text-center hidden-xs hd-prod-total">$4.00</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right hd-font-size-18">
                        <div class="hd-margin-top-15">Sub-total: <span
                                    class="hd-color-price hd-padding-left-15">$8.00</span></div>
                        <div class="hd-margin-top-15">Shipping Fee: <span
                                    class="hd-color-price hd-padding-left-15">$0.00</span></div>
                        <div class="hd-margin-top-15">Insurance Fee: <span
                                    class="hd-color-price hd-padding-left-15">$2.00</span></div>
                        <div class="hd-font-weight-bold hd-margin-top-15">Total: <span
                                    class="hd-color-price hd-padding-left-15">$10.00</span></div>
                    </td>
                </tr>
                </tbody>
            </table>
        <?php } else { ?>
            <form class="form-horizontal" method="post" autocomplete="off">
                <div class="form-group hd-margin-top-30">
                    <label class="control-label col-md-2">Email Address <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="email"/>
                    </div>
                </div>
                <div class="form-group hd-margin-top-30">
                    <label class="control-label col-md-2">Order Number <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="number"/>
                    </div>
                </div>
                <div class="form-group hd-margin-top-30">
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
