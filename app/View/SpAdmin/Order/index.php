<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header');
?>
    <div class="layui-fluid hd-padding-top60">
        <table id="order_list" lay-filter="opt"></table>
    </div>
    <script>
        layui.use(['table'], function () {
            layui.table.render({
                elem: '#order_list',
                url: window.location.href,
                cols: [
                    [
                        {field: 'order_number', title: '订单号', align: 'center'},
                        {field: 'customer_email', title: '用户邮箱', align: 'center'},
                        {field: 'customer_name', title: '用户名', align: 'center'},
                        {field: 'payment_method', title: '支付方式', align: 'center'},
                        {field: 'order_status', title: '订单状态', align: 'center'},
                        {field: 'order_total', title: '订单金额', align: 'center'},
                        {field: 'currency_code', title: '交易货币', align: 'center'},
                        {field: 'last_operation', title: '最后操作', align: 'center'},
                        {fixed: 'right', width: '100', align: 'center', toolbar: '#operate'}
                    ]
                ]
            });

            layui.table.on('tool(opt)', function (obj) {
                switch (obj.event) {
                    case 'edit':
                        let layer_idx = layer.open({
                            type: 2,
                            title: '编辑',
                            skin: 'hd-open-edit',
                            content: '/spadmin/order/' + obj.data.order_number
                        });
                        layer.full(layer_idx);
                        break;
                }
            });
        });
    </script>
    <script type="text/html" id="operate">
        <i class="layui-icon layui-icon-edit" lay-event="edit"></i>
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
