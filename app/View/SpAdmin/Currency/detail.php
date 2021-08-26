<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid">
        <form class="layui-form hd-margin-top-30" method="post" autocomplete="off">
            <div class="layui-form-item">
                <label class="layui-form-label">币种</label>
                <div class="layui-input-block">
                    <select name="code" lay-filter="code">
                        <option left="" right="" value="">请选择币种</option>
                        <?php
                        if ($currency_list) {
                            foreach ($currency_list as $code => $currency) {
                                $symbol = ' left="' . $currency['symbol_left'] . '" right="' . $currency['symbol_right'] . '" ';;
                                if ($currency_code == $code) {
                                    echo '<option ', $symbol, ' value="', $code, '" selected>', $currency['currency_name'], '</option>';
                                } else {
                                    echo '<option ' . $symbol . ' value="', $code, '">', $currency['currency_name'], '</option>';
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">币种符号</label>
                <div class="layui-input-block">
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="symbol_left" readonly disabled
                               placeholder="左边符号"
                               value="<?php echo $currency_info['symbol_left'] ?? ''; ?>"/>
                    </div>
                    <div class="layui-form-mid">-</div>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" name="symbol_right" readonly disabled
                               placeholder="右边符号"
                               value="<?php echo $currency_info['symbol_right'] ?? ''; ?>"/>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">币种汇率</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input hd-float-only" name="value" maxlength="1"
                           value="<?php echo $currency_info['value'] ?? '.'; ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">币种精确度</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input hd-int-only" name="decimal_places" maxlength="1"
                           value="<?php echo $currency_info['decimal_places'] ?? ''; ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">小数点符号</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="decimal_point" maxlength="1"
                           value="<?php echo $currency_info['decimal_point'] ?? '.'; ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">千分位符号</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="thousands_point" maxlength="1"
                           value="<?php echo $currency_info['thousands_point'] ?? ','; ?>"/>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input hd-int-only" name="sort" maxlength="5"
                           value="<?php echo $currency_info['sort'] ?? ''; ?>"/>
                </div>
                <div class="layui-form-mid layui-word-aux">排序由小到大，不填则默认为0</div>
            </div>
            <div class="layui-form-item hd-margin-top-30">
                <div class="layui-input-block">
                    <input type="hidden" name="hash_tk" value="<?php echo $csrf_token; ?>"/>
                    <input class="layui-btn" type="submit" lay-submit lay-filter="currency_edit"
                           value="<?php echo xss_text('save', true); ?>"/>
                    <input class="layui-btn layui-btn-primary hd-layer-close" type="button"
                           value="<?php echo xss_text('cancel', true); ?>"/>
                </div>
            </div>
        </form>
    </div>

    <script>
        layui.use(['form'], function () {
            layui.form.on('select(code)', function (data) {
                let opt = $(data.elem).find('option:selected');
                $('input[name="symbol_left"]').val(opt.attr('left'));
                $('input[name="symbol_right"]').val(opt.attr('right'));
            });

            layui.form.on('submit(currency_edit)', function (formObj) {
                form_submit(window.location.href, formObj.field);

                return false;
            });
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
