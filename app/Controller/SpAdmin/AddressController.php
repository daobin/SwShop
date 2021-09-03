<?php
/**
 * 国家、省州等地址管理
 * User: dao bin
 * Date: 2021/8/31
 * Time: 10:52
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\AddressBiz;
use App\Controller\Controller;
use App\Helper\SafeHelper;

class AddressController extends Controller
{
    public function country()
    {
        if ($this->request->isAjax) {
            $addrBiz = new AddressBiz();

            $page = $this->request->get['page'] ?? 1;
            $pageSize = $this->request->get['limit'] ?? 10;
            $countryList = $addrBiz->getCountryList($this->shopId, (int)$page, (int)$pageSize);

            return [
                'code' => 0,
                'count' => $addrBiz->count,
                'data' => $countryList
            ];
        }

        $data = [
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'country')
        ];
        return $this->render($data);
    }

    public function countryDetail()
    {
        if ($this->request->isPost) {
            return $this->countrySave();
        }

        $addBiz = new AddressBiz();
        $countryId = (int)$this->get('addr_id', 0);

        $data = [
            'country_id' => $countryId,
            'country_info' => $addBiz->getCountryById($this->shopId, $countryId),
            'country_list' => $addBiz->getSysCountryList(),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'country' . $countryId)
        ];

        return $this->render($data);
    }

    private function countrySave()
    {
        $origCountryId = (int)$this->get('addr_id', 0);
        $countryId = (int)$this->post('country_id', 0);
        if ($countryId <= 0) {
            return ['status' => 'fail', 'msg' => '请选择国家'];
        }

        $addrBiz = new AddressBiz();
        if ($origCountryId !== $countryId && $addrBiz->getCountryById($this->shopId, $countryId)) {
            return ['status' => 'fail', 'msg' => '国家已存在'];
        }

        $sysCountry = $addrBiz->getSysCountryById($countryId);
        if (empty($sysCountry)) {
            return ['status' => 'fail', 'msg' => '国家无效'];
        }

        $data = [
            'country_id' => $countryId,
            'country_name' => $sysCountry['country_name'],
            'iso_code_2' => $sysCountry['iso_code_2'],
            'iso_code_3' => $sysCountry['iso_code_3'],
            'icon_path' => $sysCountry['icon_path'],
            'is_high_risk' => isset($this->request->post['is_high_risk']) ? 1 : 0,
            'sort' => $this->post('sort', 0),
            'operator' => $this->operator
        ];

        if ($addrBiz->saveCountry($this->shopId, $origCountryId, $data)) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }

    public function countryDelete()
    {
        $countryId = (int)$this->post('addr_id', 0);
        if ($countryId <= 0) {
            return ['status' => 'fail', 'msg' => '国家无效'];
        }

        if ((new AddressBiz())->delCountryById($this->shopId, $countryId)) {
            return ['status' => 'success', 'msg' => '删除成功'];
        }

        return ['status' => 'fail', 'msg' => '删除失败'];
    }

    public function zone()
    {
        $countryId = (int)$this->get('country_id', 0);

        $addrBiz = new AddressBiz();
        $countryInfo = $addrBiz->getCountryById($this->shopId, $countryId);

        if ($this->request->isAjax) {
            if (empty($countryInfo)) {
                return [
                    'code' => 0,
                    'count' => 0,
                    'data' => []
                ];
            }

            $page = $this->request->get['page'] ?? 1;
            $pageSize = $this->request->get['limit'] ?? 10;
            $zoneList = $addrBiz->getZoneList($this->shopId, $countryId, (int)$page, (int)$pageSize);

            return [
                'code' => 0,
                'count' => $addrBiz->count,
                'data' => $zoneList
            ];
        }

        $data = [
            'country_id' => $countryId,
            'country_name' => $countryInfo['country_name'] ?? '',
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'zone')
        ];
        return $this->render($data);
    }

    public function zoneDetail()
    {
        if ($this->request->isPost) {
            return $this->zoneSave();
        }

        $addBiz = new AddressBiz();
        $countryId = (int)$this->get('country_id', 0);
        $zoneId = (int)$this->get('addr_id', 0);

        $data = [
            'country_id' => $countryId,
            'zone_id' => $zoneId,
            'zone_info' => $addBiz->getZoneById($this->shopId, $zoneId),
            'zone_list' => $addBiz->getSysZoneList(),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'zone' . $zoneId)
        ];

        return $this->render($data);
    }

    private function zoneSave()
    {
        $origZoneId = (int)$this->get('addr_id', 0);
        $zoneId = (int)$this->post('zone_id', 0);
        if ($zoneId <= 0) {
            return ['status' => 'fail', 'msg' => '请选择州'];
        }

        $addrBiz = new AddressBiz();
        if ($origZoneId !== $zoneId && $addrBiz->getZoneById($this->shopId, $zoneId)) {
            return ['status' => 'fail', 'msg' => '州已存在'];
        }

        $countryId = (int)$this->post('country_id', 0);
        $countryInfo = $addrBiz->getCountryById($this->shopId, $countryId);
        if (empty($countryInfo)) {
            return ['status' => 'fail', 'msg' => '国家无效'];
        }

        $sysZone = $addrBiz->getSysZoneById($zoneId);
        if (empty($sysZone)) {
            return ['status' => 'fail', 'msg' => '州无效'];
        }

        $data = [
            'country_id' => $countryId,
            'zone_id' => $zoneId,
            'zone_name' => $sysZone['zone_name'],
            'zone_code' => $sysZone['zone_code'],
            'sort' => $this->post('sort', 0),
            'operator' => $this->operator
        ];

        if ($addrBiz->saveZone($this->shopId, $origZoneId, $data)) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }

    public function zoneDelete()
    {
        $zoneId = (int)$this->post('addr_id', 0);
        if ($zoneId <= 0) {
            return ['status' => 'fail', 'msg' => '州无效'];
        }

        if ((new AddressBiz())->delZoneById($this->shopId, $zoneId)) {
            return ['status' => 'success', 'msg' => '删除成功'];
        }

        return ['status' => 'fail', 'msg' => '删除失败'];
    }
}
