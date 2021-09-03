<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['add_url' => '/spadmin/country/0', 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-top60">
        <table id="country_list" lay-filter="opt"></table>
    </div>
    <input type="hidden" id="hd-hash-tk" value="<?php echo $csrf_token ?? ''; ?>"/>
    <script>
        layui.use(['table', 'form'], function () {
            let tableIns = layui.table.render({
                elem: '#country_list',
                url: window.location.href,
                cols: [
                    [
                        {field: 'country_id', hide: true},
                        {field: 'country_name', title: '国家名称', align: 'center'},
                        {field: 'iso_code_2', title: '国家编码', align: 'center'},
                        {field: 'iso_code_3', title: '国家编码', align: 'center'},
                        {field: 'last_operation', title: '最后操作', align: 'center'},
                        {fixed: 'right', width: '100', align: 'center', toolbar: '#operate'}
                    ]
                ],
                height: 'full-90',
                page: true,
                limit: 20
            });

            layui.table.on('tool(opt)', function (obj) {
                switch (obj.event) {
                    case 'zone':
                        let zone_idx = layer.open({
                            type: 2,
                            title: '',
                            closeBtn: 0,
                            skin: 'hd-open-edit',
                            content: '/spadmin/zone?country_id=' + obj.data.country_id
                        });
                        layer.full(zone_idx);
                        break;
                    case 'edit':
                        let edit_idx = layer.open({
                            type: 2,
                            title: '',
                            closeBtn: 0,
                            skin: 'hd-open-edit',
                            content: '/spadmin/country/' + obj.data.country_id
                        });
                        layer.full(edit_idx);
                        break;
                    case 'delete':
                        layer.confirm('确定删除该项？', open_ask_cfg, function (idx) {
                            layer.close(idx);
                            $.ajax({
                                type: 'post',
                                url: '/spadmin/country/delete',
                                data: {
                                    addr_id: obj.data.country_id,
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
        <i class="layui-icon layui-icon-align-center hd-cursor-pointer" lay-event="zone"></i>
        <i class="layui-icon layui-icon-edit" lay-event="edit"></i>
        <i class="layui-icon layui-icon-delete" lay-event="delete"></i>
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
