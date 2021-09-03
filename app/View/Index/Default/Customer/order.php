<?php
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params ?? []);
?>
    <div class="hd-height-15">&nbsp;</div>
    <div id="hd-crumb" class="container">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">Order History</li>
        </ol>
    </div>
    <div class="container">
        <ul class="nav nav-pills nav-justified">
            <li><a href="/account.html">My Profile</a></li>
            <li><a href="/password.html">Change Password</a></li>
            <li><a href="/address.html">Address Book</a></li>
            <li class="active"><a>Order History</a></li>
        </ul>
        <div class="page-header">
            <h2 class="hd-color-333">Order History</h2>
        </div>
        <div class="row hd-margin-top-30">
            
        </div>
    </div>
<?php
\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params ?? []);
