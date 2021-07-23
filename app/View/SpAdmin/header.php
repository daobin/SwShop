<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sw Shop</title>

    <meta name="renderer" content="webkit|ie-comp|ie-stand"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"/>

    <link rel="stylesheet" href="/static/layui/css/layui.css"/>
    <link rel="stylesheet"
          href="/static/spadmin/hd.admin.css<?php echo \App\Helper\ConfigHelper::get('web_info.timestamp'); ?>"/>

    <!--[if lt IE 9]>
    <script type="text/javascript" src="/static/html5shiv.min.js"></script>
    <script type="text/javascript" src="/static/respond.min.js"></script>
    <![endif]-->
    <script src="/static/layui/layui.js"></script>
    <script src="/static/spadmin/hd.admin.js<?php echo \App\Helper\ConfigHelper::get('web_info.timestamp'); ?>"></script>
</head>
<body>
<?php if ($show_top_line){ ?>
<div class="layui-fluid" id="hd-top-line">
    <div class="layui-row">
        <div class="layui-col-xs-offset11 layui-col-xs1 hd-text-right">
            <a class="layui-btn layui-btn-sm layui-btn-normal"><i class="layui-icon layui-icon-refresh"></i></a>
        </div>
    </div>
    <hr class="layui-bg-gray"/>
</div>
<?php
}
