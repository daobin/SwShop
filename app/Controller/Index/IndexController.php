<?php
declare(strict_types=1);

namespace App\Controller\Index;

use App\Controller\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return '<h1>Hello Index::index</h1>';
    }
}
