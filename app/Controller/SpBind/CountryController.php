<?php
declare(strict_types=1);

namespace App\Controller\SpBind;

use App\Biz\AddressBiz;
use App\Controller\Controller;
use App\Helper\SafeHelper;

class CountryController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            return [
                'code' => 0,
                'data' => (new AddressBiz())->getSysCountryList()
            ];
        }

        return $this->render([
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BD', 'country')
        ]);
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        $addressBiz = new AddressBiz();
        $countryId = (int)$this->get('country_id', 0);

        $data = [
            'country_info' => $addressBiz->getSysCountryById($countryId),
            'zone_list' => $addressBiz->getSysZoneList($countryId),
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BD', 'country' . $countryId)
        ];

        return $this->render($data);
    }

    public function save()
    {
        $countryId = (int)$this->get('country_id');
        $countryName = $this->post('name');
        $code2 = $this->post('code2', '', 'trim,strtoupper');
        $code3 = $this->post('code3', '', 'trim,strtoupper');
        if (empty($countryName) || strlen($code2) != 2 || strlen($code3) != 3) {
            return ['status' => 'fail', 'msg' => '请输入有效的国家名称和编码'];
        }

        $addrBiz = new AddressBiz();
        $nameCountryInfo = $addrBiz->getSysCountryByName($countryName);
        if (!empty($nameCountryInfo) && $nameCountryInfo['country_id'] != $countryId) {
            return ['status' => 'fail', 'msg' => '国家名称已存在'];
        }
        $codeCountryInfo = $addrBiz->getSysCountryByCode2($code2);
        if (!empty($codeCountryInfo) && $codeCountryInfo['country_id'] != $countryId) {
            return ['status' => 'fail', 'msg' => '国家编码（2位）已存在'];
        }
        $codeCountryInfo = $addrBiz->getSysCountryByCode3($code3);
        if (!empty($codeCountryInfo) && $codeCountryInfo['country_id'] != $countryId) {
            return ['status' => 'fail', 'msg' => '国家编码（3位）已存在'];
        }

        $data = [
            'country_id' => $countryId,
            'country_name' => $countryName,
            'iso_code_2' => $code2,
            'iso_code_3' => $code3,
            'operator' => $this->sysOperator
        ];
        if($addrBiz->saveSysCountry($data) > 0){
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => '保存失败'];
    }

    public function syncZoneList()
    {
        $syncUrl = '';
        $countryCode = $this->post('country_code', '', 'trim,strtoupper');
        switch ($countryCode) {
            case 'US':
                $syncUrl = 'https://www.nowmsg.com/us/all_state.asp';
                break;
            default:
                break;
        }

        $addrBiz = new AddressBiz();
        $sysCountryInfo = $addrBiz->getSysCountryByCode2($countryCode);
        if (empty($syncUrl) || empty($sysCountryInfo['country_id'])) {
            return [
                'status' => 'fail',
                'msg' => 'Country Invalid'
            ];
        }


        $ch = curl_init();

        $chOptions = [
            CURLOPT_URL => $syncUrl,
            CURLOPT_HTTPHEADER => [
                'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
            ],
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true
        ];

        curl_setopt_array($ch, $chOptions);
        $res = curl_exec($ch);
        $errNo = curl_errno($ch);
        if ($errNo > 0) {
            return [
                'status' => 'fail',
                'msg' => $errNo . ' : ' . curl_error($ch)
            ];
        }
        curl_close($ch);

        preg_match_all('/<li\s*id.+><a.*>(.+)<\/a><\/li>/i', $res, $zoneList);
        $zoneList = $zoneList[1] ?? [];
        if (empty($zoneList) || !is_array($zoneList)) {
            return [
                'status' => 'fail',
                'msg' => 'Data empty'
            ];
        }

        $res = [];
        foreach ($zoneList as $zone) {
            $zone = str_replace(['(', ')'], ',', $zone);
            list($zoneName, $zoneCode) = explode(',', $zone);
            $res[$zoneCode] = $zoneName;
            $addrBiz->saveSysZone([
                'country_id' => $sysCountryInfo['country_id'],
                'zone_name' => $zoneName,
                'zone_code' => $zoneCode,
                'operator' => $this->sysOperator
            ]);
        }

        return ['status' => 'success', 'zone_list' => $res];
    }
}