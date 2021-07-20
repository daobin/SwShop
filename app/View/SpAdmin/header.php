<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hello Sw Shop</title>

    <meta name="renderer" content="webkit|ie-comp|ie-stand"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"/>

    <link rel="stylesheet" href="/static/layui/css/layui.css"/>
    <link rel="stylesheet" href="/static/spadmin/hd.admin.css"/>

    <!--[if lt IE 9]>
    <script type="text/javascript" src="/static/html5shiv.min.js"></script>
    <script type="text/javascript" src="/static/respond.min.js"></script>
    <![endif]-->
    <script src="/static/jquery/jquery-3.6.0.min.js"></script>
    <script src="/static/layui/layui.js"></script>
    <script>
        // 页内 Iframe 导航映射
        var iframeNavMaps = [];

        // LayUI 插件加载
        var element;
        layui.use(['element'], function () {
            element = layui.element;
            element.on('tabDelete(iframe)', function (data) {
                iframeNavMaps.splice(data.index, 1);
            });
        });
    </script>
</head>
<body>
<div id="hd-header">
    <div class="layui-layout-admin">
        <div class="layui-header">
            <div class="layui-logo hd-color-white">
                商城后台管理系统
            </div>
            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item">
                    <a href="/spadmin/customers">今日用户<span class="layui-badge">99</span></a>
                </li>
                <li class="layui-nav-item">
                    <a href="/spadmin/orders">今日订单<span class="layui-badge">99</span></a>
                </li>
                <li class="layui-nav-item">
                    <a href="/spadmin/orders">待处理订单<span class="layui-badge-dot"></span></a>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right">
                <li class="layui-nav-item">
                    <a href="javascript:void(0);"><img src="//t.cn/RCzsdCq" class="layui-nav-img"/>管理员</a>
                    <dl class="layui-nav-child">
                        <dd><a href="/spadmin">修改信息</a></dd>
                        <dd><a href="/spadmin/logout.html">安全退出</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>
</div>