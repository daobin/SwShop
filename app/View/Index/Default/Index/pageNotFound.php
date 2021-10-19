<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>

    <title>Page Not Found</title>
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.css"/>
    <style>
        body img {
            position: absolute;
            top: 100px;
            left: 0;
            right: 0;
            margin: auto;
        }

        body h5 {
            position: absolute;
            top: 500px;
            left: 0;
            right: 0;
            margin: auto;
            text-align: center;
            font-size: 36px;
            font-weight: bold;
        }

        body a {
            position: absolute;
            top: 600px;
            left: 0;
            right: 0;
            margin: auto;
            text-align: center;
            font-size: 30px !important;
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div id="error">
    <img src="/static/index/default/404.png"/>
    <h5 class="text-muted">ERROR 404 - Page Not Found</h5>
</div>
<script>
    if (self == top) {
        var a = document.createElement('a');
        a.href = '/';
        a.class = 'text-primary';

        var n = document.createTextNode('Back to home');
        a.appendChild(n);

        document.getElementById('error').appendChild(a);
    }
</script>
</body>
</html>
