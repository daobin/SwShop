<?php
declare(strict_types=1);

namespace App\Widget;

class IndexWidget extends Widget
{
    public function header()
    {
        $data = ['title' => 'Product 类目'];
        return $this->render('header', $data);
    }

    public function footer()
    {
        return $this->render('footer');
    }
}
