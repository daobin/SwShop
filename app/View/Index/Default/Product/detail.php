<?php
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/guitar-c1.html">Guitar</a></li>
            <li class="active">My Guitar</li>
        </ol>
    </div>
    <div class="container hd-margin-top-30">
        <div class="row">
            <div class="col-md-6">
                <div id="hd-main-image-loop" class="carousel">
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="https://sw-shop.oss-cn-hongkong.aliyuncs.com/sp_1/prod_img/gitar/5cca9f4f0701acbbe99de13236b249dc_800_800.jpg?1628158151"/>
                        </div>
                        <div class="item">
                            <img src="https://sw-shop.oss-cn-hongkong.aliyuncs.com/sp_1/prod_img/gitar/7e85d77b18a22f68e2e84e318fe8ea6a_800_800.jpg?1628158151"/>
                        </div>
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
            </div>
            <div class="col-md-6" id="hd-prod-info">
                <div class="hd-margin-top-30 visible-xs visible-sm">&nbsp;</div>
                <h1><?php echo xss_text($prod_info['desc']['product_name'] ?? ''); ?></h1>
                <div class="row hd-margin-top-30">
                    <div class="col-md-3 hd-font-weight-bold">SKU :</div>
                    <div class="col-md-9"><?php echo xss_text($prod_info['sku'] ?? '-- GT0001'); ?></div>
                </div>
                <div class="row hd-margin-top-30">
                    <div class="col-md-3 hd-font-weight-bold">Ship From :</div>
                    <div class="col-md-9">US Warehouse (Arrivals in 5-7 business days)</div>
                </div>
                <div class="row hd-margin-top-30">
                    <div class="col-md-12 hd-prod-box">
                        <span class="price hd-display-inline-block hd-margin-right-15">$149.99</span>
                        <span class="origin hd-display-inline-block hd-margin-right-15">&nbsp;$299.99&nbsp;</span>
                        <span class="off hd-display-inline-block hd-margin-right-15">50% OFF</span>
                    </div>
                </div>
                <div class="row hd-margin-top-30">
                    <div class="col-md-4">
                        <div class="input-group hd-width-150">
                            <div class="btn input-group-addon hd-prod-qty-minus">-</div>
                            <input type="text" class="form-control hd-prod-qty-val text-center" value="1"/>
                            <div class="btn input-group-addon hd-prod-qty-plus">+</div>
                        </div>
                    </div>
                </div>
                <div class="row hd-margin-top-30">
                    <div class="col-md-4">
                        <button class="btn btn-lg btn-warning hd-add-to-cart">Add To Cart</button>
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
            <div id="hd-prod-desc" class="hd-prod-detail-content">
                <h3>Introductions:</h3>
                <p>
                    The newly upgraded Glarry GP Ⅱ Electric Bass features a premium Basswood body alongside a Hard Maple
                    neck and fingerboard.
                    Other improvements include upgraded Split Single-Coil pickup, upgraded bass strings and a Bone nut.
                    It is an awesome and affordable classic bass guitar priced
                    for beginners and music lovers. Out of the box, the upgraded GP Ⅱ will have you playing in the
                    bedroom, the studio or the stage in no time.
                </p>
                <p>
                    <img src="https://www.glarrymusic.com/up/f_attachment/product/G17000088/G17000088-16.jpg"/>
                </p>
                <p>
                    <img src="https://www.glarrymusic.com/up/f_attachment/product/G17000088/G17000088-13.jpg"/>
                </p>
                <p>
                    <img src="https://www.glarrymusic.com/up/f_attachment/product/G17000088/G17000088-18.jpg"/>
                </p>
            </div>
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
    <input type="hidden" class="hd-sku" value="GT0001"/>
    <input type="hidden" id="hd-cart-tk" value="<?php echo $cart_tk ?? ''; ?>"/>
<?php if ($device == 'PC') { ?>
    <script src="/static/jquery/jquery.zoom.min.js"></script>
    <script>
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
    </script>
    <?php
}
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
