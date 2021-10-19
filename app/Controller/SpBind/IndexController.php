<?php
/**
 * User: dao bin
 * Date: 2021/10/19
 * Time: 18:02
 */
declare(strict_types=1);

class IndexController extends \App\Controller\Controller
{
    public function index(){
        return $this->render();
    }

    public function login(){

    }

    public function loginProcess(){

    }

    public function logout(){
        $this->session->clear();
        $this->response->redirect('/spbind/login.html');
    }
}
