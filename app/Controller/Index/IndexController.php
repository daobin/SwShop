<?php
/**
 * 店铺首页
 * User: dao bin
 */
declare(strict_types=1);

namespace App\Controller\Index;

use App\Controller\Controller;
use App\Helper\SafeHelper;

class IndexController extends Controller
{
    public function index()
    {
        return $this->render();
    }

    public function login()
    {
        $safeHelper = new SafeHelper($this->request, $this->response);

        $data = [
            'register_tk' => $safeHelper->buildCsrfToken('IDX', 'register'),
            'login_tk' => $safeHelper->buildCsrfToken('IDX', 'login'),
        ];
        return $this->render($data);
    }

    public function pageNotFound()
    {
        $this->response->status(404);
        return $this->render();
    }
}
