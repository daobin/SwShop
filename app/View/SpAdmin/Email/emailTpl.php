<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-top60">
        <table id="tpl_list" lay-filter="opt"></table>
    </div>
    <script>
        layui.use(['table'], function () {
            layui.table.render({
                elem: '#tpl_list',
                url: window.location.href,
                cols: [
                    [
                        {field: 'subject', title: '邮件标题', align: 'center'},
                        {field: 'template', title: '邮件模板', align: 'center'},
                        {field: 'last_operation', title: '最后操作', align: 'center'},
                        {fixed: 'right', width: '100', align: 'center', toolbar: '#operate'}
                    ]
                ]
            });

            layui.table.on('tool(opt)', function (obj) {
                switch (obj.event) {
                    case 'edit':
                        var layer_idx = layer.open({
                            type: 2,
                            title: '编辑',
                            skin: 'hd-open-edit',
                            content: '/spadmin/email-tpl/' + obj.data.template
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