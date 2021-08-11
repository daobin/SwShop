<?php
/**
 * 优惠券管理
 * User: AT0103
 * Date: 2021/8/11
 * Time: 13:56
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Controller\Controller;
use App\Helper\LanguageHelper;

class CouponController extends Controller
{
    public function index()
    {
        return '待处理';
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        return '待处理';
    }

    private function save()
    {
        $code = $this->request->get['code'];

        return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
    }
}
