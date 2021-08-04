<?php
/**
 * 文件上传相关业务逻辑
 * User: dao bin
 * Date: 2021/7/22
 * Time: 16:22
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class UploadBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function getUploadInfoByName(int $shopId, string $originName): array
    {
        if ($shopId < 0 || empty($originName)) {
            return [];
        }

        return $this->dbHelper->table('upload_file')->where(
            ['shop_id' => $shopId, 'origin_name' => $originName])
            ->find();
    }

    public function getUploadListByFolder(int $shopId, string $folder, int $page = 1, int $pageSize = 30): array
    {
        if ($shopId < 0 || empty($folder)) {
            return [];
        }

        return $this->dbHelper->table('upload_file')->where(
            ['shop_id' => $shopId, 'folder' => $folder])
            ->page($page, $pageSize)->select();
    }

    public function saveUploadInfo(array $data): int
    {
        if (empty($data['shop_id']) || empty($data['origin_name'])) {
            return 0;
        }

        $shopId = (int)$data['shop_id'];
        $uploadInfo = $this->getUploadInfoByName($shopId, $data['origin_name']);
        if (empty($uploadInfo)) {
            return $this->dbHelper->insert($data);
        }

        return $this->dbHelper->where(
            ['shop_id' => $shopId, 'upload_file_id' => $uploadInfo['upload_file_id']])->update($data);
    }
}
