<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <script src="/static/layui/xm-select.js"></script>
    <script>
        var lang_codes = JSON.parse('<?php echo json_encode($lang_codes);?>');
        var cate_desc_list = JSON.parse('<?php echo json_encode($cate_desc_list);?>');
        layui.use(['form', 'element'], function () {
            let element = layui.element;
            for (let lang_idx in lang_codes) {
                let lang_code = lang_codes[lang_idx];
                let cate_name = '';
                let cate_desc = '';
                let cate_desc_m = '';
                let meta_title = '';
                let meta_keywords = '';
                let meta_desc = '';
                if (cate_desc_list[lang_code] != undefined) {
                    cate_name = cate_desc_list[lang_code].category_name;
                    cate_desc = cate_desc_list[lang_code].category_description;
                    cate_desc_m = cate_desc_list[lang_code].category_description_m;
                    meta_title = cate_desc_list[lang_code].meta_title;
                    meta_keywords = cate_desc_list[lang_code].meta_keywords;
                    meta_desc = cate_desc_list[lang_code].meta_description;
                }

                let lay_id = 'cate_desc_' + lang_idx;
                let html = '';
                if (lang_idx == 0) {
                    html += '<div class="layui-form-item">';
                    html += '<label class="layui-form-label">类目名称 <span class="hd-color-red">*</span></label>';
                    html += '<div class="layui-input-block">';
                    html += '<input type="text" class="layui-input" name="cate_name[' + lang_code + ']" maxlength="300" value="' + cate_name + '" />';
                    html += '</div></div>';
                } else {
                    html += '<div class="layui-form-item">';
                    html += '<label class="layui-form-label">类目名称</label>';
                    html += '<div class="layui-input-block">';
                    html += '<input type="text" class="layui-input" name="cate_name[' + lang_code + ']" maxlength="300"' +
                        ' placeholder="不填则默认为[' + lang_codes[0].toLocaleUpperCase() + ']的类目名称" value="' + cate_name + '" />';
                    html += '</div></div>';
                }
                html += '<div class="layui-form-item">';
                html += '<label class="layui-form-label">类目描述（电脑端）</label>';
                html += '<div class="layui-input-block">';
                html += '<textarea class="layui-textarea" name="cate_desc[' + lang_code + ']">' + cate_desc + '</textarea>';
                html += '</div></div>';
                html += '<div class="layui-form-item">';
                html += '<label class="layui-form-label">类目描述（移动端）</label>';
                html += '<div class="layui-input-block">';
                html += '<textarea class="layui-textarea" name="cate_desc_m[' + lang_code + ']"' +
                    ' placeholder="不填则默认为类目描述（电脑端）">' + cate_desc_m + '</textarea>';
                html += '</div></div>';
                html += '<div class="layui-form-item">';
                html += '<label class="layui-form-label">Meta 标题</label>';
                html += '<div class="layui-input-block">';
                html += '<input type="text" class="layui-input" name="meta_title[' + lang_code + ']" maxlength="300"' +
                    ' placeholder="用于 SEO 优化，不填则默认为类目名称" value="' + meta_title + '" />';
                html += '</div></div>';
                html += '<div class="layui-form-item">';
                html += '<label class="layui-form-label">Meta 关键词</label>';
                html += '<div class="layui-input-block">';
                html += '<textarea class="layui-textarea" name="meta_keywords[' + lang_code + ']" maxlength="500"' +
                    ' placeholder="用于 SEO 优化">' + meta_keywords + '</textarea>';
                html += '</div></div>';
                html += '<div class="layui-form-item">';
                html += '<label class="layui-form-label">Meta 描述</label>';
                html += '<div class="layui-input-block">';
                html += '<textarea class="layui-textarea" name="meta_desc[' + lang_code + ']" maxlength="1000"' +
                    ' placeholder="用于 SEO 优化">' + meta_desc + '</textarea>';
                html += '</div></div>';
                element.tabAdd('cate_desc_list', {
                    id: lay_id,
                    title: lang_code.toLocaleUpperCase(),
                    content: html
                });
            }
            element.tabChange('cate_desc_list', 'cate_desc_0');

            xmSelect.render({
                el: '#parent_select',
                filterable: true,
                name: 'parent_id',
                height: 'auto',
                tips: '请选择父级类目，未选择时默认为顶级类目',
                radio: true,
                clickClose: true,
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
                initValue: [<?php echo (int)$parent_id;?>],
                data: JSON.parse('<?php echo json_encode($cate_tree_list);?>'),
                on: function(data){
                    console.log(data);
                }
            });

            layui.form.on('submit(cate_edit)', function (formObj) {
                form_submit(window.location.href, formObj.field);

                return false;
            });
        });
    </script>
    <div class="layui-fluid">
        <form class="layui-form hd-padding-top30 hd-padding-bottom30" method="post" autocomplete="off">
            <div class="layui-form-item">
                <label class="layui-form-label">父级类目</label>
                <div class="layui-input-block">
                    <div id="parent_select"></div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">类目状态</label>
                <div class="layui-input-block">
                    <?php
                    if(empty($cate_info['category_status'])){
                        echo '<input type="checkbox" name="cate_status" lay-skin="switch" lay-text="开启|关闭"/>';
                    }else{
                        echo '<input type="checkbox" name="cate_status" lay-skin="switch" lay-text="开启|关闭" checked/>';
                    }
                    ?>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">类目排序</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input hd-int-only" name="cate_sort" maxlength="5"
                           value="<?php echo $cate_info['sort'] ?? ''; ?>"/>
                </div>
                <div class="layui-form-mid layui-word-aux">排序由小到大，不填则默认为0</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">类目 URL <span class="hd-color-red">*</span></label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="cate_url" maxlength="100"
                           placeholder="不含请求协议和域名部分"
                           value="<?php echo $cate_info['category_url'] ?? '';?>"/>
                </div>
                <div class="layui-form-mid layui-word-aux">用于 SEO 优化，跳转链接为空时有效</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">跳转链接</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="redirect_link" maxlength="500"
                           placeholder="包含请求协议和域名内在的完整链接"
                           value="<?php echo $cate_info['redirect_link'] ?? '';?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">产品个数</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input hd-int-only" name="prod_size" maxlength="5"
                           value="<?php echo $cate_info['product_show_size'] ?? '';?>"/>
                </div>
                <div class="layui-form-mid layui-word-aux">每页列表展示产品个数，不填则默认为0</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">评论个数</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input hd-int-only" name="review_size" maxlength="5"
                           value="<?php echo $cate_info['review_show_size'] ?? '';?>"/>
                </div>
                <div class="layui-form-mid layui-word-aux">每页列表展示评论个数，不填则默认为0</div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <div class="layui-tab layui-tab-brief" lay-filter="cate_desc_list">
                        <ul class="layui-tab-title"></ul>
                        <div class="layui-tab-content"></div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item" id="hd-bottom-options">
                <input type="hidden" name="hash_tk" value="<?php echo $csrf_token; ?>"/>
                <input class="layui-btn" type="submit" lay-submit lay-filter="cate_edit"
                       value="<?php echo xss_text('save', true); ?>"/>
                <input class="layui-btn layui-btn-primary hd-layer-close" type="button"
                       value="<?php echo xss_text('cancel', true); ?>"/>
            </div>
        </form>
    </div>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
