<?php
declare(strict_types=1);

namespace App\Widget;

class SpAdminWidget extends Widget
{
    public function header()
    {
        return $this->render('header');
    }

    public function footer()
    {
        return $this->render('footer');
    }
}
