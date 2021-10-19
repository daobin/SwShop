<?php
/**
 * User: dao bin
 * Date: 2021/10/19
 * Time: 18:02
 */
declare(strict_types=1);

class ShopController extends \App\Controller\Controller
{
    public function index(){
        return $this->render();
    }

    public function detail(){
        if ($this->request->isPost) {
            return $this->save();
        }

        return $this->render();
    }

    private function save(){
        return [];
    }

    public function delete(){

    }
}
