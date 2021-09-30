<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-top60">
        <form class="layui-form hd-padding-top10" autocomplete="off">
            <div class="layui-form-item">
                <div class="layui-input-block" style="margin-left: 0 !important; min-height: auto;">
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="start_time"
                               placeholder="注册时间（开始）"
                               value="<?php echo $prod_info['length'] ?? ''; ?>"/>
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="end_time"
                               placeholder="注册时间（结束）"
                               value="<?php echo $prod_info['width'] ?? ''; ?>"/>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <select name="customer_from">
                            <option value="">请选择用户来源</option>
                            <option value="PC">电脑端</option>
                            <option value="M">移动端</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <select name="customer_type">
                            <option value="">请选择用户类型</option>
                            <option value="normal">正常用户</option>
                            <option value="testing">测试用户</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input class="layui-btn layui-btn-normal" type="submit" lay-submit lay-filter="search"
                               value="搜索"/>
                    </div>
                </div>
            </div>
        </form>
        <form class="layui-form hd-padding-top10" autocomplete="off">
            <div class="layui-form-item">
                <div class="layui-input-inline" style="margin-right: 0;">
                    <select name="search_key">
                        <option value="username">用户名</option>
                        <option value="email">用户邮箱</option>
                    </select>
                </div>
                <div class="layui-input-inline hd-width-500">
                    <input type="text" class="layui-input" name="search_value" placeholder="请输入左侧对应选项的值查询用户" />
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input class="layui-btn layui-btn-normal" type="submit" lay-submit lay-filter="search2"
                               value="查询"/>
                    </div>
                </div>
            </div>
        </form>
        <table id="customer_list" lay-filter="opt"></table>
    </div>
    <script>
        layui.use(['table', 'jquery', 'form', 'laydate'], function () {
            layui.laydate.render({
                elem: 'input[name=start_time]'
            });
            layui.laydate.render({
                elem: 'input[name=end_time]'
            });

            var tableIns = layui.table.render({
                elem: '#customer_list',
                url: window.location.href,
                cols: [
                    [
                        {field: 'customer_id', hide: true},
                        {field: 'email', title: '用户邮箱', align: 'center'},
                        {
                            title: '用户名', align: 'center',
                            templet: function (d) {
                                return $.trim(d.first_name + ' ' + d.last_name);
                            }
                        },
                        {
                            title: '用户类型', align: 'center',
                            templet: function (d) {
                                if (d.customer_type == 'normal') {
                                    return '正常用户';
                                }
                                return '测试用户';
                            }
                        },
                        {
                            title: '用户来源', align: 'center',
                            templet: function (d) {
                                if (d.device_from == 'M') {
                                    return '移动端';
                                }
                                return '电脑端'
                            }
                        },
                        {field: 'registered_text', title: '注册时间', align: 'center'},
                        {field: 'last_operation', title: '最后操作', align: 'center'},
                        {fixed: 'right', width: '100', align: 'center', toolbar: '#operate'}
                    ]
                ],
                height: 'full-220',
                page: true,
                limit: 20
            });

            layui.table.on('tool(opt)', function (obj) {
                switch (obj.event) {
                    case 'edit':
                        var layer_idx = layer.open({
                            type: 2,
                            title: '编辑',
                            skin: 'hd-open-edit',
                            content: '/spadmin/customer/' + obj.data.customer_id
                        });
                        layer.full(layer_idx);
                        break;
                }
            });

            layui.form.on('submit(search)', function (formObj) {
                tableIns.reload({
                    where: formObj.field,
                    page: {curr: 1}
                });
                return false;
            });

            layui.form.on('submit(search2)', function (formObj) {
                tableIns.reload({
                    where: formObj.field,
                    page: {curr: 1}
                });
                return false;
            });
        });
    </script>
    <script type="text/html" id="operate">
        <i class="layui-icon layui-icon-edit" lay-event="edit"></i>
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
