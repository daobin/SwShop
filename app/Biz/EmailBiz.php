<?php
/**
 * 邮件业务
 * User: dao bin
 * Date: 2021/9/26
 * Time: 17:08
 */
declare(strict_types=1);

namespace App\Biz;

use App\Helper\DbHelper;

class EmailBiz
{
    private $dbHelper;

    public function __construct()
    {
        $this->dbHelper = new DbHelper();
    }

    public function getEmailTemplateList(int $shopId): array
    {
        if ($shopId <= 0) {
            return [];
        }

        return $this->dbHelper->table('email_tpl')->where(['shop_id' => $shopId])
            ->fields(['subject', 'template', 'updated_at', 'updated_by'])
            ->select();
    }

    public function getEmailTemplateByTpl(int $shopId, string $tpl): array
    {
        if ($shopId <= 0 || empty($tpl)) {
            return [];
        }

        return $this->dbHelper->table('email_tpl')->where(['shop_id' => $shopId, 'template' => $tpl])
            ->fields(['subject', 'template', 'banner_images'])->find();
    }

    public function updateEmailTemplate(int $shopId, string $tpl, string $subject, array $bannerImages, string $operator): int
    {
        $subject = trim($subject);
        $operator = trim($operator);
        if ($shopId <= 0 || empty($subject) || empty($tpl)) {
            return 0;
        }

        return $this->dbHelper->table('email_tpl')
            ->where(['shop_id' => $shopId, 'template' => $tpl])->update(
                [
                    'subject' => $subject,
                    'banner_images' => json_encode($bannerImages),
                    'updated_at' => time(),
                    'updated_by' => $operator
                ]);
    }
}
