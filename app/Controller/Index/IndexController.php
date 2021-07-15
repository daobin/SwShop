<?php
/**
 * 店铺首页
 * User: dao bin
 */
declare(strict_types=1);

namespace App\Controller\Index;

use App\Controller\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return '<h1>Hello Index::index</h1>';
    }

    public function login(){
        return 'Please Login';
    }

    public function pageNotFound(){
        return 'Page Not Found';
    }
}
