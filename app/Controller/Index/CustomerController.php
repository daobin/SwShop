<?php
/**
 * 用户页面
 * User: dao bin
 * Date: 2021/8/17
 * Time: 17:56
 */
declare(strict_types=1);

namespace App\Controller\Index;

use App\Biz\AddressBiz;
use App\Biz\CustomerBiz;
use App\Controller\Controller;
use App\Helper\LanguageHelper;
use App\Helper\SafeHelper;

class CustomerController extends Controller
{
    public function index()
    {
        if ($this->request->isPost) {
            $firstName = $this->post('first_name');
            $lastName = $this->post('last_name');
            if (empty($firstName) || empty($lastName)) {
                return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_name', $this->langCode)];
            }

            $updated = (new CustomerBiz())->updateName($this->shopId, $this->customerId, $firstName, $lastName, $this->operator);
            if ($updated) {
                $customerInfo = $this->session->get('sp_customer_info');
                if (empty($customerInfo)) {
                    return ['status' => 'fail', 'url' => '/logout.html'];
                }

                $customerInfo = json_decode($customerInfo, true);
                $customerInfo['first_name'] = $firstName;
                $customerInfo['last_name'] = $lastName;
                $this->session->set('sp_customer_info', json_encode($customerInfo));

                return ['status' => 'success', 'msg' => LanguageHelper::get('update_completed', $this->langCode)];
            }

            return ['status' => 'fail', 'msg' => LanguageHelper::get('update_failed', $this->langCode)];
        }

        $customerInfo = (new CustomerBiz())->getCustomerById($this->shopId, $this->customerId);
        if (empty($customerInfo)) {
            $this->response->redirect('/logout.html');
        }

        $data = [
            'customer_info' => $customerInfo,
            'hash_tk' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('IDX', 'profile'),
        ];
        return $this->render($data);
    }

    public function password()
    {
        if ($this->request->isPost) {
            $currPwd = $this->post('curr_pwd');
            $newPwd = $this->post('new_pwd');
            $newPwd2 = $this->post('new_pwd2');
            if (empty($currPwd) || empty($newPwd) || $newPwd != $newPwd2) {
                return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_password', $this->langCode)];
            }

            $customerBiz = new CustomerBiz();
            $customerInfo = $customerBiz->getCustomerById($this->shopId, $this->customerId);
            if (empty($customerInfo)) {
                return ['status' => 'fail', 'url' => '/logout.html'];
            }
            if (!password_verify($currPwd, $customerInfo['password']) || password_verify($newPwd, $customerInfo['password'])) {
                return ['status' => 'fail', 'msg' => LanguageHelper::get('invalid_password', $this->langCode)];
            }

            $newPwd = password_hash($newPwd, PASSWORD_DEFAULT);
            $updated = $customerBiz->updatePassword($this->shopId, $this->customerId, $newPwd, $this->operator);
            if ($updated) {
                return [
                    'status' => 'success',
                    'msg' => LanguageHelper::get('update_completed', $this->langCode),
                    'url' => '/logout.html'
                ];
            }

            return ['status' => 'fail', 'msg' => LanguageHelper::get('update_failed', $this->langCode)];
        }

        $data = [
            'hash_tk' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('IDX', 'password'),
        ];
        return $this->render($data);
    }

    public function address()
    {
        $customerInfo = (new CustomerBiz())->getCustomerById($this->shopId, $this->customerId);

        $data = [
            'default_address_id' => $customerInfo['shipping_address_id'] ?? 0,
            'address_list' => (new AddressBiz())->getAddressListByCustomerId($this->shopId, $this->customerId),
            'hash_tk' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('IDX', 'del.address'),
        ];
        return $this->render($data);
    }

    public function addressDetail()
    {
        if ($this->request->isPost) {
            return $this->saveAddress();
        }

        $addrBiz = new AddressBiz();

        $addrId = (int)$this->get('addr_id', 0);
        $addrInfo = $addrBiz->getAddressById($this->shopId, $this->customerId, $addrId);
        $countryList = $addrBiz->getCountryList($this->shopId, 1, 1000);
        $customerInfo = (new CustomerBiz())->getCustomerById($this->shopId, $this->customerId);

        $data = [
            'addr_info' => $addrInfo,
            'country_list' => $countryList,
            'is_default' => $customerInfo['shipping_address_id'] == $addrId,
            'hash_tk' => (new SafeHelper($this->request, $this->response))->buildCsrfToken('IDX', 'address'),
        ];
        return $this->render($data);
    }

