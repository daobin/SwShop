<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-bottom30">
        <div class="layui-card hd-margin-top-30">
            <div class="layui-card-header layui-font-16">订单 #<?php echo $order_info['order_number']; ?></div>
            <div class="layui-card-body">
                <div class="layui-row hd-margin-top-10">
                    <div class="layui-col-xs3">
                        用户名称：<?php echo $order_info['customer_name']; ?>
                    </div>
                    <div class="layui-col-xs3">
                        用户邮箱：<?php echo $order_info['customer_email']; ?>
                    </div>
                    <div class="layui-col-xs3">
                        生成时间：<?php echo $order_info['created_text']; ?>
                    </div>
                    <div class="layui-col-xs3">
                        订单类型：<?php echo $order_info['order_type'] == 'normal' ? '正常单' : '测试单'; ?>
                    </div>
                </div>
                <div class="layui-row hd-margin-top-10">
                    <div class="layui-col-xs3">
                        下单站点：<?php echo $order_info['host_from']; ?>
                    </div>
                    <div class="layui-col-xs3">
                        下单设备：<?php echo $order_info['device_from'] == 'M' ? '移动端' : '电脑端'; ?>
                    </div>
                    <div class="layui-col-xs3">
                        下单IP：<?php echo long2ip($order_info['ip_number']); ?>
                    </div>
                    <div class="layui-col-xs3">
                        IP国家：<?php echo $order_info['ip_country_iso_code_2']; ?>
                    </div>
                </div>
                <div class="layui-row hd-margin-top-10">
                    <div class="layui-col-xs3">
                        货运方式：<?php echo $order_info['shipping_method']; ?>
                    </div>
                    <div class="layui-col-xs3">
                        支付方式：<?php echo $order_info['payment_method']; ?>
                    </div>
                    <div class="layui-col-xs3">
                        下单状态：<?php echo $order_statuses[$order_info['order_status_id']] ?? ''; ?>
                    </div>
                    <div class="layui-col-xs3">
                        交易流水：<?php echo $paypal_info['txn_id'] ?? ''; ?>
                    </div>
                </div>
                <div class="layui-row hd-margin-top-10">
                    <div class="layui-col-xs12">
                        货运地址：
                        <?php
                        echo xss_text($order_address['first_name'] . ' ' . $order_address['last_name']);
                        echo ', ', xss_text($order_address['street_address']);
                        if (!empty($order_address['street_address_sub'])) {
                            echo ', ', xss_text($order_address['street_address_sub']);
                        }
                        echo ', ', xss_text($order_address['city']);
                        echo ', ', xss_text($order_address['zone_name'] . ' ' . $order_address['postcode']);
                        echo ', ', xss_text($order_address['country_name']);
                        echo ', ', xss_text($order_address['telephone']);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card hd-margin-top-30">
            <div class="layui-card-header layui-font-16">订单商品</div>
            <div class="layui-card-body">
                <table class="layui-table hd-margin-top-10">
                    <thead>
                    <tr>
                        <th>商品</th>
                        <th>SKU</th>
                        <th>单价</th>
                        <th>数量</th>
                        <th>总价</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($order_info['prod_list'] as $sku => $prod_info) {
                        $prod_id = $prod_info['product_id'];
                        $prod_name = xss_text($prod_info['product_name']);
                        $prod_img = '';
                        if (!empty($prod_img_list[$prod_id])) {
                            $prod_img = $oss_access_host . $prod_img_list[$prod_id]['image_path'] . '/' . $prod_img_list[$prod_id]['image_name'] . '?' . $prod_img_list[$prod_id]['updated_at'];
                        }
                        $sku_attrs = [];
                        if (!empty($sku_attr_list[$sku])) {
                            foreach ($sku_attr_list[$sku] as $attr_value => $attr_info) {
                                $sku_attrs[] = xss_text($attr_value);
                                if (!empty($attr_info['image_path']) && !empty($attr_info['image_name'])) {
                                    $prod_img = $oss_access_host . $attr_info['image_path'] . '/' . $attr_info['image_name'] . '?' . $attr_info['updated_at'];
                                }
                            }
                        }
                        $prod_img = str_replace('_d_d', '_100_100', $prod_img);
                        ?>
                        <tr>
                            <td>
                                <img class="layui-border-orange" src="<?php echo $prod_img; ?>" style="width: 60px;"/>
                                &nbsp;&nbsp;
                                <?php
                                if (!empty($sku_attrs)) {
                                    echo ' <span class="hd-color-888">[ ', implode(", ", $sku_attrs), ' ]</span>';
                                    echo '<span class="hd-padding-left15">', $prod_name, '</span>';
                                } else {
                                    echo '<span>', $prod_name, '</span>';
                                }
                                ?>
                            </td>
                            <td><?php echo $sku; ?></td>
                            <td><?php echo format_price_total($prod_info['price'], $order_currency); ?></td>
                            <td><?php echo $prod_info['qty']; ?></td>
                            <td>
                                <?php echo format_price_total($prod_info['price'] * $prod_info['qty'], $order_currency); ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="hd-margin-top-30"></div>
                <?php
                foreach ($total_list as $class => $total) {
                    echo '<div class="layui-row hd-margin-top-10 hd-align-right">';
                    echo '<b>', $total['ot_title'], ':</b>&nbsp;&nbsp;';
                    echo '<span>', $total['ot_text'], '</span></div>';
                }
                ?>
            </div>
        </div>
        <div class="layui-card hd-margin-top-30" id="hd-status-history">
            <div class="layui-card-header layui-font-16">订单状态</div>
            <div class="layui-card-body">
                <table class="layui-table hd-margin-top-10">
                    <thead>
                    <tr>
                        <th>更新时间</th>
                        <th class="hd-align-center">用户可见</th>
                        <th>状态</th>
                        <th>备注</th>
                        <th>操作人</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($history_list as $status_info) {
                        echo '<tr>';
                        echo '<td>', $status_info['created_text'], '</td>';
                        if ($status_info['is_show'] == 0) {
                            echo '<td class="hd-align-center"><i class="layui-icon layui-icon-close"></i></td>';
                        } else {
                            echo '<td class="hd-align-center"><i class="layui-icon layui-icon-ok"></i></td>';
                        }
                        echo '<td>', $order_statuses[$status_info['order_status_id']] ?? '', '</td>';
                        echo '<td>', $status_info['comment'], '</td>';
                        echo '<td>', $status_info['created_by'], '</td>';
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
                <hr class="hd-margin-top-30"/>
                <form method="post" autocomplete="off" class="layui-form hd-margin-top-30">
                    <div class="layui-form-item">
                        <div class="layui-col-xs2 layui-col-xs-offset7">
                            <select name="status_id" lay-filter="status_select">
                                <?php
                                foreach ($order_statuses as $status_id => $status_text) {
                                    echo '<option value="', $status_id, '">', $status_text, '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="layui-col-xs2 layui-col-xs-offset1">
                            <select name="is_show">
                                <option value="0">用户不可见</option>
                                <option value="1">用户可见</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-col-xs5 layui-col-xs-offset7">
                            <textarea name="comment" class="layui-textarea"
                                      placeholder="<?php echo reset($status_notes); ?>"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item hd-align-right">
                        <input type="hidden" name="hash_tk" value="<?php echo $csrf_token; ?>"/>
                        <input class="layui-btn" type="submit" lay-submit lay-filter="status_edit" value="更新状态"/>
                    </div>
                </form>
            </div>
        </div>
        <div class="layui-card hd-margin-top-30">
            <div class="layui-card-header layui-font-16">用户订单（总数：<?php echo $order_list_count; ?>）</div>
            <div class="layui-card-body" style="max-height: 600px; overflow-y: scroll;">
                <table class="layui-table hd-margin-top-10">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>订单号</th>
                        <th>支付方式</th>
                        <th>订单金额</th>
                        <th>订单状态</th>
                        <th>订单类型</th>
                        <th>生成时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($order_list as $seq => $order_other) {
                        echo '<tr>';
                        echo '<td>', $seq + 1, '</td>';
                        if ($order_info['order_id'] == $order_other['order_id']) {
                            echo '<td>', $order_other['order_number'], '</td>';
                        } else {
                            echo '<td><a class="layui-font-blue" href="/spadmin/order/', $order_other['order_number'], '">', $order_other['order_number'], '</a></td>';
                        }
                        echo '<td>', $order_other['payment_method'], '</td>';
                        echo '<td>(', $order_other['currency_code'], ') ', $order_other['order_total'], '</td>';
                        echo '<td>', $order_statuses[$order_other['order_status_id']] ?? '', '</td>';
                        echo '<td>', $order_other['order_type'] == 'normal' ? '正常单' : '测试单', '</td>';
                        echo '<td>', $order_other['created_text'], '</td>';
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        var to_fixed = '<?php echo $to_fixed;?>';
        var status_notes = <?php echo json_encode($status_notes);?>;
        layui.use(['form', 'jquery'], function () {
            if (to_fixed != '') {
                $('html').animate({
                    scrollTop: ($('#' + to_fixed).offset().top - 10) + 'px'
                }, 600);
            }

            layui.form.on('select(status_select)', function (d) {
                $('textarea[name="comment"]').attr('placeholder', status_notes[d.value]);
            });

            layui.form.on('submit(status_edit)', function (formObj) {
                form_submit(window.location.href, formObj.field);

                return false;
            });
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');
