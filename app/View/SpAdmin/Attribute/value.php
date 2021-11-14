<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['back' => 1, 'add_url' => '/spadmin/attr-value/'.$group_id.'/0', 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-top60">
        <table id="attr_list" lay-filter="opt"></table>
    </div>
    <input type="hidden" id="hd-hash-tk" value="<?php echo $csrf_token ?? ''; ?>"/>
    <script>
        layui.use(['table'], function () {
            layui.table.render({
                elem: '#attr_list',
                url: window.location.href,
                cols: [
                    [
                        {field: 'value_name', title: '属性值', align: 'center'},
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
                            title: '',
                            closeBtn: 0,
                            skin: 'hd-open-edit',
                            content: '/spadmin/attr-value/' + obj.data.attr_group_id + '/' + obj.data.attr_value_id
                        });
                        layer.full(layer_idx);
                        break;
                    case 'attr':
                }
            });
        });
    </script>
    <script type="text/html" id="operate">
        <i class="layui-icon layui-icon-edit" lay-event="edit"></i>
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
