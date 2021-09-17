<?php
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Order History</li>
        </ol>
    </div>
    <div class="container">
        <ul class="nav nav-pills nav-justified bg-info hd-border-radius-4">
            <li><a href="/account.html">My Profile</a></li>
            <li><a href="/password.html">Change Password</a></li>
            <li><a href="/address.html">Address Book</a></li>
            <li class="active"><a>Order History</a></li>
        </ul>
        <div class="page-header">
            <h2 class="hd-color-333">Order History</h2>
        </div>
        <div class="hd-margin-top-30">
            <div class="hd-order-one panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-8 hd-font-weight-bold">
                            Order #SP20210914
                            |
                            Date: 2021-09-14
                            |
                            Total: $29.99
                        </div>
                        <div class="col-md-4 text-right">
                            Order Status: <b class="text-danger">Pending</b>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-1">
                                    <a href="/p-p1.html" title="GoodGuitar for">
                                        <img src="https://sw-shop.oss-cn-hongkong.aliyuncs.com/sp_1/prod_img/def/83031e774a8cdf53a9c3e050bdaa593a_100_100.jpg?1629972257"
                                             alt="GoodGuitar for"/>
                                    </a>
                                </div>
                                <div class="col-md-6 hd-prod-title">
                                    <a href="/p-p1.html" title="GoodGuitar for">
                                        GoodGuitar forGoodGuitar forGoodGuitar forGoodGuitar forGoodGuitar forGoodGuitar
                                        for
                                        GoodGuitar forGoodGuitar forGoodGuitar forGoodGuitar for
                                        GoodGuitar forGoodGuitar forGoodGuitar forGoodGuitar forGoodGuitar for
                                    </a>
                                </div>
                                <div class="col-md-2 col-md-offset-1 hd-v-center">
                                    1
                                </div>
                                <div class="col-md-2 hd-v-center">
                                    $19.99
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-right hd-v-center">
                            <a href="/order/1.html" class="btn btn-info">Order Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row hd-margin-top-60">
            <ul class="pager">
                <li class="previous">
                    <a><span>&larr;</span> Previous</a>
                </li>
                <li class="next">
                    <a>Next <span>&rarr;</span></a>
                </li>
            </ul>
        </div>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
