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
    <script src="/static/spadmin/hd.admin.js<?php echo \App\Helper\ConfigHelper::get('web_info.timestamp'); ?>"></script>
    <script>
        // 页内 Iframe 导航映射
        var iframeNavMaps = [];

        layui.use(['element'], function () {
            let element = layui.element;
            let iframe_reload = null;

            $('a').click(function () {
                if ($(this).attr('to-iframe') != undefined) {
                    $('a[iframe=' + $(this).attr('to-iframe') + ']').click();
                    $(this).find('span').remove();
                    return;
                }

                if ($(this).attr('iframe') == undefined) {
                    return;
                }

                let iframe = $.trim($(this).attr('iframe'));
                let lay_id = iframe.replace('/', '_');
                if (iframeNavMaps.indexOf(lay_id) > -1) {
                    iframe_reload = true;
                    element.tabChange('iframe', lay_id);
                } else {
                    iframeNavMaps.push(lay_id);

                    let iframeHtml = '<iframe src="/spadmin/' + iframe + '.html"></iframe>';
                    element.tabAdd('iframe', {
                        id: lay_id,
                        title: $(this).html(),
                        content: iframeHtml
                    });
                    element.tabChange('iframe', lay_id);
                }
            });

            element.on('tab(iframe)', function (data) {
                if(iframe_reload){
                    if(iframe_reload === true){
                        $('#hd-main .layui-tab-content iframe').get()[data.index].contentWindow.location.reload(true);
                    }else{
                        $('#hd-main .layui-tab-content iframe').get()[data.index].contentWindow.location.href = iframe_reload;
                    }
                    iframe_reload = false;
                }
            });

            element.on('tabDelete(iframe)', function (data) {
                iframeNavMaps.splice(data.index, 1);
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
                    <a to-iframe="customer">今日用户<span class="layui-badge">99</span></a>
                </li>
                <li class="layui-nav-item">
                    <a to-iframe="order">今日订单<span class="layui-badge">99</span></a>
                </li>
                <li class="layui-nav-item">
                    <a to-iframe="order">待处理订单<span class="layui-badge-dot"></span></a>
                </li>
            </ul>
            <ul class="layui-nav layui-layout-right">
                <li class="layui-nav-item">
                    <span id="date_time"></span>
                </li>
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
<script>
    window.onload = function () {
        show_date_time('#date_time');
    }
</script>
</body>
</html>