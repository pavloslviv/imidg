<?php

class ShopCore
{
    static $orderStatuses = array(
        'new'=>'Новый',
        'processing'=>'Обрабатывается',
        'shipped'=>'Отправлен',
        'cancelled'=>'Отменен',
        'done'=>'Выполнен'
    );

    static $callbaсkStatuses = array(
        1 => 'Новый',
        2 => 'Выполнен',
        3 => 'Отменен'
    );

    static $paymentMethods = array(
        'bank'=>'Оплата через банк',
        'cash'=>'Наличными',
        'privat24'=>'Приват24',
        'liqpay'=>'LiqPay'
    );

    static $shipmentMethods = array(
        'pickup'=>'Самовывоз',
        'courier'=>'Курьером по Львову',
        'new_post'=>'Новая Почта',
        'post'=>'Укрпочта'
    );

    static $modifications = array(
        10=>28,
        11=>34,
        16=>46,
        12=>54,
        18=>54,
        19=>54,
        20=>54,
        21=>54,
        32=>57,
        45=>59,
        50=>61,
        58=>63,
        76=>67,
        88=>69,
        103=>71,
        113=>73,
        118=>75,
        122=>77
    );

    static $lockedOptions = array(
        32=>array(
            'day'=>'денний',
            'night'=>'вечірній'
        ),
        38=>array(
            'day'=>'денний',
            'night'=>'вечірній'
        ),
        49=>array(
            'day'=>'денний',
            'night'=>'вечірній'
        ),
        33=>array(
            'spring'=>'навесні',
            'summer'=>'влітку',
            'autumn'=>'восени',
            'winter'=>'взимку'
        ),
        39=>array(
            'spring'=>'навесні',
            'summer'=>'влітку',
            'autumn'=>'восени',
            'winter'=>'взимку'
        ),
        50=>array(
            'spring'=>'навесні',
            'summer'=>'влітку',
            'autumn'=>'восени',
            'winter'=>'взимку'
        ),
    );

    static $brandOptions = array(
        '10' => '40',
        '11' => '41',
        '12' => '42',
        '13' => '43',
        '16' => '51',
        '45' => '58'
    );

    public static function validateShipment($method,$data, $errors){
        $cleanData = array();
        $result = 'error';
        $errors_count_before_shipment_validation = count($errors);
        if(!ShopCore::$shipmentMethods[$method]){
            $errors[] =  array(
                'field'=>'method',
                'message'=>'Невідомий спосіб доставки.'
            );
        }
        switch($method){
            case 'pickup':
                $cleanData['office_id'] = (int)$data['office'];
                $addressList = json_decode(Core::getSettings('shop','map'),true);

                if ($data['office'] == '') {
                    $errors[] =  array(
                        'field'=>'office',
                        'message'=>'Вкажіть магазин.'
                    );
                }

                if (!$addressList['items'][$cleanData['office_id']]){
                    $cleanData['office_id']=0;
                }
                $office = $addressList['items'][$cleanData['office_id']];
                $cleanData['office']= $office['city'].', '.$office['address'].', Тел.: '.$office['phone'];
                break;
            case 'new_post':
                $city = trim($data['city']);
                $warehouse = trim($data['warehouse']);
                if($city==''){
                    $errors[] =  array(
                        'field'=>'city',
                        'message'=>'Вкажіть місто.'
                    );
                }
                if($warehouse==''){
                    $errors[] =  array(
                        'field'=>'warehouse',
                        'message'=>'Вкажіть номер відділення.'
                    );
                }
                $cleanData['city'] = $city;
                $cleanData['warehouse'] = $warehouse;
                break;
            case 'post':
                $address = trim($data['address']);
                if($address==''){
                    $errors[] =  array(
                        'field'=>'address',
                        'message'=>'Вкажіть вашу поштову адресу.'
                    );
                }
                $cleanData['address'] = $address;
                break;
            case 'courier':
                $address = trim($data['courier_address']);
                if($address==''){
                    $errors[] =  array(
                        'field'=>'courier_address',
                        'message'=>'Вкажіть адресу доставки.'
                    );
                }
                $cleanData['courier_address'] = $address;
                break;
        }

        if (count($errors) == $errors_count_before_shipment_validation) {
            $result = 'success';
        }
        $cleanData['method']=$method;
        return array('result'=>$result,'data'=>$cleanData,'errors' => $errors);
    }

    public static function validatePayment($method, $errors){
        if(ShopCore::$paymentMethods[$method]){
            return array('result'=> 'success',  'data'=>array('method'=>$method));
        } else {
            $errors[] =  array(
                'field'=>'payment',
                'message'=>'Невідомий метод оплати.'
            );
            return array('result'=> 'error',  'errors'=>$errors);
        }

    }

    public static function getDiscount($userId=null){

        if(!$userId && !$_SESSION['customer']){
            return 0;
        }
        $userId = $userId ? (int)$userId : (int)$_SESSION['customer']['id'];
        $db = Core::getDB();
        $discount = $db->selectRow('SELECT * FROM `sr_shop_discount` WHERE `customer_id`=?',$userId);
        if(!$discount['id']){
            return 0;
        }
        return (float)$discount['discount']/100;
    }
}