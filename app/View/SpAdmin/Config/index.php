<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header');
?>
    <div class="layui-fluid padding-top60">
        <table id="cfg_list" lay-filter="opt"></table>
    </div>
    <script>
        layui.use(['table'], function () {
            layui.table.render({
                elem: '#cfg_list',
                url: window.location.href,
                cols: [
                    [
                        {field: 'config_id', hide: true},
                        {field: 'config_name', title: '配置项', align: 'center'},
                        {field: 'config_key', title: '配置键', align: 'center'},
                        {field: 'config_value', title: '配置值', align: 'center'},
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
                            content: '/spadmin/config/' + obj.data.config_key.toLocaleLowerCase()
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
