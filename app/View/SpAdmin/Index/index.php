<?php \App\Helper\TemplateHelper::widget('sp_admin', 'header'); ?>
    <div id="hd-main">
        <ul class="layui-nav layui-nav-tree layui-nav-side" style="top: 60px; border-radius: 0;">
            <li class="layui-nav-item">
                <a href="javascript:void(0);" iframe="dashboard">
                    <i class="layui-icon layui-icon-home"></i>
                    数据表盘
                </a>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:void(0);" iframe="customer">
                    <i class="layui-icon layui-icon-user"></i>
                    用户管理
                </a>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:void(0);" iframe="order">
                    <i class="layui-icon layui-icon-cart"></i>
                    订单管理
                </a>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:void(0);">
                    <i class="layui-icon layui-icon-note"></i>
                    商品管理
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:void(0);" iframe="category">商品类目</a></dd>
                    <dd><a href="javascript:void(0);" iframe="product">商品列表</a></dd>
                    <dd><a href="javascript:void(0);" iframe="product/0">商品添加</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:void(0);">
                    <i class="layui-icon layui-icon-note"></i>
                    促销管理
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:void(0);" iframe="banner">广告图</a></dd>
                    <dd><a href="javascript:void(0);" iframe="coupon">优惠券</a></dd>
                    <dd><a href="javascript:void(0);" iframe="time-limited">限时限量</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:void(0);">
                    <i class="layui-icon layui-icon-set"></i>
                    商城设置
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:void(0);" iframe="config/web">商城信息</a></dd>
                    <dd><a href="javascript:void(0);" iframe="config/mail">邮件配置</a></dd>
                    <dd><a href="javascript:void(0);" iframe="language">多语言配置</a></dd>
                    <dd><a href="javascript:void(0);" iframe="currency">多币种配置</a></dd>
                    <dd><a href="javascript:void(0);" iframe="payment">支付方式</a></dd>
                </dl>
            </li>
        </ul>
        <div class="layui-tab" lay-allowClose="true" lay-filter="iframe">
            <ul class="layui-tab-title"></ul>
            <div class="layui-tab-content"></div>
        </div>
    </div>
<?php \App\Helper\TemplateHelper::widget('sp_admin', 'footer'); ?>