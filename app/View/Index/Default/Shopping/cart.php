<?php
$widget_params['tkd_title'] = 'Shopping Cart - ' . $website_name;
$widget_params['shopping_nav_img'] = 'step.png';
\App\Helper\TemplateHelper::widget('index', 'header', $widget_params);

if (empty($cart_list)) {
    include 'cart_empty.php';
} else {
    include 'cart_product.php';
}

\App\Helper\TemplateHelper::widget('index', 'footer', $widget_params);
