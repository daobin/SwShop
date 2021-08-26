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

    public function getFolderArr(int $shopId): array
    {
        if($shopId <= 0){
            return [];
        }

        $folderArr = $this->dbHelper->table('upload_file')->where(['shop_id' => $shopId, 'folder' => ['<>', 'def']])
            ->fields(['folder'])->groupBy(['folder'])->select();
        if(empty($folderArr)){
            return [];
        }

        $folderArr = array_column($folderArr, 'folder');
        array_unshift($folderArr, 'def');

        return $folderArr;
    }

    public function getUploadInfoByName(int $shopId, string $originName): array
    {
        if ($shopId < 0 || empty($originName)) {
            return [];
        }

        $fields = ['upload_file_id', 'origin_name', 'oss_object', 'file_class', 'folder', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('upload_file')->where(['shop_id' => $shopId, 'origin_name' => $originName])
            ->fields($fields)->find();
    }

    public function getUploadListByFolder(int $shopId, string $folder, int $page = 1, int $pageSize = 30): array
    {
        if ($shopId < 0 || empty($folder)) {
            return [];
        }

        $fields = ['upload_file_id', 'origin_name', 'oss_object', 'file_class', 'folder', 'updated_at', 'updated_by'];
        return $this->dbHelper->table('upload_file')->where(['shop_id' => $shopId, 'folder' => $folder])
            ->fields($fields)->page($page, $pageSize)->select();
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
