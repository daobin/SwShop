<?php
$widget_params['tkd_title'] = 'Forgot Password  - ' . $website_name;
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Forgot Password</li>
        </ol>
    </div>
    <div class="container">
        <div class="page-header">
            <h2 class="hd-color-333">
                Forgot Password
                <?php if (!empty($email) && empty($error)) { ?>
                    <a class="btn btn-info pull-right" href="/forgot-password.html">
                        &lt;&lt; Back
                    </a>
                <?php } else { ?>
                    <small class="hd-font-size-18 hd-margin-left-15 hidden-xs hidden-sm">
                        <?php echo \App\Helper\LanguageHelper::get('enter_email_used_login', $lang_code); ?>
                    </small>
                <?php } ?>
            </h2>
        </div>
        <?php
        if (!empty($email) && empty($error)) {
            ?>
            <div class="hd-font-size-18 text-success">
                <i class="glyphicon glyphicon-ok"></i>
                &nbsp;
                <?php echo \App\Helper\LanguageHelper::get('forgot_password_email_submitted_tip', $lang_code); ?>
            </div>
            <?php
        } else {
            ?>
            <form class="form-horizontal" method="post" autocomplete="off">
                <div class="form-group hd-margin-top-30">
                    <label class="control-label col-md-2">Email Address <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="email" value="<?php echo $email ?? ''; ?>"/>
                    </div>
                </div>
                <div class=" form-group hd-margin-top-30">
                    <div class="col-md-6 col-md-offset-2">
                        <input type="hidden" name="hash_tk" value="<?php echo $hash_tk ?? ''; ?>"/>
                        <input type="submit" class="btn btn-warning" value="Submit"/>
                    </div>
                </div>
                <?php
                if (!empty($error)) {
                    echo '<div class="form-group hd-margin-top-30"><div class="col-md-6 col-md-offset-2 text-danger hd-font-size-16">';
                    echo '<i class="glyphicon glyphicon-remove"></i>&nbsp;', $error, '</div></div>';
                }
                ?>
            </form>
        <?php } ?>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
