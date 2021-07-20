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
RouteHelper::get('/', 'Index.Index.index');
RouteHelper::get('/login', 'Index.Index.login');
RouteHelper::post('/login', 'Index.Ajax.loginProcess');
RouteHelper::post('/register', 'Index.Ajax.registerProcess');
RouteHelper::get('/<cate_name>-c<cate_id>', 'Index.Product.category', [
    'cate_name' => '\w+',
    'cate_id' => '\d+',
]);
RouteHelper::get('/<prod_name>-p<prod_id>', 'Index.Product.detail', [
    'prod_name' => '\w+',
    'prod_id' => '\d+',
]);
RouteHelper::get('/product/category', 'Index.Product.category');
RouteHelper::get('/page-not-found', 'Index.Index.pageNotFound');

// 店铺管理路由配置
RouteHelper::get('/spadmin', 'SpAdmin.Index.index');
RouteHelper::get('/spadmin/dashboard', 'SpAdmin.Index.dashboard');
RouteHelper::get('/spadmin/login', 'SpAdmin.Index.login');
RouteHelper::post('/spadmin/login', 'SpAdmin.Ajax.login');
RouteHelper::get('/spadmin/logout', 'SpAdmin.Index.logout');
RouteHelper::get('/spadmin/config', 'SpAdmin.Config.index');
RouteHelper::get('/spadmin/category', 'SpAdmin.Category.index');
RouteHelper::get('/spadmin/category/<cate_id>', 'SpAdmin.Category.detail', ['cate_id' => '\d+']);
RouteHelper::post('/spadmin/category/<cate_id>', 'SpAdmin.Ajax.saveCategory', ['cate_id' => '\d+']);
RouteHelper::get('/spadmin/product', 'SpAdmin.Product.index');
RouteHelper::get('/spadmin/product/<prod_id>', 'SpAdmin.Product.detail', ['prod_id' => '\d+']);
RouteHelper::post('/spadmin/product/<prod_id>', 'SpAdmin.Ajax.saveProduct', ['prod_id' => '\d+']);
RouteHelper::get('/spadmin/customer', 'SpAdmin.Customer.index');
RouteHelper::get('/spadmin/customer/<customer_id>', 'SpAdmin.Customer.detail', ['customer_id' => '\d+']);
RouteHelper::get('/spadmin/order', 'SpAdmin.Order.index');
RouteHelper::get('/spadmin/order/<prod_id>', 'SpAdmin.Order.detail', ['prod_id' => '\d+']);

return [];
