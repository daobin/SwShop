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
        <div class="row hd-margin-bottom30">
            <div class="col-md-6">
                <form id="hd-form-register" action="/register.html" method="post" autocomplete="off">
                    <div class="form-group">
                        <h3>Create Account</h3>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="email" placeholder="Email Address"/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control hd-password" name="password" placeholder="Password"/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control hd-password" name="password2"
                               placeholder="Confirm Password"/>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="hash_tk" value="<?php echo $register_tk; ?>"/>
                        <input type="submit" class="btn btn-warning" value="Create Account"/>
                    </div>
                </form>
            </div>
            <div class="col-md-5 col-md-offset-1">
                <form id="hd-form-login" method="post" autocomplete="off">
                    <div class="form-group">
                        <h3>Sign In</h3>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="email" placeholder="Email Address"/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control hd-password" name="password" placeholder="Password"/>
                    </div>
                    <div class="form-group text-right">
                        <a href="/">Forgot your password ?</a>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="hash_tk" value="<?php echo $login_tk; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Sign In"/>
                    </div>
                </form>
            </div>
        </div>
        <img src="https://www.glarrymusic.com/up/banner/20191104/960x380.jpg" style="width: 100%;"/>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
