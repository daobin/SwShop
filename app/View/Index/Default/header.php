<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>

    <title><?php echo $tkd_title;?></title>
    <meta name="keywords" content="<?php echo $tkd_keywords;?>"/>
    <meta name="description" content="<?php echo $tkd_description;?>"/>

    <!--[if lt IE 9]>
    <script type="text/javascript" src="/static/html5shiv.min.js"></script>
    <script type="text/javascript" src="/static/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="/static/fa/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.css"/>
    <link rel="stylesheet" href="/static/index/default/common.css<?php echo $timestamp ?? ''; ?>"/>

    <script src="/static/jquery/jquery-3.6.0.min.js"></script>
    <script src="/static/bootstrap/js/bootstrap.js"></script>
</head>
<body>
<div id="hd-header">
    <div class="container hd-margin-top-bottom-15">
        <div class="row">
            <div class="col-md-2 text-center">
                <?php
                if (!empty($website_logo)) {
                    echo '<a href="/"><img class="logo" src="', $website_logo, '" alt="', ($website_name ?? ''), '" /></a>';
                }
                ?>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="hd-search form-control" placeholder="Search for ..."
                               value="<?php echo $keywords ?? ''; ?>"/>
                        <span class="btn input-group-addon hd-btn-search">
                            <i class="glyphicon glyphicon-search hd-font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-5 hidden-xs text-right" id="hd-nav-icon">
                <a class="cs" href="/customer-service.html" data-toggle="tooltip" title="Customer Service"></a>
                <a class="order-tracking" href="/order-tracking.html" data-toggle="tooltip" title="Order Tracking"></a>
                <?php
                if (empty($cart_qty)) {
                    echo '<a class="cart" href="/shopping/cart.html" data-toggle="tooltip" title="Shopping Cart"></a>';
                } else {
                    echo '<a class="cart2" href="/shopping/cart.html" data-toggle="tooltip" title="Shopping Cart"><span class="badge">', $cart_qty, '</span></a>';
                }

                if (empty($customer_id)) {
                    echo '<a class="login" href="/login.html" data-toggle="tooltip" title="Register / Sign In"></a>';
                } else {
                    echo '<a class="logined" data-toggle="tooltip" title="Account" href="/account.html"></a>';
                }
                ?>
            </div>
        </div>
    </div>
    <div id="hd-header-navbar" class="hidden-xs hidden-sm">
        <div class="container">
            <ul class="nav navbar-nav">
                <?php
                if (!empty($cate_list)) {
                    foreach ($cate_list as $cate_info) {
                        if (!$cate_info['category_status']) {
                            continue;
                        }

                        $cate_link = empty($cate_info['category_url']) ? 'category' : process_url_string($cate_info['category_url']);
                        $cate_link .= '-c' . $cate_info['product_category_id'] . '.html';
                        $cate_link = empty($cate_info['redirect_link']) ? $cate_link : $cate_info['redirect_link'];
                        if (empty($cate_info['children'])) {
                            echo '<li><a href="/', $cate_link, '">', xss_text($cate_info['category_name']), '</a></li>';
                            continue;
                        }

                        echo '<li class="dropdown">';
                        echo '<a data-toggle="dropdown">', xss_text($cate_info['category_name']), ' <span class="caret"></span></a>';
                        echo '<ul class="dropdown-menu">';
                        foreach ($cate_info['children'] as $sub_cate) {
                            $cate_link = empty($sub_cate['category_url']) ? 'category' : process_url_string($sub_cate['category_url']);
                            $cate_link .= '-c' . $sub_cate['product_category_id'] . '.html';
                            $cate_link = empty($sub_cate['redirect_link']) ? $cate_link : $sub_cate['redirect_link'];
                            echo '<li><a href="/', $cate_link, '">', xss_text($sub_cate['category_name']), '</a></li>';
                        }
                        echo '</ul></li>';
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</div>