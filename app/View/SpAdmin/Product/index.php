<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['add_url' => '/spadmin/product/0']);
?>
    <div class="layui-fluid hd-padding-top60">
        <table id="prod_list" lay-filter="opt"></table>
    </div>
    <script>
        layui.use(['table'], function () {
            layui.table.render({
                elem: '#prod_list',
                url: window.location.href,
                cols: [
                    [
                        {field: 'product_id', hide: true},
                        {field: 'sku', title: 'SKU', align: 'center'},
                        {field: 'product_status_text', title: '商品状态', align: 'center'},
                        {field: 'product_name', title: '商品名称', align: 'center'},
                        {field: 'product_category', title: '所属类目', align: 'center'},
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
                            content: '/spadmin/product/' + obj.data.product_id
                        });
                        layer.full(layer_idx);
                        break;
                    case 'look':
                        layer.alert('前台功能尚未完成，敬请等待', open_alert_cfg);
                        break;
                }
            });
        });
    </script>
    <script type="text/html" id="operate">
        <i class="layui-icon layui-icon-edit" lay-event="edit"></i>
        <i class="layui-icon layui-icon-release" lay-event="look"></i>
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
