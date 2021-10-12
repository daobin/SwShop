<?php
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">My Profile</li>
        </ol>
    </div>
    <div class="container">
        <ul class="nav nav-pills nav-justified bg-info hd-border-radius-4">
            <li class="active"><a>My Profile</a></li>
            <li><a href="/password.html">Change Password</a></li>
            <li><a href="/address.html">Address Book</a></li>
            <li><a href="/order.html">Order History</a></li>
        </ul>
        <div class="page-header">
            <h2 class="hd-color-333">
                My Profile
                <a class="btn btn-link pull-right" href="/logout.html">
                    <i class="glyphicon glyphicon-log-out"></i> Logout
                </a>
            </h2>
        </div>
        <form class="hd-form form-horizontal hd-margin-top-30" method="post" autocomplete="off">
            <div class="form-group">
                <label class="control-label col-md-2">Email</label>
                <div class="col-md-10">
                    <p class="form-control-static"><?php echo $customer_info['email']??''; ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">First Name <span class="text-danger">*</span></label>
                <div class="col-md-10">
                    <input type="text" class="form-control" name="first_name" maxlength="60"
                           value="<?php echo $customer_info['first_name']??''; ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Last Name <span class="text-danger">*</span></label>
                <div class="col-md-10">
                    <input type="text" class="form-control" name="last_name" maxlength="60"
                           value="<?php echo $customer_info['last_name']??''; ?>"/>
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
