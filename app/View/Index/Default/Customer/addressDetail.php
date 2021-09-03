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
                <small class="hd-font-size-18 hd-margin-left-15 hidden-xs hidden-sm">Edit and save your shipping address</small>
                <a class="btn btn-info pull-right" href="/address.html">
                    &lt;&lt; Back to address book
                </a>
            </h2>
        </div>
        <form class="hd-form form-horizontal hd-margin-top-30" method="post" autocomplete="off">
            <div class="form-group">
                <label class="control-label col-md-2">First Name <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="first_name" maxlength="60"
                           value="<?php echo $addr_info['first_name']??''; ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Last Name <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="last_name" maxlength="60"
                           value="<?php echo $addr_info['last_name']??''; ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Address <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="address" maxlength="180"
                           placeholder="Street address, Company name, etc."
                           value="<?php echo $addr_info['street_address']??''; ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Address Line 2</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="address2" maxlength="280"
                           placeholder="Apartment, suite, unit, building, floor, etc."
                           value="<?php echo $addr_info['street_address_sub']??''; ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">City <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="city" maxlength="60"
                           value="<?php echo $addr_info['city']??''; ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Country <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <select class="form-control" name="country_id">
                        <option value="0">Please select your country</option>
                        <?php
                        if (!empty($country_list)) {
                            foreach ($country_list as $country) {
                                $selected = $country['country_id'] == $addr_info['country_id'] ? ' selected ' : '';
                                echo '<option ', $selected, ' value="', $country['country_id'], '">', xss_text($country['country_name']), '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">State / Province <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="state" maxlength="60"
                           placeholder="Please select the country first" disabled
                           value="<?php echo $addr_info['zone_name']??''; ?>"/>
                    <select name="state_id" class="form-control hd-display-none"
                            data-id="<?php echo $addr_info['zone_id'] ?? 0; ?>"></select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Postcode <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="postcode" maxlength="16"
                           value="<?php echo $addr_info['postcode']??''; ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Phone Number <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="phone" maxlength="30"
                           value="<?php echo $addr_info['telephone']??''; ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-offset-2 col-md-8 hd-cursor-pointer hd-font-weight-normal">
                    <input class="hd-cursor-pointer" type="checkbox"
                           <?php echo $is_default ? ' checked ' : '';?>
                           name="set_default" value="1"/>
                    <span>set as Default address</span>
                </label>
            </div>
            <div class="form-group hd-margin-top-30">
                <div class="col-md-offset-2 col-md-10">
                    <input type="hidden" name="hash_tk" value="<?php echo $hash_tk ?? ''; ?>"/>
                    <input type="submit" class="btn btn-warning" value="Save"/>
                </div>
            </div>
        </form>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
