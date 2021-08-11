<?php
/**
 * 广告图管理
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
            'oss_access_host' => (new OssHelper($this->shopId))->accessHost,
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
            'banner_status' => empty($this->post('banner_status')) ? 0 : 1,
            'updated_at' => $time,
            'updated_by' => $this->operator,
            'image_list' => []
        ];

        $imageList = $this->post('image_list');
        $isNewList = $this->post('is_new', []);
        $linkList = $this->post('link', []);
        if (!empty($imageList) && is_array($imageList)) {
            $ossAccessHost = (new OssHelper($this->shopId))->accessHost;
            foreach ($imageList as $sort => $image) {
                $link = empty($linkList[$sort]) ? '' : trim($linkList[$sort]);
                $link = trim($link, '/');
                if (!empty($link) && !filter_var($link, FILTER_VALIDATE_URL)) {
                    return ['status' => 'fail', 'msg' => '图片 [' . ($sort + 1) . '] 跳转链接无效'];
                }

                $data['image_list'][] = [
                    'banner_id' => $bannerId,
                    'shop_id' => $this->shopId,
                    'image_path' => str_replace($ossAccessHost, '', dirname($image)),
                    'image_name' => basename($image),
                    'sort' => $sort,
                    'is_new_window' => empty($isNewList[$sort]) ? 0 : 1,
                    'window_link' => $link,
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
