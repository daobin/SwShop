<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
<script>
    var lang_codes = <?php echo json_encode($lang_codes);?>;
    var value_desc_list = <?php echo json_encode($value_desc_list);?>;

    layui.use(['form', 'element'], function () {
        let element = layui.element;
        for (let lang_idx in lang_codes) {
            let lang_code = lang_codes[lang_idx];
            let value_name = '';
            if (value_desc_list[lang_code] != undefined) {
                value_name = value_desc_list[lang_code];
            }

            let lay_id = 'value_desc_' + lang_idx;
            let html = '';
            if (lang_idx == 0) {
                html += '<div class="layui-form-item">';
                html += '<label class="layui-form-label">属性值 <span class="hd-color-red">*</span></label>';
                html += '<div class="layui-input-inline">';
                html += '<input type="text" class="layui-input" name="value_names[' + lang_code + ']" maxlength="30" value="' + value_name + '" />';
                html += '</div></div>';
            } else {
                html += '<div class="layui-form-item">';
                html += '<label class="layui-form-label">属性值</label>';
                html += '<div class="layui-input-inline">';
                html += '<input type="text" class="layui-input" name="value_names[' + lang_code + ']" maxlength="30"' +
                    ' placeholder="不填则默认为[' + lang_codes[0].toLocaleUpperCase() + ']的属性值" value="' + value_name + '" />';
                html += '</div></div>';
            }
            element.tabAdd('value_desc_list', {
                id: lay_id,
                title: lang_code.toLocaleUpperCase(),
                content: html
            });
        }
        element.tabChange('value_desc_list', 'value_desc_0');

        layui.form.on('submit(attr_edit)', function (formObj) {
            form_submit(window.location.href, formObj.field);

            return false;
        });
    });
</script>
    <div class="layui-fluid hd-padding-bottom30">
        <form class="layui-form hd-margin-top-30" method="post" autocomplete="off">
            <div class="layui-form-item">
                <label class="layui-form-label">所在属性组</label>
                <div class="layui-input-block hd-padding-top10"><?php echo xss_text($group_name);?></div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <div class="layui-tab layui-tab-brief" lay-filter="value_desc_list">
                        <ul class="layui-tab-title"></ul>
                        <div class="layui-tab-content"></div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item hd-margin-top-30">
                <div class="layui-input-block">
                    <input type="hidden" name="hash_tk" value="<?php echo $csrf_token ?? ''; ?>"/>
                    <input class="layui-btn" type="submit" lay-submit lay-filter="attr_edit"
                           value="<?php echo xss_text('save', true); ?>"/>
                    <input class="layui-btn layui-btn-primary hd-layer-close" type="button"
                           value="<?php echo xss_text('cancel', true); ?>"/>
                </div>
            </div>
        </form>
    </div>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');