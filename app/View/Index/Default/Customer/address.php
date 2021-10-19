<?php
$widget_params['tkd_title'] = 'My Address - ' . $website_name;
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">My Address</li>
        </ol>
    </div>
    <div class="container">
        <ul class="nav nav-pills nav-justified bg-info hd-border-radius-4">
            <li><a href="/account.html">My Profile</a></li>
            <li><a href="/password.html">Change Password</a></li>
            <li class="active"><a>My Address</a></li>
            <li><a href="/order.html">My Order</a></li>
            <li><a href="/logout.html">Logout</a></li>
        </ul>
        <div class="page-header">
            <h2 class="hd-color-333">
                My Address
                <small class="hd-font-size-18 hd-margin-left-15 hidden-xs hidden-sm">
                    <?php echo xss_text('max_10_shopping_address', $lang_code); ?>
                </small>
                <?php if (count($address_list) < 10) { ?>
                    <a class="btn btn-info pull-right" href="/address/add.html<?php echo $from ?? ''; ?>">
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
                            <a class="btn btn-sm"
                               href="/address/<?php echo $address['customer_address_id']; ?>.html<?php echo $from ?? ''; ?>">
                                <i class="glyphicon glyphicon-edit"></i>
                            </a>
                            <a class="btn btn-sm" data-toggle="popover"
                               data-addr="<?php echo $address['customer_address_id']; ?>">
                                <i class="glyphicon glyphicon-trash"></i>
                            </a>
                            <?php if (!empty($from)) { ?>
                                <a class="btn btn-sm btn-link"
                                   href="/shopping/confirmation.html?shipping_address=<?php echo $address['customer_address_id']; ?>">
                                    <i class="glyphicon glyphicon-ok"></i>
                                    Select
                                </a>
                            <?php } ?>
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
