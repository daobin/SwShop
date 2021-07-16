<?php
/**
 * 异步交互控制
 * User: dao bin
 * Date: 2021/7/16
 * Time: 14:05
 */
declare(strict_types=1);

namespace App\Controller\SpAdmin;

use App\Controller\Controller;

class AjaxController extends Controller
{
    public function login(){

        return ['status' => 'success'];
    }
}
