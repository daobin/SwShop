<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['add_url' => '/spadmin/currency/0', 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-top60">
        <table id="currency_list" lay-filter="opt"></table>
    </div>
    <input type="hidden" id="hd-hash-tk" value="<?php echo $csrf_token ?? ''; ?>"/>
    <script>
        layui.use(['table'], function () {
            layui.table.render({
                elem: '#currency_list',
                url: window.location.href,
                cols: [
                    [
                        {field: 'currency_name', title: '币种名称', align: 'center'},
                        {field: 'currency_code', title: '币种编码', align: 'center'},
                        {
                            title: '币种符号', align: 'center', templet: function (d) {
                            return d.symbol_left + d.symbol_right;
                        }
                        },
                        {field: 'value', title: '币种汇率', align: 'center'},
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
                            content: '/spadmin/currency/' + obj.data.currency_code
                        });
                        layer.full(layer_idx);
                        break;
                    case 'delete':
                        layer.confirm('确定删除该项？', open_ask_cfg, function (idx) {
                            layer.close(idx);
                            $.ajax({
                                type: 'post',
                                url: '/spadmin/currency/delete',
                                data: {
                                    code: obj.data.currency_code,
                                    hash_tk: $.trim($('#hd-hash-tk').val())
                                },
                                success: function (res) {
                                    if (res.msg != undefined && res.msg != '') {
                                        layer.alert(res.msg, open_alert_cfg);
                                    }
                                    if (res.status == 'success') {
                                        obj.del();
                                    }
                                },
                                error: function () {
                                    layer.alert('未知错误，请稍候刷新页面重试！', open_alert_cfg);
                                }
                            });
                        });
                        break;
                }
            });
        });
    </script>
    <script type="text/html" id="operate">
        <i class="layui-icon layui-icon-edit" lay-event="edit"></i>
        <i class="layui-icon layui-icon-delete" lay-event="delete"></i>
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
