<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <script>
        var lang_codes = <?php echo json_encode($lang_codes);?>;
        var cate_tree_list = <?php echo json_encode($cate_tree_list);?>;

        layui.use(['element'], function () {
            let element = layui.element;

            layui.use(['tree'], function () {
                for (let tree_idx in cate_tree_list) {
                    let lay_id = 'cate_list_' + tree_idx;

                    element.tabAdd('cate_list', {
                        id: lay_id,
                        title: lang_codes[tree_idx].toLocaleUpperCase(),
                        content: '<div id="' + lay_id + '"></div>'
                    });

                    layui.tree.render({
                        elem: '#' + lay_id,
                        data: cate_tree_list[tree_idx],
                        accordion: true,
                        onlyIconControl: true,
                        click: function (obj) {
                            open_ask_cfg.btn = ['编辑类目', '新增子类', '不小心点错了'];

                            layer.confirm('你想如何操作“' + obj.data.title + '”？', open_ask_cfg, function (idx) {
                                layer.close(idx);

                                if (obj.data.id < 0) {
                                    layer.alert('当前类实际不存在，不允许编辑', open_alert_cfg);
                                    return;
                                }

                                idx = layer.open({
                                    type: 2,
                                    title: '编辑',
                                    skin: 'hd-open-edit',
                                    content: '/spadmin/category/' + obj.data.id
                                });
                                layer.full(idx);
                            }, function (idx) {
                                layer.close(idx);

                                idx = layer.open({
                                    type: 2,
                                    title: '新增子类',
                                    skin: 'hd-open-edit',
                                    content: '/spadmin/category/0?parent_id=' + obj.data.id
                                });
                                layer.full(idx);
                            });
                        }
                    });
                }
                element.tabChange('cate_list', 'cate_list_0');
            });
        });
    </script>
    <div class="layui-fluid hd-padding-top30">
        <div class="layui-tab layui-tab-brief" lay-filter="cate_list">
            <ul class="layui-tab-title"></ul>
            <div class="layui-tab-content"></div>
        </div>
    </div>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
