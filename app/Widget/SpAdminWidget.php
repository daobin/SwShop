<?php
declare(strict_types=1);

namespace App\Widget;

class SpAdminWidget extends Widget
{
    public function header($params = [])
    {
        $params['show_top_line'] = $params['show_top_line'] ?? true;
        return $this->render('header', $params);
    }

    public function leftNav($params = [])
    {
        $params['navs'] = [
            ['name' => '数据表盘', 'icon' => 'layui-icon-console', 'iframe' => 'dashboard'],
            ['name' => '用户管理', 'icon' => 'layui-icon-user', 'iframe' => 'customer'],
            ['name' => '订单管理', 'icon' => 'layui-icon-cart', 'iframe' => 'order'],
            ['name' => '商品管理', 'icon' => 'layui-icon-note', 'sub_navs' =>
                [
                    ['name' => '商品类目', 'icon' => 'layui-icon-more-vertical', 'iframe' => 'category'],
                    ['name' => '商品属性', 'icon' => 'layui-icon-component', 'iframe' => 'attr-group'],
                    ['name' => '商品列表', 'icon' => 'layui-icon-list', 'iframe' => 'product'],
                ]
            ],
            ['name' => '促销管理', 'icon' => 'layui-icon-rate', 'sub_navs' =>
                [
                    ['name' => '广告图', 'icon' => 'layui-icon-picture', 'iframe' => 'banner'],
//                    ['name' => '优惠券', 'icon' => 'layui-icon-gift', 'iframe' => 'coupon'],
//                    ['name' => '限时限量', 'icon' => 'layui-icon-time', 'iframe' => 'time-limited'],
                    ['name' => '系统邮件模板', 'icon' => 'layui-icon-template', 'iframe' => 'email-tpl'],
                ]
            ],
//            ['name' => '定时工具', 'icon' => 'layui-icon-at', 'sub_navs' =>
//                [
//                    ['name' => '消息通知', 'icon' => 'layui-icon-notice', 'iframe' => 'cron/notice'],
//                ]
//            ],
            ['name' => '系统设置', 'icon' => 'layui-icon-set', 'sub_navs' =>
                [
                    ['name' => '商城信息', 'icon' => 'layui-icon-tips', 'iframe' => 'config-web_info'],
                    ['name' => '邮件配置', 'icon' => 'layui-icon-email', 'iframe' => 'config-mail'],
                    ['name' => 'OSS 配置', 'icon' => 'layui-icon-file', 'iframe' => 'config-oss'],
                    ['name' => '仓库配置', 'icon' => 'layui-icon-location', 'iframe' => 'warehouse'],
                    ['name' => '语言配置', 'icon' => 'layui-icon-dialogue', 'iframe' => 'language'],
                    ['name' => '币种配置', 'icon' => 'layui-icon-dollar', 'iframe' => 'currency'],
                    ['name' => '国家地址', 'icon' => 'layui-icon-website', 'iframe' => 'country'],
                    ['name' => '支付方式', 'icon' => 'layui-icon-senior', 'iframe' => 'payment'],
                    ['name' => '货运方式', 'icon' => 'layui-icon-release', 'iframe' => 'shipping'],
                ]
            ],
            ['name' => '管理员', 'icon' => 'layui-icon-group', 'iframe' => 'admin'],
        ];
        return $this->render('left_nav', $params);
    }

    public function footer($params = [])
    {
        return $this->render('footer', $params);
    }
}
