<?php
declare(strict_types=1);

namespace App\Widget;

class IndexWidget extends Widget
{
    public function header($params = [])
    {
        $data = [
            'timestamp' => $params['timestamp'] ?? '?' . date('YmdH'),
            'customer_id' => $params['customer_id'] ?? 0
        ];
        return $this->render('header', $data);
    }

    public function footer($params = [])
    {
        $data = [
            'timestamp' => $params['timestamp'] ?? '?' . date('YmdH'),
        ];
        return $this->render('footer', $data);
    }
}
