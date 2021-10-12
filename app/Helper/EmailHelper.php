<?php
/**
 * Email 邮件发送助手
 * User: dao bin
 * Date: 2021/10/12
 * Time: 10:10
 */
declare(strict_types=1);

namespace App\Helper;

use App\Biz\ConfigBiz;
use PHPMailer\PHPMailer\PHPMailer;

class EmailHelper
{
    private $shopId;
    private $langCode;
    private $mailCfgs;

    public function __construct(int $shopId, string $langCode)
    {
        $this->shopId = $shopId;
        $this->langCode = $langCode;

        $mailCfgs = (new ConfigBiz())->getConfigListByGroup($shopId, 'mail');
        $this->mailCfgs = !empty($mailCfgs) ? array_column($mailCfgs, 'config_value', 'config_key') : [];
    }

    public function sendMail(array $mailData): array
    {
        $template = $mailData['template'] ?? '';
        if (!in_array($template, ['welcome', 'forget_password', 'customer_service', 'order_success'])) {
            return ['status' => 'fail', 'msg' => 'Invalid mail'];
        }

        $template = ROOT_DIR . 'public/email/' . $template . '.html';
        if (!file_exists($template)) {
            return ['status' => 'fail', 'msg' => 'Invalid email template'];
        }

        if (empty($mailData['to_address']) || !filter_var($mailData['to_address'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'fail', 'msg' => 'Invalid email address'];
        }

        $content = file_get_contents($template);
        $content = str_replace(
            ['%CUSTOMER_NAME%'],
            ['Dao bin Lai'],
            $content);

        $mailObj = new PHPMailer(true);
        try {
            $mailObj->isSMTP();
            $mailObj->Host = $this->mailCfgs['SMTP_HOST'];
            $mailObj->Port = $this->mailCfgs['SMTP_PORT'];
            $mailObj->SMTPAuth = true;
            $mailObj->Username = $this->mailCfgs['SMTP_USERNAME'];
            $mailObj->Password = $this->mailCfgs['SMTP_PASSWORD'];
            $mailObj->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

            $mailObj->setFrom($this->mailCfgs['SMTP_USERNAME']);
            $mailObj->addAddress($mailData['to_address']);

            $mailObj->isHTML(true);
            $mailObj->Subject = 'Welcome';
            $mailObj->Body = $content;

            $mailObj->send();

        } catch (\Throwable $e) {
            return ['status' => 'fail', 'msg' => $e->getMessage()];
        }

        return ['status' => 'success'];
    }
}
