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

        return $this->dbHelper->table('banner')->where(['shop_id' => $shopId])->select();
    }

    public function getBannerById(int $shopId, int $bannerId): array
    {
        if ($shopId <= 0 || $bannerId <= 0) {
            return [];
        }

        $bannerInfo = $this->dbHelper->table('banner')->where(
            ['shop_id' => $shopId, 'banner_id' => $bannerId])->find();
        if (empty($bannerInfo)) {
            return [];
        }

        $bannerInfo['image_list'] = $this->dbHelper->table('banner_image')->where(
            ['shop_id' => $shopId, 'banner_id' => $bannerId])
            ->orderBy(['sort' => 'asc'])->select();
        if(!empty($bannerInfo['image_list'])){
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
        $bannerInfo = $this->dbHelper->table('banner')->where(
            ['shop_id' => $shopId, 'banner_id' => $bannerId])->find();
        if (empty($bannerInfo)) {
            return 0;
        }

        $this->dbHelper->beginTransaction();
        try {
            $imgList = empty($data['image_list']) ? [] : $data['image_list'];
            unset($data['banner_id'], $data['created_at'], $data['created_by'], $data['image_list']);

            $this->dbHelper->table('banner')->where(['shop_id' => $shopId, 'banner_id' => $bannerId])->update($data);

            if (!empty($imgList)) {

            } else if (!empty($bannerInfo['image_list'])) {
                $this->dbHelper->table('banner_image')->where(['shop_id' => $shopId, 'banner_id' => $bannerId])->delete();
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
