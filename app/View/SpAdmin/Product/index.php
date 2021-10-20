<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['add_url' => '/spadmin/product/0', 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-top60">
        <form class="layui-form hd-padding-top10" autocomplete="off">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <div id="cate_select"></div>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <select name="prod_status">
                            <option value="">请选择商品状态</option>
                            <?php
                            if (!empty($product_status_arr)) {
                                foreach ($product_status_arr as $value => $text) {
                                    echo '<option value="', (int)$value, '">', $text, '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input class="layui-btn layui-btn-normal" type="submit" lay-submit lay-filter="prod_search"
                               value="搜索"/>
                    </div>
                </div>
            </div>
        </form>
        <table id="prod_list" lay-filter="opt"></table>
    </div>
    <script src="/static/layui/xm-select.js"></script>
    <script>
        xmSelect.render({
            el: '#cate_select',
            filterable: true,
            name: 'category_id',
            height: 'auto',
            tips: '请选择商品分类',
            radio: true,
            clickClose: true,
            model: {
                icon: 'hidden'
            },
            prop: {
                name: 'category_name',
                value: 'product_category_id'
            },
            tree: {
                show: true,
                strict: false,
                expandedKeys: true,
                showLine: false
            },
            initValue: [<?php echo $prod_info['product_category_id'] ?? 0;?>],
            data: <?php echo json_encode($cate_tree_list);?>
        });
    </script>
    <script>
        layui.use(['table', 'form'], function () {
            var tableIns = layui.table.render({
                elem: '#prod_list',
                url: window.location.href,
                cols: [
                    [
                        {field: 'product_id', hide: true},
                        {field: 'product_name', title: '商品名称', align: 'center'},
                        {
                            title: '商品状态',
                            align: 'center',
                            templet: function (d) {
                                switch (d.product_status) {
                                    case 1:
                                        return '上架中';
                                    case 2:
                                        return '<div class="layui-bg-red">下架中</div>';
                                    default:
                                        return '<div class="layui-bg-orange">待处理</div>';
                                }
                            }
                        },
                        {field: 'cate_level', title: '所属类目', align: 'center'},
                        {field: 'price', title: '默认价格（<?php echo $currency_symbol;?>）', align: 'center'},
                        {field: 'last_operation', title: '最后操作', align: 'center'},
                        {fixed: 'right', width: '100', align: 'center', toolbar: '#operate'}
                    ]
                ],
                height: 'full-150',
                page: true,
                limit: 20
            });

            layui.table.on('tool(opt)', function (obj) {
                switch (obj.event) {
                    case 'edit':
                        var layer_idx = layer.open({
                            type: 2,
                            title: '',
                            closeBtn: 0,
                            skin: 'hd-open-edit',
                            content: '/spadmin/product/' + obj.data.product_id
                        });
                        layer.full(layer_idx);
                        break;
                }
            });

            layui.form.on('submit(prod_search)', function (formObj) {
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
        <a href="/{{d.product_url}}-p{{d.product_id}}.html" target="_blank">
            <i class="layui-icon layui-icon-release" lay-event="look"></i>
        </a>
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
