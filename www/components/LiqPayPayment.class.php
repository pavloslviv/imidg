<?php
/**
 * Company Otakoyi.com
 * Author: wg
 * Date: 12.01.15 18:05
 */

include_once(ROOT . '/lib/LiqPay.class.php');

/**
 * Class LiqPay
 * @name LiqPay
 * @author wmgodyak mailto:wmgodyak@gmail.com
 * @version 1.0
 * @copyright &copy; 2014 Otakoyi.com
 * @package controllers\modules\payment
 */
class LiqPayPayment extends Component
{

    protected $data;

    public function __construct($data = null)
    {
        parent::__construct();
        $this->data = $data;
    }

    protected function registerActions()
    {
        $this->actions['default'] = 'callback';
        $this->actions['checkout'] = 'checkout';
        $this->actions['callback'] = 'callback';
        //$this->actions['privat'] = 'result';
    }

    public function run($action = null)
    {
        if (!$action) {
            $action = Core::$path[1];
        }
        $currentAction = $this->actions[$action];
        if ($currentAction) {
            $this->$currentAction();
        } else {
            $default = $this->actions['default'];
            var_dump($default);
            die();
            //$this->$default();
        }
    }


    /**
     * @return string
     */
    public function checkout()
    {
        // налаштування платіжної системи

        $liqpay = new LiqPay(Core::getSettings('main', 'public_key'), Core::getSettings('main', 'private_key'));
        return $liqpay->cnb_form(
            array(
                'version' => '3',
                'amount' => $this->data['total'],
                'currency' => 'UAH',
                'description' => 'Платіж за замовлення',
                'order_id' => $this->data['id'],
                'server_url' => HTTP_ROOT . '/LiqPayPayment/callback',
                'result_url' => HTTP_ROOT . '/cart/liqPaySuccess',
//                'sandbox' => 1,
                'language' => Lang::$current
            )
        );
    }

    public function callback()
    {
        if (!$_POST) {
            $this->log('ERROR: Empty POST');
            echo HTTP_ROOT . 'LiqPayPayment/callback';
            die("ERROR: Empty POST");
        }
//        $_POST['signature'] = '7rb2gC2m3z45f+zNAz9zoQ+BM5c=';
//        $_POST['data'] = 'eyJ2ZXJzaW9uIjozLCJwdWJsaWNfa2V5IjoiaTI3MTc0MDAzNzQ5IiwiYW1vdW50IjoiMzUwLjAwIiwiY3VycmVuY3kiOiJVQUgiLCJkZXNjcmlwdGlvbiI6IlBheW1lbnQgb2YgYXBhcnRtZW50IEQxNTA1MDEtODMtMzUyLTE1MDYxMSIsInR5cGUiOiJidXkiLCJvcmRlcl9pZCI6IkQxNTA1MDEtODMtMzUyLTE1MDYxMSIsImxpcXBheV9vcmRlcl9pZCI6IjMwMzI3NXUxNDMwNDgxOTc4MTI3NTI2Iiwic3RhdHVzIjoic2FuZGJveCIsImVycl9jb2RlIjpudWxsLCJ0cmFuc2FjdGlvbl9pZCI6NTY0MDk3MDQsInNlbmRlcl9waG9uZSI6IjM4MDY3NjczNjI0MiIsInNlbmRlcl9jb21taXNzaW9uIjowLCJyZWNlaXZlcl9jb21taXNzaW9uIjo5LjYzLCJhZ2VudF9jb21taXNzaW9uIjowfQ==';

// log callback data
        $this->log('>>>  log callback data');
        $this->log(var_export($_POST, 1));

        $callbackParams = $_POST;

        $data = base64_decode($callbackParams['data']);
        $data = json_decode($data, 1);
        $sign = base64_encode(sha1(
            Core::getSettings('main', 'private_key') .
            $callbackParams['data'] .
            Core::getSettings('main', 'private_key'), 1));

        $this->log('verify signature begin');
// verify signature
        if ($callbackParams['signature'] !== $sign) {
            // answer with fail response
            $this->log("ERROR: Invalid signature");
        } else {
            // log success
            $this->log('Callback signature OK');

            $this->log('log data');
            $this->log(var_export($data, 1));

            // do processing stuff
            switch ($data['status']) {
                case 'sandbox':
                    // ставлю мітку про оплату
                    $order = new DBObject('sr_shop_order', $data['order_id']);
                    $order->set('payment_data', json_encode($data));

                    $order->save();
                    //$o->confirmPayment($data['order_id']);

                    //$notify = new Notification($data['order_id']);

                    $this->log('повідомлення замовнику');
                    $this->sendLetter(
                        'payment_success',
                        Core::getSettings('main', 'admin_mail'),
                        'Оплата за замовлення №' . $data['order_id'],
                        array(
                            'payment' => $data
                        )
                    );

                case 'success':
                    // ставлю мітку прооплату
                    $order = new DBObject('sr_shop_order', $data['order_id']);
                    $order->set('payment_data', json_encode($data));

                    $order->save();
                    //$o->confirmPayment($data['order_id']);

                    //$notify = new Notification($data['order_id']);

                    $this->log('повідомлення замовнику');
                    $this->sendLetter(
                        'payment_success',
                        Core::getSettings('main', 'admin_mail'),
                        'Оплата за замовлення №' . $data['order_id'],
                        array(
                            'payment' => $data
                        )
                    );

                    $this->log("Order {$data['order_id']} processed as successfull sale");
                    break;
                default:
                    $err_msg = "При оплате заказа  {$data['order_id']}  получен статус {$data['status']}.

                            Возможные значения:
                            success - успешный платеж
                            failure - неуспешный платеж
                            wait_secure - платеж на проверке
                            wait_accept - Деньги с клиента списаны, но магазин еще не прошел проверку
                            wait_lc - Аккредитив. Деньги с клиента списаны, ожидается подтверждение доставки товара
                            processing - Платеж обрабатывается
                            sandbox - тестовый платеж
                            subscribed - Подписка успешно оформлена
                            unsubscribed - Подписка успешно деактивирована
                            reversed - Возврат клиенту после списания
                            cash_wait - Ожидание оплаты счета клиентом в терминале

                     Подробнее о статусах: https://www.liqpay.com/ru/doc#callback ";
                    $this->log($err_msg);
                    //mail('info@mushroom.com.ua', 'LiqPay Callback Error', $err_msg);
                    die("ERROR: Invalid callback data");
            }

            // answer with success response
            $this->log('OK');
            exit("OK");
        }

    }

    private function log($msg)
    {
        $msg = strftime('%Y.%m.%d %H:%M:%S> ') . $msg . "\n";
        return file_put_contents(ROOT . "/payment_logfile.txt", $msg, FILE_APPEND);

    }
}
