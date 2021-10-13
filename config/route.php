<?php
/**
 * 路由配置文件
 * 目前只支持GET、POST两种请求方式的简单路由，不含路由组等复杂功能
 * User: dao bin
 * Date: 2021/7/16
 */
declare(strict_types=1);

use App\Helper\RouteHelper;

// 店铺前台路由配置
RouteHelper::post('/login', 'Index.Ajax.loginProcess');
RouteHelper::post('/register', 'Index.Ajax.registerProcess');
RouteHelper::get('/zones', 'Index.Ajax.getZoneList');
RouteHelper::post('/add-to-cart', 'Index.Ajax.addToCart');
RouteHelper::post('/update-cart-product', 'Index.Ajax.updateCartProduct');
RouteHelper::post('/delete-cart-product', 'Index.Ajax.deleteCartProduct');
RouteHelper::post('/default-address', 'Index.Ajax.setDefaultAddress');
RouteHelper::post('/delete-address', 'Index.Ajax.deleteAddress');
RouteHelper::post('/customer-service', 'Index.Ajax.customerService');

RouteHelper::get('/', 'Index.Index.index');
RouteHelper::get('/login', 'Index.Index.login');
RouteHelper::get('/logout', 'Index.Index.logout');
RouteHelper::any('/account', 'Index.Customer.index');
RouteHelper::any('/password', 'Index.Customer.password');
RouteHelper::get('/address', 'Index.Customer.address');
RouteHelper::any('/address/add', 'Index.Customer.addressDetail');
RouteHelper::any('/address/<addr_id>', 'Index.Customer.addressDetail', ['addr_id' => '\d+']);
RouteHelper::get('/order', 'Index.Customer.order');
RouteHelper::get('/order/<order_number>', 'Index.Customer.orderDetail', ['order_number' => '[\w\d]+']);
RouteHelper::any('/order-tracking', 'Index.Index.orderTracking');
RouteHelper::get('/customer-service', 'Index.Index.customerService');
RouteHelper::get('/<cate_name>-c<cate_id>', 'Index.Product.category', [
    'cate_name' => '[\w\-]+',
    'cate_id' => '\d+',
]);
RouteHelper::get('/<prod_name>-p<prod_id>', 'Index.Product.detail', [
    'prod_name' => '[\w\-]+',
    'prod_id' => '\d+',
]);
RouteHelper::get('/search', 'Index.Product.search');

RouteHelper::get('/shopping/cart', 'Index.Shopping.cart');
RouteHelper::get('/shopping/confirmation', 'Index.Shopping.confirmation');
RouteHelper::post('/shopping/payment', 'Index.Shopping.payment');
RouteHelper::get('/shopping/payment-handler', 'Index.Shopping.paymentHandler');
RouteHelper::get('/shopping/payment-cancel', 'Index.Shopping.paymentCancel');
RouteHelper::get('/shopping/success', 'Index.Shopping.success');
RouteHelper::get('/page-not-found', 'Index.Index.pageNotFound');


