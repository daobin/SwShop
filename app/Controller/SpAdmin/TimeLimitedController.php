<?php
/**
 * 限时限量管理
 * User: AT0103
 * Date: 2021/8/11
 * Time: 13:56
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Controller\Controller;
use App\Helper\LanguageHelper;

class TimeLimitedController extends Controller
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
        $limitedId = $this->request->get['limited_id'] ?? 0;
        $limitedId = (int)$limitedId;

        return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
    }
}