    private function saveAddress()
    {
        $token = $this->post('hash_tk');
        $idempotentField = 'idempotent_save_address';
        if (empty($this->session->get($idempotentField))) {
            $this->session->set($idempotentField, $token);
        } else {
            return ['status' => 'fail'];
        }

        $addrBiz = new AddressBiz();
        $addressList = $addrBiz->getAddressListByCustomerId($this->shopId, $this->customerId);
        if (count($addressList) >= 10) {
            $this->session->remove($idempotentField);
            return ['status' => 'fail', 'msg' => LanguageHelper::get('max_10_shopping_address', $this->langCode)];
        }

        $addrId = (int)$this->get('addr_id', 0);
        $firstName = $this->post('first_name');
        $lastName = $this->post('last_name');
        $address = $this->post('address');
        $address2 = $this->post('address2');
        $city = $this->post('city');
        $countryId = (int)$this->post('country_id', 0);
        $zoneId = (int)$this->post('state_id', 0);
        $zoneName = $this->post('state');
        $postCode = $this->post('postcode');
        $phone = $this->post('phone');

        $emptyTip = [];
        if (empty($firstName) || empty($lastName)) {
            $emptyTip[] = LanguageHelper::get('invalid_name', $this->langCode);
        }
        if (empty($address)) {
            $emptyTip[] = LanguageHelper::get('invalid_address', $this->langCode);
        }
        if (empty($city)) {
            $emptyTip[] = LanguageHelper::get('invalid_city', $this->langCode);
        }
        if (empty($countryId)) {
            $emptyTip[] = LanguageHelper::get('invalid_country', $this->langCode);
        }
        if (empty($zoneId) && empty($zoneName)) {
            $emptyTip[] = LanguageHelper::get('invalid_zone', $this->langCode);
        }
        if (empty($postCode)) {
            $emptyTip[] = LanguageHelper::get('invalid_postcode', $this->langCode);
        }
        if (empty($phone)) {
            $emptyTip[] = LanguageHelper::get('invalid_phone_number', $this->langCode);
        }
        if (!empty($emptyTip)) {
            $this->session->remove($idempotentField);
            return ['status' => 'fail', 'msg' => '* ' . implode('<br/>* ', $emptyTip)];
        }

        $countryInfo = $addrBiz->getCountryById($this->shopId, $countryId);
        if (empty($countryInfo)) {
            $this->session->remove($idempotentField);
            return ['status' => 'fail', 'msg' => LanguageHelper::get('select_valid_country', $this->langCode)];
        }
        if ($zoneId > 0) {
            $zoneInfo = $addrBiz->getZoneById($this->shopId, $zoneId);
            if (empty($zoneInfo)) {
                $this->session->remove($idempotentField);
                return ['status' => 'fail', 'msg' => LanguageHelper::get('select_valid_zone', $this->langCode)];
            }
            $zoneName = $zoneInfo['zone_name'];
        }

        $time = time();
        $address = [
            'customer_address_id' => $addrId,
            'address_type' => 'shipping',
            'first_name' => $firstName,
            'last_name' => $lastName,
            'street_address' => $address,
            'street_address_sub' => $address2,
            'postcode' => $postCode,
            'city' => $city,
            'zone_id' => $zoneId,
            'zone_name' => $zoneName,
            'country_id' => $countryId,
            'country_name' => $countryInfo['country_name'] ?? '',
            'telephone' => $phone,
            'set_default' => $this->post('set_default', 0),
            'created_at' => $time,
            'created_by' => $this->operator,
            'updated_at' => $time,
            'updated_by' => $this->operator
        ];

        if ($addrBiz->saveAddress($this->shopId, $this->customerId, $address) > 0) {
            $this->session->remove($idempotentField);
            return [
                'status' => 'success',
                'url' => '/address.html',
                'msg' => LanguageHelper::get('save_successfully', $this->langCode)
            ];
        }

        $this->session->remove($idempotentField);
        return ['status' => 'fail', 'msg' => LanguageHelper::get('save_failed', $this->langCode)];
    }

    public function order()
    {
        return $this->render();
    }

    public function orderDetail()
    {
        $orderId = $this->get('order_id', 0);
        return $this->render();
    }
}
