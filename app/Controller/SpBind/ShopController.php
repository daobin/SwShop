<?php
/**
 * User: dao bin
 * Date: 2021/10/19
 * Time: 18:02
 */
declare(strict_types=1);

namespace App\Controller\SpBind;

use App\Biz\ShopBiz;
use App\Controller\Controller;
use App\Helper\SafeHelper;

class ShopController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            return [
                'code' => 0,
                'data' => (new ShopBiz())->getShopList()
            ];
        }

        return $this->render([
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BD', 'shop')
        ]);
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        $shopId = (int)$this->get('shop_id', 0);

        $data = [
            'shop_info' => (new ShopBiz())->getShopById($shopId),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BD', 'shop' . $shopId)
        ];

        return $this->render($data);
    }

    private function save()
    {
        $shopBiz = new ShopBiz();

        $shopId = (int)$this->get('shop_id', 0);
        $name = $this->post('shop_name');
        $domain = $this->post('shop_domain');
        $domain2 = $this->post('shop_domain2');
        $redirectCode = (int)$this->post('redirect_code', 0);
        $redirectCode = in_array($redirectCode, [0, 301, 302]) ? $redirectCode : 0;
        $status = (int)$this->post('shop_status', 0);
        $status = in_array($status, [0, 1]) ? $status : 0;
        if ($name == '') {
            return ['status' => 'fail', 'msg' => '请输入店铺名称'];
        }

        if ($domain == '') {
            return ['status' => 'fail', 'msg' => '请输入主域名'];
        }

        $domainInfo = $shopBiz->getShopByDomain($domain);
        if (!empty($domainInfo) && $domainInfo['shop_id'] != $shopId) {
            return ['status' => 'fail', 'msg' => '主域名已存在'];
        }

        $domainInfo = $shopBiz->getShopByDomain($domain2);
        if (!empty($domainInfo) && $domainInfo['shop_id'] != $shopId) {
            return ['status' => 'fail', 'msg' => '第2主域名已存在'];
        }

        $data = [
            'shop_id' => $shopId,
            'shop_name' => $name,
            'shop_domain' => $domain,
            'shop_domain2' => $domain2,
            'redirect_code' => $redirectCode,
            'shop_status' => $status,
            'operator' => $this->sysOperator
        ];

        if ($shopBiz->saveShop($data) > 0) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }

    public function delete()
    {
        $shopId = $this->post('shop_id', 0);
        if ((new ShopBiz())->delShopById((int)$shopId) > 0) {
            return ['status' => 'success', 'msg' => '删除成功'];
        }

        return ['status' => 'fail', 'msg' => '删除失败'];
    }
}
