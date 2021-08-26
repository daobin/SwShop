<?php
/**
 * 店铺购物流程页
 * User: dao bin
 */
declare(strict_types=1);

namespace App\Controller\Index;

use App\Controller\Controller;
use App\Helper\SafeHelper;

class ShoppingController extends Controller
{
    public function cart()
    {
        $this->session->set('login_to', '/shopping/cart.html');

        $data = [
            'cart_tk' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('IDX', 'upCartProd')
        ];
        return $this->render($data);
    }

    public function confirm()
    {
        return $this->render();
    }

    public function payment()
    {

    }

    public function success()
    {
        return $this->render();
    }
}
