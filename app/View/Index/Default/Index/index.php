<?php
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="container">
        <div class="hd-height-15 visible-xs">&nbsp;</div>
        <div id="hd-banner-loop" class="carousel" data-ride="carousel">
            <div class="carousel-inner">
                <div class="item active">
                    <a href="">
                        <img src="https://www.glarrymusic.com/up/banner/20210813/1300x480-fb2.jpg"/>
                    </a>
                    <div class="carousel-caption"></div>
                </div>
                <div class="item">
                    <a href="">
                        <img src="https://www.glarrymusic.com/up/banner/20210810/1300X480-digital.jpg"/>
                    </a>
                    <div class="carousel-caption"></div>
                </div>
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
                <li data-target="#hd-banner-loop" data-slide-to="0" class="active"></li>
                <li data-target="#hd-banner-loop" data-slide-to="1"></li>
            </ol>
        </div>
        <div class="row hd-margin-top-bottom60" id="hd-flag-guarantee">
            <div class="col-md-3">
                <div class="hd-best-price">
                    <p></p>
                    <p>Best Price</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="hd-free-shipping">
                    <p></p>
                    <p>Free Shipping</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="hd-quality-guarantee">
                    <p></p>
                    <p>Quality Guarantee</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="hd-money-back">
                    <p></p>
                    <p>30 Days Money Back</p>
                </div>
            </div>
        </div>
        <div class="page-header text-center">
            <h2>Featured Products</h2>
        </div>
        <div class="row">
            <div class="col-md-3 hd-prod-box">
                <div class="thumbnail">
                    <a href="/prod-p1.html">
                        <img src="https://sw-shop.oss-cn-hongkong.aliyuncs.com/sp_1/prod_img/gitar/5cca9f4f0701acbbe99de13236b249dc_300_300.jpg?1628158151?1628760297"/>
                    </a>
                    <div class="caption">
                        <a href="/prod-p1.html" class="title"
                           title="Glarry Brass Trumpet Bb with 7C Mouthpiece Black Silver Golden Glarry Brass Trumpet Bb with 7C Mouthpiece Black Silver Golden2">
                            Glarry Brass Trumpet Bb with 7C Mouthpiece Black Silver Golden Glarry Brass Trumpet Bb with
                            7C Mouthpiece Black Silver Golden
                        </a>
                        <div class="price">$99.99</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 hd-prod-box">
                <div class="thumbnail">
                    <a href="/">
                        <img src="https://sw-shop.oss-cn-hongkong.aliyuncs.com/sp_1/prod_img/gitar/7e85d77b18a22f68e2e84e318fe8ea6a_300_300.jpg?1628158151?1628760297"/>
                    </a>
                    <div class="caption">
                        <a href="/" class="title"
                           title="Glarry Brass Trumpet Bb with 7C Mouthpiece Black Silver Golden Glarry Brass Trumpet Bb with 7C Mouthpiece Black Silver Golden2">
                            Glarry Brass Trumpet Bb with 7C Mouthpiece Black Silver Golden Glarry Brass Trumpet Bb with
                            7C Mouthpiece Black Silver Golden
                        </a>
                        <div class="price">$99.99</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-header text-center">
            <h2>We Do It For You</h2>
        </div>
        <div class="jumbotron">
            <p>Glarry offers great price and better quality goods and services for music lovers who have ideals,
                ambitions and make constant efforts to realize their musical dreams!</p>
            <p>&nbsp;</p>
            <p class="text-right"><a class="btn btn-danger">Our Story</a></p>
        </div>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
