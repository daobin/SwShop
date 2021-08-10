<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-top60">
        <table id="banner_list" lay-filter="opt"></table>
    </div>
    <script>
        layui.use(['table'], function () {
            layui.table.render({
                elem: '#banner_list',
                url: window.location.href,
                cols: [
                    [
                        {field: 'banner_id', hide: true},
                        {field: 'title', title: '广告项目', align: 'center'},
                        {field: 'code', title: '广告编码', align: 'center'},
                        {
                            title: '广告状态', align: 'center', templet: function (d) {
                            if(d.banner_status == 1){
                                return '开启';
                            }else{
                                return '<div class="layui-bg-red">关闭</div>';
                            }
                        }
                        },
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
                            content: '/spadmin/banner/' + obj.data.banner_id
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
