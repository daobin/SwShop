<?php
\App\Helper\TemplateHelper::widget('sp_bind', 'header', ['add_url' => '/spbind/shop/0', 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-top60">
        <table id="admin_list" lay-filter="opt"></table>
    </div>
    <input type="hidden" id="hd-hash-tk" value="<?php echo $csrf_token ?? ''; ?>"/>
    <script>
        layui.use(['table'], function () {
            layui.table.render({
                elem: '#admin_list',
                url: window.location.href,
                cols: [
                    [
                        {field: 'shop_name', title: '店铺', align: 'center'},
                        {
                            title: '店铺状态',
                            align: 'center',
                            templet: function (d) {
                                switch (d.shop_status) {
                                    case 1:
                                        return '开启';
                                    default:
                                        return '<div class="layui-bg-red">关闭</div>';
                                }
                            }
                        },
                        {field: 'shop_domain', title: '主域名', align: 'center'},
                        {field: 'created_text', title: '新增时间', align: 'center'},
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
                            content: '/spbind/shop/' + obj.data.shop_id
                        });
                        layer.full(layer_idx);
                        break;
                    case 'delete':
                        layer.confirm('确定删除该项？', open_ask_cfg, function (idx) {
                            layer.close(idx);
                            $.ajax({
                                type: 'post',
                                url: '/spbind/shop/delete',
                                data: {
                                    shop_id: obj.data.shop_id,
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
\App\Helper\TemplateHelper::widget('sp_bind', 'footer');
