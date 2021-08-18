<?php
/**
 * 前台异步处理
 * User: dao bin
 */
declare(strict_types=1);

namespace App\Controller\Index;

use App\Biz\CustomerBiz;
use App\Controller\Controller;

class AjaxController extends Controller
{
    public function loginProcess()
    {
        $email = $this->post('email');
        $password = $this->post('password');
        $customer = (new CustomerBiz())->getCustomerByEmail($this->shopId, $email);
        if(empty($customer)){
            return ['status' => 'fail', 'msg' => 'Invalid email or password'];
        }
        if(!password_verify($password, $customer['password'])){
            return ['status' => 'fail', 'msg' => 'Invalid email or password'];
        }

        $this->session->renameKey($this->request->domain);
        $this->session->set('sp_customer_info', json_encode($customer));
        $this->session->remove('IDXlogin');

        return ['status' => 'success', 'url' => '/account.html'];
    }

    public function registerProcess()
    {
        $token = $this->post('hash_tk');
        $idempotentField = 'idempotent_register';
        if (empty($this->session->get($idempotentField))) {
            $this->session->set($idempotentField, $token);
        } else {
            return ['status' => 'fail'];
        }

        $time = time();
        $register = (new CustomerBiz())->register([
            'email' => $this->post('email'),
            'password' => $this->post('password'),
            'password2' => $this->post('password2'),
            'shop_id' => $this->shopId,
            'host_from' => $this->host,
            'device_from' => $this->device,
            'ip_number' => $this->ip,
            'ip_country_iso_code_2' => $this->ipCountryIsoCode2,
            'registered_at' => $time,
            'created_at' => $time,
            'created_by' => $this->operator,
            'updated_at' => $time,
            'updated_by' => $this->operator
        ]);

        $this->session->remove($idempotentField);
        if($register['status'] === 'success'){
            $this->session->renameKey($this->request->domain);
            $this->session->set('sp_customer_info', json_encode($register['customer_info']));
            $this->session->remove('IDXregister');
            $register['url'] = '/account.html';
        }

        return $register;
    }
}
