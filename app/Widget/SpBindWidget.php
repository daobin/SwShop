<?php
declare(strict_types=1);

namespace App\Widget;

class SpBindWidget extends Widget
{
    public function header($params = [])
    {
        $params['show_top_line'] = $params['show_top_line'] ?? true;
        return $this->render('header', $params);
    }

    public function leftNav($params = [])
    {
        $params['navs'] = [
            ['name' => '店铺管理', 'icon' => 'layui-icon-find-fill', 'iframe' => 'shop'],
//            ['name' => '仓库配置', 'icon' => 'layui-icon-location', 'iframe' => 'warehouse'],
//            ['name' => '语言配置', 'icon' => 'layui-icon-dialogue', 'iframe' => 'language'],
//            ['name' => '币种配置', 'icon' => 'layui-icon-dollar', 'iframe' => 'currency'],
            ['name' => '国家地址', 'icon' => 'layui-icon-website', 'iframe' => 'country'],
//            ['name' => '支付方式', 'icon' => 'layui-icon-senior', 'iframe' => 'payment'],
//            ['name' => '货运方式', 'icon' => 'layui-icon-release', 'iframe' => 'shipping'],
            ['name' => '管理员', 'icon' => 'layui-icon-group', 'iframe' => 'admin'],
        ];
        return $this->render('left_nav', $params);
    }

    public function footer($params = [])
    {
        return $this->render('footer', $params);
    }
}
