<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>

    <title>Sw Shop Admin</title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>

    <meta name="renderer" content="webkit|ie-comp|ie-stand"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no"/>

    <!--link rel="shortcut icon" href="/"/-->
    <link rel="stylesheet" href="/static/layui/css/layui.css"/>
    <style>
        body {
            width: 100%;
            overflow: hidden;
            background: url("/static/spadmin/login.bg.jpg") center no-repeat;
        }

        form {
            width: 268px;
            margin: 288px auto;
            background: #fff;
            padding: 10px 30px;
            border-radius: 8px;
        }

        .layui-form-item {
            position: relative;
        }

        .layui-form-item i.layui-icon {
            position: absolute;
            top: 10px;
            left: 5px;
        }

        .layui-input {
            border-radius: 6px;
            text-indent: 18px;
        }

        #logo {
            display: block;
            width: 86%;
            margin: 0 auto;
        }

        .margin-top30 {
            margin-top: 30px !important;
        }
    </style>

    <!--[if lt IE 9]>
    <script type="text/javascript" src="/static/html5shiv.min.js"></script>
    <script type="text/javascript" src="/static/respond.min.js"></script>
    <![endif]-->
    <script src="/static/layui/layui.js"></script>
    <script src="/static/spadmin/hd.admin.js<?php echo \App\Helper\ConfigHelper::get('web_info.timestamp'); ?>"></script>
    <script>
        if (self != top) {
            top.location.href = self.location.href;
        }
    </script>
</head>
<body>
<form class="layui-form" method="post" autocomplete="off">
    <div class="layui-form-item" style="display: none;">
        <!--img id="logo" src="" /-->
    </div>
    <div class="layui-form-item margin-top30">
        <div class="layui-form-inline">
            <i class="layui-icon layui-icon-username"></i>
            <input class="layui-input" type="text" maxlength="16"
                   placeholder="<?php echo xss_text('enter_account', true); ?>" name="account"
                   lay-verify="account" lay-verType="alert"/>
        </div>
    </div>
    <div class="layui-form-item">
        <i class="layui-icon layui-icon-password"></i>
        <input class="layui-input" type="password" maxlength="32"
               placeholder="<?php echo xss_text('enter_password', true); ?>" name="password"
               lay-verify="password" lay-verType="alert"/>
    </div>
    <div class="layui-form-item margin-top30">
        <input type="hidden" name="hash_tk" value="<?php echo $csrf_token; ?>"/>
        <input class="layui-btn" type="submit" lay-submit lay-filter="login"
               value="<?php echo xss_text('login', true); ?>"/>
    </div>
</form>
<script>
    layui.use(['form'], function(){
        layui.form.verify({
            account: function (val) {
                val = $.trim(val);
                if (val == '') {
                    return '<?php echo xss_text('enter_account', true); ?>';
                }
                if (val.length > 16) {
                    return '<?php echo xss_text('enter_valid_account_password', true); ?>';
                }
            },
            password: function (val) {
                val = $.trim(val);
                if (val == '') {
                    return '<?php echo xss_text('enter_password', true); ?>';
                }
                if (val.length > 32) {
                    return '<?php echo xss_text('enter_valid_account_password', true); ?>';
                }
            }
        });

        layui.form.on('submit(login)', function (formObj) {
            form_submit(window.location.href, formObj.field);
            return false;
        });
    });
</script>
</body>
</html>