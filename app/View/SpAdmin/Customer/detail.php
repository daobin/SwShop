<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'header', ['show_top_line' => false, 'timestamp' => $timestamp ?? '']);
?>
    <div class="layui-fluid hd-padding-bottom30">
        <div class="layui-card hd-margin-top-30">
            <div class="layui-card-header layui-font-16">用户信息</div>
            <div class="layui-card-body">
                <div class="layui-row hd-margin-top-10">
                    <div class="layui-col-xs3">
                        用户国家：<?php echo $customer_info['ip_country_iso_code2'] ?? '--'; ?>
                    </div>
                    <div class="layui-col-xs3">
                        用户来源：<?php echo $customer_info['device_from'] == 'M' ? '移动端' : '电脑端'; ?>
                    </div>
                    <div class="layui-col-xs3">
                        注册时间：<?php echo $customer_info['registered_text'] ?? '--'; ?>
                    </div>
                    <div class="layui-col-xs3">
                        最后登录：
                        <?php echo empty($customer_info['logined_at']) ? '--' : date('Y-m-d H:i:s', $customer_info['logined_at']); ?>
                    </div>
                </div>
                <div class="layui-row hd-margin-top-10">
                    <div class="layui-col-xs3">
                        订单总数：0
                    </div>
                    <div class="layui-col-xs3">
                        订单总额：<?php echo format_price_total(0, $currency); ?>
                    </div>
                    <div class="layui-col-xs3">
                        付款订单数：0
                    </div>
                    <div class="layui-col-xs3">
                        付款订单额：<?php echo format_price_total(0, $currency); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card hd-margin-top-30">
            <div class="layui-card-header layui-font-16">用户地址</div>
            <div class="layui-card-body">
                <?php
                foreach($address_list as $address){
                    if($address['address_type'] != 'shipping'){
                        continue;
                    }
                    echo '<div class="layui-row hd-margin-top-10"><i class="layui-icon layui-icon-ok"></i>&nbsp;&nbsp;';
                    echo xss_text($address['first_name'] . ' ' . $address['last_name']);
                    echo ', ', xss_text($address['street_address']);
                    if (!empty($address['street_address_sub'])) {
                        echo ', ', xss_text($address['street_address_sub']);
                    }
                    echo ', ', xss_text($address['city']);
                    echo ', ', xss_text($address['zone_name'] . ' ' . $address['postcode']);
                    echo ', ', xss_text($address['country_name']);
                    echo ', ', xss_text($address['telephone']);
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        <div class="layui-card hd-margin-top-30">
            <div class="layui-card-header layui-font-16">信息变更</div>
            <div class="layui-card-body">
                <form class="layui-form hd-margin-top-10" method="post" autocomplete="off">
                    <div class="layui-form-item">
                        <label class="layui-form-label">First Name</label>
                        <div class="layui-input-inline hd-width-500">
                            <input type="text" class="layui-input" name="first_name" maxlength="60"
                                   value="<?php echo $customer_info['first_name']; ?>"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">Last Name</label>
                        <div class="layui-input-inline hd-width-500">
                            <input type="text" class="layui-input" name="last_name" maxlength="60"
                                   value="<?php echo $customer_info['last_name']; ?>"/>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">邮箱</label>
                        <div class="layui-input-inline hd-width-500">
                            <input type="text" class="layui-input" name="email" maxlength="100"
                                   value="<?php echo $customer_info['email']; ?>"/>
                        </div>
                        <div class="layui-form-mid layui-font-red">* 变更邮箱将同步订单中的邮箱地址</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">密码</label>
                        <div class="layui-input-inline hd-width-500">
                            <input type="text" class="layui-input hd-password"
                                   placeholder="为空表示无需重置密码"
                                   name="pwd" maxlength="50"/>
                        </div>
                        <div class="layui-form-mid layui-font-red">* 重置密码会影响用户登录，请谨慎操作</div>
                    </div>
                    <div class="layui-form-item hd-margin-top-30">
                        <div class="layui-input-block">
                            <input type="hidden" name="hash_tk" value="<?php echo $csrf_token ?? ''; ?>"/>
                            <input class="layui-btn" type="submit" lay-submit lay-filter="customer_edit"
                                   value="<?php echo xss_text('save', true); ?>"/>
                            <input class="layui-btn layui-btn-primary hd-layer-close" type="button"
                                   value="<?php echo xss_text('cancel', true); ?>"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        layui.use(['form'], function () {
            layui.form.on('submit(customer_edit)', function (formObj) {
                form_submit(window.location.href, formObj.field);

                return false;
            });
        });
    </script>
<?php
\App\Helper\TemplateHelper::widget('sp_admin', 'footer');