<?php
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Address Book</li>
        </ol>
    </div>
    <div class="container">
        <ul class="nav nav-pills nav-justified">
            <li><a href="/account.html">My Profile</a></li>
            <li><a href="/password.html">Change Password</a></li>
            <li class="active"><a>Address Book</a></li>
            <li><a href="/order.html">Order History</a></li>
        </ul>
        <div class="page-header">
            <h2 class="hd-color-333">
                Address Book
                <small class="hd-font-size-18 hd-margin-left-15 hidden-xs hidden-sm">
                    <?php echo \App\Helper\LanguageHelper::get('max_10_shopping_address', $lange_code); ?>
                </small>
                <?php if (count($address_list) < 10) { ?>
                    <a class="btn btn-info pull-right" href="/address/add.html">
                        &plus; Add
                    </a>
                <?php } ?>
            </h2>
        </div>
        <div class="hd-margin-top-30" id="hd-address-list">
            <?php
            if (!empty($address_list)) {
                foreach ($address_list as $address) {
                    ?>
                    <div class="hd-address">
                        <div>
                            <?php
                            echo xss_text($address['first_name'] . ' ' . $address['last_name']);
                            echo ', ', xss_text($address['street_address']);
                            if (!empty($address['street_address_sub'])) {
                                echo ', ', xss_text($address['street_address_sub']);
                            }
                            echo ', ', xss_text($address['city']);
                            echo ', ', xss_text($address['zone_name'] . ' ' . $address['postcode']);
                            echo ', ', xss_text($address['country_name']);
                            echo ', ', xss_text($address['telephone']);
                            ?>
                        </div>
                        <div class="hd-address-opt">
                            <label class="hd-set-default hd-margin-right-15">
                                <input class="hd-cursor-pointer" type="radio" name="def_addr"
                                    <?php if ($default_address_id == $address['customer_address_id']) {
                                        echo ' checked ';
                                    } ?>
                                       value="<?php echo $address['customer_address_id']; ?>"/>
                                <span>set as Default address</span>
                            </label>
                            <a class="btn btn-sm" href="/address/<?php echo $address['customer_address_id']; ?>.html">
                                <i class="glyphicon glyphicon-edit"></i>
                            </a>
                            <a class="btn btn-sm" data-toggle="popover"
                               data-addr="<?php echo $address['customer_address_id']; ?>">
                                <i class="glyphicon glyphicon-trash"></i>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <input type="hidden" id="hd-addr-tk" value="<?php echo $hash_tk ?? ''; ?>"/>
    <input type="hidden" id="hd-addr-def" value="<?php echo $default_address_id ?? ''; ?>"/>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
