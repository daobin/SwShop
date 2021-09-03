<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sw Shop</title>

    <meta name="renderer" content="webkit|ie-comp|ie-stand"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"/>

    <link rel="stylesheet" href="/static/layui/css/layui.css"/>
    <link rel="stylesheet" href="/static/spadmin/hd.admin.css<?php echo $timestamp ?? ''; ?>"/>

    <!--[if lt IE 9]>
    <script type="text/javascript" src="/static/html5shiv.min.js"></script>
    <script type="text/javascript" src="/static/respond.min.js"></script>
    <![endif]-->
    <script src="/static/layui/layui.js"></script>
    <script src="/static/spadmin/hd.admin.js<?php echo $timestamp ?? ''; ?>"></script>
    <script src="/static/spadmin/hd.image.js<?php echo $timestamp ?? ''; ?>"></script>
</head>
<body>
<?php if (!empty($show_top_line)){ ?>
<div class="layui-fluid" id="hd-top-line">
    <div class="layui-row">
        <div class="layui-col-xs10">
            <?php
            if (empty($add_url)) {
                echo '&nbsp;';
            } else {
                echo '<a class="layui-btn layui-btn-sm hd-opt-add" href="', $add_url, '"><i class="layui-icon layui-icon-addition"></i></a>';
            }
            ?>
        </div>
        <div class="layui-col-xs2 hd-align-right">
            <?php
            if(!empty($back)){
                echo '<a class="hd-layer-close layui-btn layui-btn-sm layui-btn-warm">&lt;&lt; Back</a>';
            }
            ?>
            <a class="layui-btn layui-btn-sm layui-btn-normal hd-opt-refresh">
                <i class="layui-icon layui-icon-refresh"></i>
            </a>
        </div>
    </div>
    <hr class="layui-bg-gray"/>
</div>
<?php
}
