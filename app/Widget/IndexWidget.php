<?php
declare(strict_types=1);

namespace App\Widget;

class IndexWidget extends Widget
{
    public function header($params = [])
    {
        $data = [
            'timestamp' => $params['timestamp'] ?? '?' . date('YmdH'),
        ];
        return $this->render('header', $data);
    }

    public function footer($params = [])
    {
        return $this->render('footer');
    }
}
