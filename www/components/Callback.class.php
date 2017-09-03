<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * $cart = array(
 * items=>
 * );
 */
class Callback extends Component
{
    public $status = false;
    public $errors = array();

    protected function registerActions()
    {
        $this->actions['save'] = 'save';
        $this->actions['default'] = 'save';
        $this->actions['confirm'] = 'confirmOrder';
    }

    public function run($action = null)
    {
        if(!$action) {
            $action = Core::$path[1];
        }
        if ($this->actions[$action]) $this->{$this->actions[$action]}();
        else $this->{$this->actions['default']}();
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!$this->checkForm()) {
                $this->sendJSON(array(
                   'status' => $this->status,
                   'errors' => $this->errors
                ));
                return;
            }

            $callback = new DBObject('sr_shop_callback');
            $callback->set(array(
                'customer_name' => htmlspecialchars(trim($_POST['name'])),
                'customer_phone' => htmlspecialchars(trim($_POST['phone'])),
                'status'=> 1
            ));

            $callback->save();
            if(!$callback->id){

            } else {
                $callBackData = array(
                    'id'=>$callback->id,
                    'phone' => $_POST['phone'],
                    'name' => $_POST['name']
                );
                $this->status = true;
                $this->sendLetter($callBackData);
                $this->sendSms($callBackData);
                $this->sendJSON(array(
                    'status' => $this->status,
                    'errors' => $this->errors
                ));
                return;
            }

            return false;
        }

    }

    public function checkForm()
    {
        $name = trim($_POST['name']);
        if (empty($name)) {
            $this->errors['name'] = Lang::$locale['enter_your_name'];
        }

        if (strlen(trim($_POST['phone'])) < 10) {
            $this->errors['phone'] = Lang::$locale['enter_contact_phone'];
        }

        if (!empty($this->errors)) {
            return false;
        }
        return true;
    }

    private function sendSms($callBackData)
    {

        include_once(ROOT.'/lib/smsclient.class.php');
        //init class with your login/password
        $sms = new SMSclient('380674439777', '30052002');
        $id = $sms->sendSMS('Imidg',Core::getSettings('main','admin_phone'), 'Відівідувач '. $callBackData['phone'] .' бажає, щоб йому перетелефонували №'.$callBackData['id']);
        //if no ID - then message is not sent and you should check errors
        if(!$id){
            Core::log('Error sending SMS: '.implode('/',$sms->getErrors()));
        } else {
            Core::log('SMS sent successfully: '. $callBackData['phone'] .','.var_export($id,true));
        }
//        $sms->sendSMS('Imidg','380677662069', 'Нове замовлення на сайті imidg.com.ua №'.$orderData['id'].'. Сума '.$orderData['total'].' грн.');
    }

    public function sendLetter($callBackData)
    {

        $smarty = Core::getSmarty();
        $smarty->assign('callbackId', $callBackData['id']);
        $smarty->assign('callbackName', $callBackData['name']);
        $smarty->assign('callbackPhone', $callBackData['phone']);
        $smarty->assign('meta_title', Lang::$locale['callback_created']);
        $smarty->assign('component', 'callback_confirm');
        parent::sendLetter(
            'callback_admin',
            Core::getSettings('main','admin_mail'),
            'Відівідувач бажає, щоб йому перетелефонували',
            array(
                'id' => $callBackData['id'],
                'name' => $callBackData['name'],
                'phone' => $callBackData['phone']
            )
        );
    }


}
