<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>商城后台管理 - Sw Shop</title>

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
    <script>
        window.document.oncontextmenu = function () {
            return false;
        };

        // 页内 Iframe 导航映射
        var iframeNavMaps = [];

        layui.use(['jquery', 'element'], function () {
            let $ = layui.jquery;
            let element = layui.element;

            element.on('tabDelete(iframe)', function (data) {
                iframeNavMaps.splice(data.index, 1);
            });

            $('a').click(function () {
                if ($(this).attr('iframe') == undefined) {
                    return;
                }

                let iframe = $.trim($(this).attr('iframe'));
                let layId = iframe.replace('/', '_');
                if (iframeNavMaps.indexOf(layId) > -1) {
                    element.tabChange('iframe', layId);
                } else {
                    iframeNavMaps.push(layId);

                    let iframeHtml = '<iframe src="/spadmin/' + iframe + '.html"></iframe>';
                    element.tabAdd('iframe', {
                        id: layId,
                        title: $(this).html(),
                        content: iframeHtml
                    });
                    element.tabChange('iframe', layId);
                }
            });

            // 初始化数据表盘
            $('#hd-main .layui-nav .layui-nav-item a[iframe=dashboard]').click();
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
                    <a>今日用户<span class="layui-badge">99</span></a>
                </li>
                <li class="layui-nav-item">
                    <a>今日订单<span class="layui-badge">99</span></a>
                </li>
                <li class="layui-nav-item">
                    <a>待处理订单<span class="layui-badge-dot"></span></a>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right">
                <li class="layui-nav-item">
                    <a><img src="//t.cn/RCzsdCq" class="layui-nav-img"/><?php echo xss_text($admin_name); ?></a>
                    <dl class="layui-nav-child">
                        <dd><a href="/spadmin/logout.html" class="hd-color-red">安全退出</a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>
</div>
<div id="hd-main">
    <?php \App\Helper\TemplateHelper::widget('sp_admin', 'leftNav'); ?>
    <div class="hd-main-content">
        <div class="layui-tab" lay-allowClose="true" lay-filter="iframe">
            <ul class="layui-tab-title"></ul>
            <div class="layui-tab-content"></div>
        </div>
    </div>
</div>
</body>
</html>