<?php
/**
 * 广告图相关业务逻辑
 * User: dao bin
 * Date: 2021/8/9
 * Time: 16:55
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class BannerBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function getBannerList(int $shopId): array
    {
        if ($shopId <= 0) {
            return [];
        }

        $fields = ['banner_id', 'title', 'code', 'banner_status', 'updated_at', 'updated_by'];

        return $this->dbHelper->table('banner')->where(['shop_id' => $shopId])->fields($fields)->select();
    }

    public function getBannerById(int $shopId, int $bannerId): array
    {
        if ($shopId <= 0 || $bannerId <= 0) {
            return [];
        }

        $fields = ['banner_id', 'title', 'code', 'banner_status', 'updated_at', 'updated_by'];
        $bannerInfo = $this->dbHelper->table('banner')->where(['shop_id' => $shopId, 'banner_id' => $bannerId])
            ->fields($fields)->find();
        if (empty($bannerInfo)) {
            return [];
        }

        $fields = ['banner_image_id', 'banner_id', 'image_path', 'image_name', 'sort', 'is_new_window', 'window_link', 'updated_at', 'updated_by'];
        $bannerInfo['image_list'] = $this->dbHelper->table('banner_image')->where(
            ['shop_id' => $shopId, 'banner_id' => $bannerId])
            ->fields($fields)->orderBy(['sort' => 'asc'])->select();
        if (!empty($bannerInfo['image_list'])) {
            $bannerInfo['image_list'] = array_column($bannerInfo['image_list'], null, 'sort');
        }

        return $bannerInfo;
    }

    public function getBannerByCode(int $shopId, string $code): array
    {
        if ($shopId <= 0 || empty($code)) {
            return [];
        }

        $fields = ['banner_id', 'title', 'code', 'banner_status', 'updated_at', 'updated_by'];
        $bannerInfo = $this->dbHelper->table('banner')->where(['shop_id' => $shopId, 'code' => $code])
            ->fields($fields)->find();
        if (empty($bannerInfo)) {
            return [];
        }

        $fields = ['banner_image_id', 'banner_id', 'image_path', 'image_name', 'sort', 'is_new_window', 'window_link', 'updated_at', 'updated_by'];
        $bannerInfo['image_list'] = $this->dbHelper->table('banner_image')->where(
            ['shop_id' => $shopId, 'banner_id' => $bannerInfo['banner_id']])
            ->fields($fields)->orderBy(['sort' => 'asc'])->select();
        if (!empty($bannerInfo['image_list'])) {
            $bannerInfo['image_list'] = array_column($bannerInfo['image_list'], null, 'sort');
        }

        return $bannerInfo;
    }

    public function saveBanner(array $data): int
    {
        if (empty($data['shop_id']) || empty($data['banner_id'])) {
            return 0;
        }

        $shopId = (int)$data['shop_id'];
        $bannerId = (int)$data['banner_id'];
        $bannerInfo = $this->getBannerById($shopId, $bannerId);
        if (empty($bannerInfo)) {
            return 0;
        }

        $this->dbHelper->beginTransaction();
        try {
            $imgList = empty($data['image_list']) ? [] : $data['image_list'];
            unset($data['banner_id'], $data['created_at'], $data['created_by'], $data['image_list']);

            $this->dbHelper->table('banner')->where(['shop_id' => $shopId, 'banner_id' => $bannerId])->update($data);

            if (!empty($imgList)) {
                foreach ($imgList as $sort => $image) {
                    if (isset($bannerInfo['image_list'][$sort])) {
                        unset($image['created_at'], $image['created_by']);
                        $this->dbHelper->table('banner_image')->where(
                            ['shop_id' => $shopId, 'banner_image_id' => $bannerInfo['image_list'][$sort]['banner_image_id']])->update($image);

                        unset($bannerInfo['image_list'][$sort]);
                    } else {
                        $this->dbHelper->table('banner_image')->insert($image);
                    }
                }
            }

            if (!empty($bannerInfo['image_list'])) {
                foreach ($bannerInfo['image_list'] as $image) {
                    $this->dbHelper->table('banner_image')->where(['shop_id' => $shopId, 'banner_image_id' => $image['banner_image_id']])->delete();
                }
            }

            $this->dbHelper->commit();
        } catch (\PDOException $e) {
            print_r(__CLASS__ . ' :: ' . $e->getMessage());
            $bannerId = 0;
            $this->dbHelper->rollBack();
        }

        return $bannerId;
    }
}
