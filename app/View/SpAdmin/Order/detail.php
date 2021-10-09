<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-bottom30">
        <div class="layui-card hd-margin-top-30">
            <div class="layui-card-header layui-font-16">订单 #<?php echo $order_info['order_number']; ?></div>
            <div class="layui-card-body">
                <div class="layui-row hd-margin-top-10">
                    <div class="layui-col-xs3">
                        用户名称：<?php echo $order_info['customer_name']; ?>
                    </div>
                    <div class="layui-col-xs3">
                        用户邮箱：<?php echo $order_info['customer_email']; ?>
                    </div>
                    <div class="layui-col-xs3">
                        下单IP：<?php echo long2ip($order_info['ip_number']); ?>
                    </div>
                    <div class="layui-col-xs3">
                        IP国家：<?php echo $order_info['ip_country_iso_code_2']; ?>
                    </div>
                </div>
                <div class="layui-row hd-margin-top-10">
                    <div class="layui-col-xs3">
                        生成时间：<?php echo $order_info['created_text']; ?>
                    </div>
                    <div class="layui-col-xs3">
                        订单类型：<?php echo $order_info['order_type'] == 'normal' ? '正常单' : '测试单'; ?>
                    </div>
                    <div class="layui-col-xs3">
                        下单站点：<?php echo $order_info['host_from']; ?>
                    </div>
                    <div class="layui-col-xs3">
                        下单设备：<?php echo $order_info['device_from'] == 'M' ? '移动端' : '电脑端'; ?>
                    </div>
                </div>
                <div class="layui-row hd-margin-top-10">
                    <div class="layui-col-xs3">
                        货运方式：<?php echo $order_info['shipping_method']; ?>
                    </div>
                    <div class="layui-col-xs3">
                        支付方式：<?php echo $order_info['payment_method']; ?>
                    </div>
                    <div class="layui-col-xs3">
                        下单状态：<?php echo $order_statuses[$order_info['order_status_id']] ?? ''; ?>
                    </div>
                    <div class="layui-col-xs3">
                        交易流水：<?php echo $paypal_info['txn_id'] ?? ''; ?>
                    </div>
                </div>
                <div class="layui-row hd-margin-top-10">
                    <div class="layui-col-xs12">
                        货运地址：
                        <?php
                        echo xss_text($order_address['first_name'] . ' ' . $order_address['last_name']);
                        echo ', ', xss_text($order_address['street_address']);
                        if (!empty($order_address['street_address_sub'])) {
                            echo ', ', xss_text($order_address['street_address_sub']);
                        }
                        echo ', ', xss_text($order_address['city']);
                        echo ', ', xss_text($order_address['zone_name'] . ' ' . $order_address['postcode']);
                        echo ', ', xss_text($order_address['country_name']);
                        echo ', ', xss_text($order_address['telephone']);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card hd-margin-top-30">
            <div class="layui-card-header layui-font-16">订单商品</div>
            <div class="layui-card-body">
                <table class="layui-table hd-margin-top-10">
                    <thead>
                    <tr>
                        <th>商品</th>
                        <th>SKU</th>
                        <th>单价</th>
                        <th>数量</th>
                        <th>总价</th>
                    </tr>
                    </thead>
                </table>
                <div class="layui-row hd-margin-top-30 hd-align-right">
                    <span>Subtotal:</span>
                    <span>$2.00</span>
                </div>
                <div class="layui-row hd-margin-top-10 hd-align-right">
                    <b>Total:</b>
                    <span>$2.00</span>
                </div>
            </div>
        </div>
        <div class="layui-card hd-margin-top-30">
            <div class="layui-card-header layui-font-16">订单状态</div>
            <div class="layui-card-body">
                <table class="layui-table hd-margin-top-10">
                    <thead>
                    <tr>
                        <th>更新时间</th>
                        <th class="hd-align-center">用户可见</th>
                        <th>状态</th>
                        <th>备注</th>
                        <th>操作人</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="layui-card hd-margin-top-30">
            <div class="layui-card-header layui-font-16">用户其他订单</div>
            <div class="layui-card-body">
                <table class="layui-table hd-margin-top-10">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>订单号</th>
                        <th>订单金额</th>
                        <th>订单状态</th>
                        <th>订单类型</th>
                        <th>生成时间</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script>
        layui.use(['form'], function () {
            layui.form.on('submit(customer_edit)', function (formObj) {
                form_submit(window.location.href, formObj.field);

                return false;
            });
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