// 店铺管理路由配置
RouteHelper::get('/spadmin', 'SpAdmin.Index.index');
RouteHelper::get('/spadmin/dashboard', 'SpAdmin.Index.dashboard');
RouteHelper::get('/spadmin/login', 'SpAdmin.Index.login');
RouteHelper::post('/spadmin/login', 'SpAdmin.Index.loginProcess');
RouteHelper::get('/spadmin/logout', 'SpAdmin.Index.logout');
RouteHelper::get('/spadmin/upload-image', 'SpAdmin.Upload.image');
RouteHelper::post('/spadmin/upload-image', 'SpAdmin.Upload.uploadImage');
RouteHelper::get('/spadmin/config-<cfg_grp>', 'SpAdmin.Config.index', ['cfg_grp' => '[\w\d]+']);
RouteHelper::any('/spadmin/config/<cfg_key>', 'SpAdmin.Config.detail', ['cfg_key' => '[\w\d]+']);
RouteHelper::get('/spadmin/country', 'SpAdmin.Address.country');
RouteHelper::any('/spadmin/country/<addr_id>', 'SpAdmin.Address.countryDetail', ['addr_id' => '\d+']);
RouteHelper::post('/spadmin/country/delete', 'SpAdmin.Address.countryDelete');
RouteHelper::get('/spadmin/zone', 'SpAdmin.Address.zone');
RouteHelper::any('/spadmin/zone/<addr_id>', 'SpAdmin.Address.zoneDetail', ['addr_id' => '\d+']);
RouteHelper::post('/spadmin/zone/delete', 'SpAdmin.Address.zoneDelete');
RouteHelper::get('/spadmin/warehouse', 'SpAdmin.Warehouse.index');
RouteHelper::any('/spadmin/warehouse/<code>', 'SpAdmin.Warehouse.detail', ['code' => '[\w\d]+']);
RouteHelper::post('/spadmin/warehouse/delete', 'SpAdmin.Warehouse.delete');
RouteHelper::get('/spadmin/language', 'SpAdmin.Language.index');
RouteHelper::any('/spadmin/language/<code>', 'SpAdmin.Language.detail', ['code' => '[\w\d]+']);
RouteHelper::post('/spadmin/language/delete', 'SpAdmin.Language.delete');
RouteHelper::get('/spadmin/currency', 'SpAdmin.Currency.index');
RouteHelper::any('/spadmin/currency/<code>', 'SpAdmin.Currency.detail', ['code' => '[\w\d]+']);
RouteHelper::post('/spadmin/currency/delete', 'SpAdmin.Currency.delete');
RouteHelper::get('/spadmin/payment', 'SpAdmin.Payment.index');
RouteHelper::any('/spadmin/payment/<code>', 'SpAdmin.Payment.detail', ['code' => '[\w\d]+']);
RouteHelper::post('/spadmin/payment/delete', 'SpAdmin.Payment.delete');
RouteHelper::get('/spadmin/shipping', 'SpAdmin.Shipping.index');
RouteHelper::any('/spadmin/shipping/<code>', 'SpAdmin.Shipping.detail', ['code' => '[\w\d]+']);
RouteHelper::post('/spadmin/shipping/delete', 'SpAdmin.Shipping.delete');
RouteHelper::get('/spadmin/category', 'SpAdmin.Category.index');
RouteHelper::any('/spadmin/category/<cate_id>', 'SpAdmin.Category.detail', ['cate_id' => '\d+']);
RouteHelper::get('/spadmin/product', 'SpAdmin.Product.index');
RouteHelper::any('/spadmin/product/<prod_id>', 'SpAdmin.Product.detail', ['prod_id' => '\d+']);
RouteHelper::get('/spadmin/customer', 'SpAdmin.Customer.index');
RouteHelper::any('/spadmin/customer/<customer_id>', 'SpAdmin.Customer.detail', ['customer_id' => '\d+']);
RouteHelper::get('/spadmin/order', 'SpAdmin.Order.index');
RouteHelper::any('/spadmin/order/<order_number>', 'SpAdmin.Order.detail', ['order_number' => '[\w\d]+']);
RouteHelper::get('/spadmin/banner', 'SpAdmin.Banner.index');
RouteHelper::any('/spadmin/banner/<banner_id>', 'SpAdmin.Banner.detail', ['banner_id' => '\d+']);
RouteHelper::get('/spadmin/coupon', 'SpAdmin.Coupon.index');
RouteHelper::any('/spadmin/coupon/<code>', 'SpaAdmin.Coupon.detail', ['code' => '[\w\d]+']);
RouteHelper::get('/spadmin/time-limited', 'SpAdmin.TimeLimited.index');
RouteHelper::any('/spadmin/time-limited/<limited_id>', 'SpaAdmin.TimeLimited.detail', ['limited_id' => '\d+']);
RouteHelper::get('/spadmin/email-tpl', 'SpAdmin.Email.emailTpl');
RouteHelper::any('/spadmin/email-tpl/<template>', 'SpAdmin.Email.emailTplDetail', ['template' => '\w+']);

return [];
