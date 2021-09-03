<?php
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Change Password</li>
        </ol>
    </div>
    <div class="container">
        <ul class="nav nav-pills nav-justified">
            <li><a href="/account.html">My Profile</a></li>
            <li class="active"><a>Change Password</a></li>
            <li><a href="/address.html">Address Book</a></li>
            <li><a href="/order.html">Order History</a></li>
        </ul>
        <div class="page-header">
            <h2 class="hd-color-333">Change Password</h2>
        </div>
        <form class="hd-form form-horizontal hd-margin-top-30" method="post" autocomplete="off">
            <div class="form-group">
                <label class="control-label col-md-2">Current Password <span class="text-danger">*</span></label>
                <div class="col-md-10">
                    <input type="text" class="form-control hd-password" name="curr_pwd"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">New Password <span class="text-danger">*</span></label>
                <div class="col-md-10">
                    <input type="text" class="form-control hd-password" name="new_pwd"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Confirm Password <span class="text-danger">*</span></label>
                <div class="col-md-10">
                    <input type="text" class="form-control hd-password" name="new_pwd2"/>
                </div>
            </div>
            <div class="form-group hd-margin-top-30">
                <div class="col-md-offset-2 col-md-10">
                    <input type="hidden" name="hash_tk" value="<?php echo $hash_tk ?? ''; ?>"/>
                    <input type="submit" class="btn btn-warning" value="Update"/>
                </div>
            </div>
        </form>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
