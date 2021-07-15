<?php
declare(strict_types=1);

use App\Helper\RouteHelper;

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

RouteHelper::get('/spadmin', 'SpAdmin.Index.index');
RouteHelper::get('/spadmin/config', 'SpAdmin.Config.index');
RouteHelper::get('/spadmin/categories', 'SpAdmin.Category.index');
RouteHelper::get('/spadmin/category/<cate_id>', 'SpAdmin.Category.detail', ['cate_id' => '\d+']);
RouteHelper::post('/spadmin/category/<cate_id>', 'SpAdmin.Ajax.saveCategory', ['cate_id' => '\d+']);
RouteHelper::get('/spadmin/products', 'SpAdmin.Product.index');
RouteHelper::get('/spadmin/product/<prod_id>', 'SpAdmin.Product.detail', ['prod_id' => '\d+']);
RouteHelper::post('/spadmin/product/<prod_id>', 'SpAdmin.Ajax.saveProduct', ['prod_id' => '\d+']);

return [];
