<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-top30 hd-padding-bottom30">
        <div class="hd-margin-top-30">
            <div class="layui-card">
                <div class="layui-card-header layui-font-16">今日统计</div>
                <div class="layui-card-body">
                    <div class="layui-row hd-margin-top-10">
                        <div class="layui-col-xs3">
                            注册用户：0
                        </div>
                        <div class="layui-col-xs3">
                            下单用户：0
                        </div>
                        <div class="layui-col-xs3">
                            客单均价：<?php echo format_price_total(0, $currency); ?>
                        </div>
                    </div>
                    <div class="layui-row hd-margin-top-10">
                        <div class="layui-col-xs3">
                            订单总数：0
                        </div>
                        <div class="layui-col-xs3">
                            订单总额：<?php echo format_price_total(0, $currency); ?>
                        </div>
                        <div class="layui-col-xs3">
                            付款订单总数：0
                        </div>
                        <div class="layui-col-xs3">
                            付款订单总额：<?php echo format_price_total(0, $currency); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hd-margin-top-60">
            <div class="layui-card">
                <div class="layui-card-header layui-font-16">用户 & 订单总数累计</div>
                <div class="layui-card-body">
                    <div class="layui-row">
                        <div id="count_chart" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hd-margin-top-60">
            <div class="layui-card">
                <div class="layui-card-header layui-font-16">订单总额 & 客单均价</div>
                <div class="layui-card-body">
                    <div class="layui-row hd-margin-top-10">
                        <div id="amount_chart" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/static/echarts.min.js"></script>
    <script>
        var count_chart = echarts.init(document.getElementById('count_chart'));
        count_chart.setOption(<?php echo json_encode($count_option);?>);

        var amount_chart = echarts.init(document.getElementById('amount_chart'));
        amount_chart.setOption(<?php echo json_encode($amount_option);?>);
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
