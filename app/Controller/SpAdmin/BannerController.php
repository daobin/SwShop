<?php
/**
 * 广告图业务管理
 * User: dao bin
 * Date: 2021/8/9
 * Time: 16:07
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Biz\BannerBiz;
use App\Biz\UploadBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\OssHelper;
use App\Helper\SafeHelper;

class BannerController extends Controller
{
    public function index()
    {
        if ($this->request->isAjax) {
            return [
                'code' => 0,
                'data' => (new BannerBiz())->getBannerList($this->shopId)
            ];
        }

        return $this->render();
    }

    public function detail()
    {
        if ($this->request->isPost) {
            return $this->save();
        }

        $bannerId = $this->request->get['banner_id'] ?? 0;
        $bannerId = (int)$bannerId;
        if ($bannerId <= 0) {
            return LanguageHelper::get('invalid_request');
        }

        $bannerInfo = (new BannerBiz())->getBannerById($this->shopId, $bannerId);
        if (empty($bannerInfo)) {
            return LanguageHelper::get('invalid_request');
        }

        $data = [
            'banner_info' => $bannerInfo,
            'csrf_token' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('BG', 'banner' . $bannerId),
            'upload_folders' => (new UploadBiz())->getFolderArr($this->shopId),
        ];

        return $this->render($data);
    }

    private function save()
    {
        $bannerId = $this->request->get['banner_id'] ?? 0;
        $bannerId = (int)$bannerId;
        if ($bannerId <= 0) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
        }

        $bannerBiz = new BannerBiz();

        $bannerInfo = $bannerBiz->getBannerById($this->shopId, $bannerId);
        if (empty($bannerInfo)) {
            return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
        }

        $time = time();
        $data = [
            'banner_id' => $bannerId,
            'shop_id' => $this->shopId,
            'updated_at' => $time,
            'updated_by' => $this->operator,
            'image_list' => []
        ];

        if (!empty($this->post('image_list')) && is_array($this->post('image_list'))) {
            $ossAccessHost = (new OssHelper($this->shopId))->accessHost;
            foreach ($this->post('image_list') as $sort => $image) {
                $bannerInfo['image_list'][] = [
                    'banner_id' => $bannerId,
                    'shop_id' => $this->shopId,
                    'image_path' => str_replace($ossAccessHost, '', dirname($image)),
                    'image_name' => basename($image),
                    'sort' => $sort,
                    'created_at' => $time,
                    'created_by' => $this->operator,
                    'updated_at' => $time,
                    'updated_by' => $this->operator
                ];
            }
        }

        $update = $bannerBiz->saveBanner($data);
        if ($update > 0) {
            return ['status' => 'success', 'msg' => '保存成功'];
        }

        return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_request')];
    }
}
