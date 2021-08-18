<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>

    <title>Hello Sw Shop</title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>

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
    <div class="container hd-margin-top-bottom15">
        <div class="row">
            <div class="col-md-2 text-center">
                <a href="/">
                    <img src="http://www.gm-php7.com/public/static/index/pc/images/Glarry_Logo.png" alt="Sw Shop"/>
                </a>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="hd-search form-control" placeholder="Search for ..."/>
                        <span class="btn btn-warning input-group-addon"><i class="glyphicon glyphicon-search hd-font-size24"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 hidden-xs text-right" id="hd-nav-icon">
                <a class="cs" href="/" data-toggle="tooltip" title="Customer Service"></a>
                <a class="order-tracking" href="/" data-toggle="tooltip" title="Order Tracking"></a>
                <a class="cart" href="/" data-toggle="tooltip" title="Shopping Cart"></a>
                <a class="cart2 hd-display-none" href="/"></a>
                <?php
                if(empty($customer_id)){
                    echo '<a class="login" href="/login.html" data-toggle="tooltip" title="Register / Sign In"></a>';
                }else{
                    echo '<a class="logined" data-toggle="tooltip" title="Account" href="/account.html"></a>';
                }
                ?>
            </div>
        </div>
    </div>
    <div id="hd-header-navbar" class="hidden-xs hidden-sm">
        <div class="container">
            <ul class="nav navbar-nav">
                <li><a href="/guitar-c2.html">Guitar</a></li>
                <li class="dropdown">
                    <a data-toggle="dropdown">String <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/">String 1</a></li>
                        <li><a href="/">String 2</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>