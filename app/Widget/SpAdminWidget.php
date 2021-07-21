<?php
declare(strict_types=1);

namespace App\Widget;

class SpAdminWidget extends Widget
{
    public function header($params = [])
    {
        $data = [
            'show_top_line' => $params['show_top_line'] ?? true
        ];
        return $this->render('header', $data);
    }

    public function leftNav()
    {
        $navs = [
            ['name' => '数据表盘', 'icon' => 'layui-icon-home', 'iframe' => 'dashboard'],
            ['name' => '用户管理', 'icon' => 'layui-icon-user', 'iframe' => 'customer'],
            ['name' => '订单管理', 'icon' => 'layui-icon-cart', 'iframe' => 'order'],
            ['name' => '商品管理', 'icon' => 'layui-icon-note', 'sub_navs' =>
                [
                    ['name' => '商品类目', 'icon' => 'layui-icon-more-vertical', 'iframe' => 'category'],
                    ['name' => '商品列表', 'icon' => 'layui-icon-list', 'iframe' => 'product'],
                    ['name' => '商品添加', 'icon' => 'layui-icon-add-1', 'iframe' => 'product/0'],
                ]
            ],
            ['name' => '促销管理', 'icon' => 'layui-icon-flag', 'sub_navs' =>
                [
                    ['name' => '广告图', 'icon' => 'layui-icon-picture', 'iframe' => 'banner'],
                    ['name' => '优惠券', 'icon' => 'layui-icon-gift', 'iframe' => 'coupon'],
                    ['name' => '限时限量', 'icon' => 'layui-icon-time', 'iframe' => 'time-limited'],
                ]
            ],
            ['name' => '系统设置', 'icon' => 'layui-icon-set', 'sub_navs' =>
                [
                    ['name' => '商城信息', 'icon' => 'layui-icon-tips', 'iframe' => 'config-web_info'],
                    ['name' => '邮件配置', 'icon' => 'layui-icon-email', 'iframe' => 'config-mail'],
                    ['name' => '多语言配置', 'icon' => 'layui-icon-read', 'iframe' => 'language'],
                    ['name' => '多币种配置', 'icon' => 'layui-icon-rmb', 'iframe' => 'currency'],
                    ['name' => '支付方式', 'icon' => 'layui-icon-senior', 'iframe' => 'payment'],
                ]
            ],
        ];
        return $this->render('left_nav', ['navs' => $navs]);
    }

    public function footer()
    {
        return $this->render('footer');
    }
}
