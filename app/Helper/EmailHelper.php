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
use App\Biz\EmailBiz;
use PHPMailer\PHPMailer\PHPMailer;

class EmailHelper
{
    private $shopId;
    private $host;
    private $mailCfgs;

    public function __construct(int $shopId, string $host)
    {
        $this->shopId = $shopId;
        $this->host = trim($host);

        $mailCfgs = (new ConfigBiz())->getConfigListByGroup($shopId, 'mail');
        $this->mailCfgs = !empty($mailCfgs) ? array_column($mailCfgs, 'config_value', 'config_key') : [];
    }

    public function sendMail(array $mailData): array
    {
        $template = $mailData['template'] ?? '';
        $tplInfo = (new EmailBiz())->getEmailTemplateByTpl($this->shopId, $template);
        if (empty($tplInfo)) {
            return ['status' => 'fail', 'msg' => 'Invalid mail'];
        }

        $template = ROOT_DIR . 'public/email/' . $template . '.html';
        if (!file_exists($template)) {
            return ['status' => 'fail', 'msg' => 'Invalid email template'];
        }

        if (empty($mailData['to_address']) || !filter_var($mailData['to_address'], FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'fail', 'msg' => 'Invalid email address'];
        }

        $header = ROOT_DIR . 'public/email/header.html';
        $header = file_exists($header) ? file_get_contents($header) : '';
        $footer = ROOT_DIR . 'public/email/footer.html';
        $footer = file_exists($footer) ? file_get_contents($footer) : '';
        $content = $header . file_get_contents($template) . $footer;

        $customerName = $mailData['customer_name'] ?? 'Customer';
        $customerEmail = $mailData['customer_email'] ?? '';

        $year = date('Y');
        if ($year > 2021) {
            $year = '2021 - ' . $year;
        } else {
            $year = 2021;
        }

        $websiteName = $mailData['website_name'] ?? '';
        if ($websiteName === '') {
            $websiteName = (new ConfigBiz())->getConfigByKey($this->shopId, 'WEBSITE_NAME');
            $websiteName = $websiteName['config_value'] ?? '';
        }

        $homeLink = $mailData['home_link'] ?? 'http://' . $this->host;
        $homeLink = trim($homeLink, '/');
        $csLink = '/customer-service.html';
        $myOrderLink = '/order.html';
        $orderTrackingLink = '/order-tracking.html';
        if (filter_var($homeLink, FILTER_VALIDATE_URL)) {
            $csLink = $homeLink . $csLink;
            $myOrderLink = $homeLink . $myOrderLink;
            $orderTrackingLink = $homeLink . $orderTrackingLink;
        } else {
            $homeLink = 'javascript:void(0);';
            $orderTrackingLink = $myOrderLink = $csLink = $homeLink;
        }

        $content = str_replace(
            [
                '%CUSTOMER_NAME%', '%CUSTOMER_EMAIL%', '%YEAR%', '%WEBSITE_NAME%',
                '%HOME_LINK%', '%CS_LINK%', '%MY_ORDER_LINK%', '%ORDER_TRACKING_LINK%',
                '%ORDER_NUMBER%', '%ORDER_TOTAL%', '%ORDER_DATE%',
                '%SUBMISSION_TIME%', '%CUSTOMER_QUESTION%'
            ],
            [
                $customerName, $customerEmail, $year, $websiteName,
                $homeLink, $csLink, $myOrderLink, $orderTrackingLink,
                $mailData['order_number'] ?? '', $mailData['order_total'] ?? '', $mailData['order_date'] ?? '',
                $mailData['submission_time'] ?? '', nl2br($mailData['customer_question'] ?? '')
            ],
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
            $mailObj->Subject = empty($tplInfo['subject']) ? ucfirst(str_replace('_', ' ', $template)) : trim($tplInfo['subject']);
            if (basename($template) == 'customer_service.html') {
                $mailObj->Subject .= sprintf(' [%s] #%s', strtoupper($mailData['service_type'] ?? ''), strtoupper($mailData['order_number'] ?? ''));
            }
            print_r(basename($template) . PHP_EOL);
            print_r($mailObj->Subject . PHP_EOL);
            $mailObj->Body = $content;

            $mailObj->send();

        } catch (\Throwable $e) {
            return ['status' => 'fail', 'msg' => $e->getMessage()];
        }

        return ['status' => 'success'];
    }
}
