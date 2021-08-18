<?php
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Guitar</li>
        </ol>
    </div>
    <div class="container">
        <h2>
            Guitar
            <div class="btn-group pull-right">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    Sort By: Relevance
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="/"><i class="fa fa-thumbs-o-up text-danger"></i> Relevance</a></li>
                    <li><a href="/"><i class="fa fa-dollar text-danger"></i> High to Low</a></li>
                    <li><a href="/"><i class="fa fa-dollar text-danger"></i> Low to High</a></li>
                </ul>
            </div>
        </h2>
        <div class="row hd-margin-top-bottom15">
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
        <div class="row">
            <ul class="pager">
                <li class="previous">
                    <a href="#"><span>&larr;</span> Previous</a>
                </li>
                <li class="next">
                    <a href="#">Next <span>&rarr;</span></a>
                </li>
            </ul>
        </div>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
