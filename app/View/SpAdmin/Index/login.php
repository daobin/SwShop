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
        .layui-form-item{position: relative;}
        .layui-form-item i.layui-icon{position: absolute; top: 10px; left: 5px;}
        .layui-input {
            border-radius: 6px;
            text-indent: 18px;
        }
        #logo{display:block; width: 86%; margin: 0 auto;}
        .margin-top30{margin-top: 30px !important;}
    </style>

    {# HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 #}
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/static/html5shiv.min.js"></script>
    <script type="text/javascript" src="/static/respond.min.js"></script>
    <![endif]-->
    <script src="/static/jquery/jquery-3.6.0.min.js"></script>
    <script src="/static/layui/layui.js"></script>
</head>
<body>
<form class="layui-form" method="post" autocomplete="off">
    <div class="layui-form-item" style="display: none;">
        <!--img id="logo" src="" /-->
    </div>
    <div class="layui-form-item margin-top30">
        <div class="layui-form-inline">
            <i class="layui-icon layui-icon-username"></i>
            <input class="layui-input" type="text" maxlength="16" placeholder="请输入登录账号" name="account"
                   lay-verify="account" lay-verType="alert"/>
        </div>
    </div>
    <div class="layui-form-item">
        <i class="layui-icon layui-icon-password"></i>
        <input class="layui-input" type="password" maxlength="32" placeholder="请输入登录密码" name="password"
               lay-verify="password" lay-verType="alert"/>
    </div>
    <div class="layui-form-item margin-top30">
        <input type="hidden" name="hash_tk" value="<?php echo $csrf_token; ?>"/>
        <input class="layui-btn" type="submit" lay-submit lay-filter="login" value="登录"/>
    </div>
</form>
<script>
    $(function () {
        layui.use(['layer', 'form'], function(){
            let layer = layui.layer;
            let form = layui.form;

            form.verify({
                account: function(val){
                    val = $.trim(val);
                    if(val == ''){
                        return '请输入登录账号';
                    }
                    if(val.length > 16){
                        return '请输入有效的登录账号';
                    }
                },
                password: function(val){
                    val = $.trim(val);
                    if(val == ''){
                        return '请输入登录密码';
                    }
                    if(val.length > 32){
                        return '请输入有效的登录密码';
                    }
                }
            });

            form.on('submit(login)', function(formObj){
                $.ajax({
                    type: 'post',
                    url: window.location.href,
                    data: formObj.field,
                    success: function (res) {
                        if(res.msg != undefined && res.msg != ''){
                            layer.msg(res.msg);
                        }

                        if(res.status == 'success' && res.url != undefined && res.url != ''){
                            window.location.href = res.url;
                        }
                    },
                    error: function () {
                        layer.msg('未知错误，请稍候刷新页面重试！');
                    }
                });
                return false;
            });
        });
    });
</script>
</body>
</html>