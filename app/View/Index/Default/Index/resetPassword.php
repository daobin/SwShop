<?php
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Reset Password</li>
        </ol>
    </div>
    <div class="container">
        <div class="page-header">
            <h2 class="hd-color-333">
                Reset Password
                <?php if (empty($email) && !empty($error)) { ?>
                    <a class="btn btn-info pull-right" href="/forgot-password.html">
                        &lt;&lt; Back
                    </a>
                <?php } ?>
            </h2>
        </div>
        <?php if (empty($email) && !empty($error)) { ?>
            <div class="hd-font-size-18 text-danger">
                <?php echo '<i class="glyphicon glyphicon-remove"></i>&nbsp;', $error; ?>
            </div>
        <?php } else { ?>
            <form class="form-horizontal" method="post" autocomplete="off">
                <?php if (!empty($error)) { ?>
                    <div class="form-group hd-margin-top-30">
                        <div class="col-md-6 col-md-offset-2 hd-font-size-18 text-danger">
                            <?php echo '<i class="glyphicon glyphicon-remove"></i>&nbsp;', $error; ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <label class="control-label col-md-2">Email Address</label>
                    <div class="col-md-6">
                        <p class="form-control-static"><?php echo $email ?? ''; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">New Password <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control hd-password" maxlength="50" name="password"
                               placeholder="Password"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">Confirm Password <span
                                class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control hd-password" maxlength="50" name="password2"
                               placeholder="Password"/>
                    </div>
                </div>
                <div class=" form-group hd-margin-top-30">
                    <div class="col-md-6 col-md-offset-2">
                        <input type="hidden" name="hash_tk" value="<?php echo $hash_tk ?? ''; ?>"/>
                        <input type="submit" class="btn btn-warning" value="Submit"/>
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
