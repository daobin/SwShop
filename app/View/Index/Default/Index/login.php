<?php
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Register</li>
        </ol>
    </div>
    <div class="container">
        <div class="row hd-margin-bottom-30">
            <div class="col-md-6">
                <form class="hd-form" action="/register.html" method="post" autocomplete="off">
                    <div class="form-group">
                        <h3>Create Account</h3>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" maxlength="100" name="email"
                               placeholder="Email Address"/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control hd-password" maxlength="50" name="password"
                               placeholder="Password"/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control hd-password" maxlength="50" name="password2"
                               placeholder="Confirm Password"/>
                    </div>
                    <div class="form-group text-right">
                        <input type="hidden" name="hash_tk" value="<?php echo $register_tk; ?>"/>
                        <input type="submit" class="btn btn-warning" value="Create Account"/>
                    </div>
                </form>
            </div>
            <div class="col-md-5 col-md-offset-1">
                <form class="hd-form" method="post" autocomplete="off">
                    <div class="form-group">
                        <h3>Sign In</h3>
                    </div>
                    <?php if ($password_reset_success) { ?>
                        <div class="form-group text-success">
                            <i class="glyphicon glyphicon-ok"></i>
                            &nbsp;
                            <?php echo \App\Helper\LanguageHelper::get('password_reset_success', $lang_code); ?>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <input type="text" class="form-control" name="email" placeholder="Email Address"/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control hd-password" name="password" placeholder="Password"/>
                    </div>
                    <div class="form-group">
                        <a class="btn-link" href="/forgot-password.html">Forgot your password ?</a>
                    </div>
                    <div class="form-group text-right">
                        <input type="hidden" name="hash_tk" value="<?php echo $login_tk; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Sign In"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
