<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header');
?>
    <div class="layui-fluid hd-padding-top60">
        <table id="customer_list" lay-filter="opt"></table>
    </div>
    <script>
        layui.use(['table'], function () {
            layui.table.render({
                elem: '#customer_list',
                url: window.location.href,
                cols: [
                    [
                        {field: 'customer_id', hide: true},
                        {field: 'customer_email', title: '用户邮箱', align: 'center'},
                        {field: 'customer_name', title: '用户名', align: 'center'},
                        {field: 'customer_type', title: '用户类型', align: 'center'},
                        {field: 'customer_from', title: '来源站点&终端', align: 'center'},
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
                            content: '/spadmin/customer/' + obj.data.customer_id
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
