<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
class Customer extends Component
{
    public function __construct()
    {
        parent::__construct();
        include_once(ROOT . '/lib/VO/Customer.class.php');
    }

    public function run($action = null)
    {
        if(!$action) {
            $action = Core::$path[1];
        }
        if ($this->actions[$action]) $this->{$this->actions[$action]}();
        else $this->{$this->actions['default']}();
    }

    protected function registerActions()
    {
        $this->actions['default'] = 'profile';
        $this->actions['profile'] = 'profile';
        $this->actions['whoami'] = 'whoami';
        $this->actions['login'] = 'login';
        $this->actions['logout'] = 'logout';
        $this->actions['signup'] = 'register';
        $this->actions['update'] = 'update';
        $this->actions['change_discount'] = 'updateDiscount';
        $this->actions['change_password'] = 'updatePassword';
        $this->actions['get_order'] = 'getOrder';
        $this->actions['support'] = 'senSupportMail';
        $this->actions['recover_request'] = 'requestRecover';
        $this->actions['contact'] = 'sendContactForm';
        $this->actions['recover'] = 'showRecover';
        $this->actions['recover_pass'] = 'doRecover';
        $this->actions['subscribe'] = 'subscribe';
    }

    public function profile(){
        if(!$_SESSION['customer']){
            header('Location: '.HTTP_ROOT);
            exit();
        }
        $customer = new CustomerVO((int)$_SESSION['customer']['id']);
        $_SESSION['customer']=$customer->getAll();
        if(!$customer->id){
            header('Location: '.HTTP_ROOT);
            exit();
        }
        $discount = new DBObject('sr_shop_discount');
        $discount->fetch('`customer_id`='.(int)$customer->id);

        Core::$breadcrumbs['/profile']=Lang::$locale['customer_profile'];
        $smarty = Core::getSmarty();
        $smarty->assign('customer', $customer->getAll());
        if($discount->id){
            $smarty->assign('discount', $discount->getAll());
        }
        $smarty->assign('meta_title', Lang::$locale['customer_profile']);
        $smarty->assign('meta_descr', '');
        $smarty->assign('meta_keyw', '');
        $smarty->assign('orders', $this->getOrders());
        $smarty->assign('component', 'profile');
    }

    public function getOrders()
    {
        $customerId = (int)$_SESSION['customer']['id'];
        $orderList = new DBCollection('sr_shop_order', array('id', 'customer_id','date', 'status', 'total'));
        $orderList->fetch('customer_id='.$customerId,'date asc');
        return array_values($orderList->data);
    }

    public function getOrder($id=null)
    {
        $order = new DBObject('sr_shop_order',$id ? $id : (int)$_GET['id']);
        if(!$order->id){
            $this->sendJSON(array('result'=>'error','message'=>Lang::$locale['order_not_found']));
        }
        if($order->get('customer_id')!=(int)$_SESSION['customer']['id']){
            $this->sendJSON(array('result'=>'error','message'=>Lang::$locale['order_not_found']));
        }
        $result = $order->getAll();
        unset($result['customer_id']);
        $items = new DBCollection('sr_shop_order_item');
        $items->fetch('`order_id`='.(int)$order->id);
        $result['items']=array();
        foreach($items->data as $itemId=>$item){
            $product = new DBObject('sr_shop_product',$item['product_id']);
            if($product->id && $product->get('parent_id')!=0){
                $product = new DBObject('sr_shop_product',$product->get('parent_id'));
            }
            if($product->id){
                $item['product_id'] = $product->id;
                $item['sef'] = $product->get('sef');
                $item['image'] = $product->get('image');
            }
            array_push($result['items'],$item);
        }
        $this->sendJSON(array('result'=>'success','order'=>$result));
    }

    public function login()
    {
        if (!filter_var($_POST['login'], FILTER_VALIDATE_EMAIL)){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'login',
                    'message'=>Lang::$locale['enter_correct_email']
                )));
        }

        if ($_POST['pass']==''){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'pass',
                    'message'=>Lang::$locale['enter_password']
                )));
        }
        if ($_POST['login'] && $_POST['pass']) {
            $customer = new CustomerVO();
            $customer->fetch("`mail`='".Helpers::mysql_escape($_POST['login'])."'");
            if ($customer->get('pass') != md5($_POST['pass']) || !$customer->get('pass') || !$customer->id) {
                $this->sendJSON(array('result'=>'error','data'=>array(
                    'field'=>'pass',
                    'message'=>Lang::$locale['wrong_email_or_password']
                )));
            }
            else {
                $_SESSION['customer'] = $customer->getAll();
                $this->sendJSON(array('result'=>'success','data'=>$_SESSION['customer']));
            }
        }
        else {
            $this->sendJSON(array('result'=>'error','message'=>Lang::$locale['authorization_error']));
        }
    }

    public function logout()
    {
        unset($_SESSION['customer']);
        $this->sendJSON(array('result'=>'success','message'=>'Выход осуществлен.'));
    }

    public function whoami()
    {
        if($_SESSION['customer']){
            $customer = new CustomerVO((int)$_SESSION['customer']['id']);
            $_SESSION['customer']=$customer->getAll();
            $this->sendJSON(array('result'=>'success','data'=>$customer->getAll()));
        }
        else {
            $this->sendJSON(array('result'=>'error','message'=>'Пользователь не авторизован.'));
        }
    }

    public function register()
    {
        $db = Core::getDB();
        if ($_POST['name']==''){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'name',
                    'message'=>Lang::$locale['enter_your_name']
                )));
        }

        if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'mail',
                    'message'=>Lang::$locale['enter_correct_email']
                )));
        } else {
            $row = $db->selectRow('SELECT id FROM sr_customer WHERE `mail`=?',$_POST['mail']);
            if($row['id']){
                $this->sendJSON(array('result'=>'error',
                    'data'=>array(
                        'field'=>'mail',
                        'message'=>Lang::$locale['customer_with_this_mail_exist']
                    )));
            }

        }

        if ($_POST['phone']==''){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'phone',
                    'message'=>Lang::$locale['enter_contact_phone']
                )));
        }

        if (mb_strlen($_POST['pass'])<4 || mb_strlen($_POST['pass'])>16){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'pass',
                    'message'=>Lang::$locale['password_validation']
                )));
        }

        if ($_POST['pass']!=$_POST['pass-confirm']){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'pass-confirm',
                    'message'=>Lang::$locale['password_confirm_validation']
                )));
        }
        $discount = array();
        if($_POST['discount-code']){
            $discountCode = trim($_POST['discount-code']);
            $discount = $db->selectRow('SELECT * FROM `sr_shop_discount` WHERE `code`=?',$discountCode);
            if($discount['id'] && $discount['customer_id']!=0){
                $this->sendJSON(array('result'=>'error',
                    'data'=>array(
                        'field'=>'discount-code',
                        'message'=>Lang::$locale['duplicate_discount']
                    )));
                return;
            }

        }
        $customer = new CustomerVO();
        $customer->set(array(
            'name'=>htmlspecialchars($_POST['name']),
            'phone'=>htmlspecialchars($_POST['phone']),
            'mail'=>htmlspecialchars($_POST['mail']),
            'pass'=>md5($_POST['pass']),
            'subscribe' => $_POST['subscribe']=='1' ? 1 : 0
        ));
        $customer->save();

        $customer->fetch();

        $this->sendLetter('signup',$customer->get('mail'),'Успішна реєстрація на сайті Імідж',array(
            'mail'=>$customer->get('mail'),
            'link'=>HTTP_ROOT
        ));

        if($customer->id){
            if($discount['id']){
                $db->query('UPDATE `sr_shop_discount` SET `customer_id`=? WHERE `id`=?',$customer->id,$discount['id']);
            }
            $_SESSION['customer']=$customer->getAll();
            $this->sendJSON(array('result'=>'success','data'=>$customer->getAll()));
        }
        else {
            $this->sendJSON(array('result'=>'error','message'=>Lang::$locale['signup_error']));
        }

    }

    public function subscribe(){
        $db = Core::getDB();
        if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'mail',
                    'message'=>Lang::$locale['enter_correct_email']
                )));
            return;
        }

        $customer = $db->selectRow('SELECT * FROM sr_customer WHERE `mail`=?',$_POST['mail']);
        if($customer['id']){
            $db->query('UPDATE `sr_customer` SET `subscribe`=1 WHERE `id`=?',$customer['id']);
            $this->sendJSON(array('result'=>'success','message'=>Lang::$locale['successfully_subscribed']));
            return;
        }
        $customer = new CustomerVO();
        $customer->set(array(
            'name'=>htmlspecialchars($_POST['name']),
            'phone'=>'',
            'mail'=>$_POST['mail'],
            'pass'=>sha1($_POST['mail'].time().rand(0,10000)),
            'subscribe' => 1
        ));
        $customer->save();

        $customer->fetch();
        if($customer->id){
            $this->sendJSON(array('result'=>'success','message'=>Lang::$locale['successfully_subscribed']));
        } else {
            $this->sendJSON(array('result'=>'error','message'=>Lang::$locale['unknown_error']));
        }
    }

    public function update()
    {
        $this->checkSession();
        $db = Core::getDB();
        $allowedFields = array('name','mail','phone','subscribe');
        $data=array();
        foreach($allowedFields as $field){
            if(isset($_POST[$field])){
                $data[$field]=htmlspecialchars(trim($_POST[$field]));
            }
        }
        if(isset($data['subscribe'])){
            $data['subscribe'] = $data['subscribe']=='1' ? 1 : 0;
        }
        $customerId = (int)$_POST['id'];
        if (isset($data['name']) && $data['name']==''){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'name',
                    'message'=>Lang::$locale['enter_your_name']
                )));
        }

        if(isset($data['mail'])){
            if (!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)){
                $this->sendJSON(array('result'=>'error',
                    'data'=>array(
                        'field'=>'mail',
                        'message'=>Lang::$locale['enter_correct_email']
                    )));
            } else {
                $row = $db->selectRow('SELECT id FROM sr_customer WHERE `mail`=? and id<>?',$data['mail'],$customerId);
                if($row['id']){
                    $this->sendJSON(array('result'=>'error',
                        'data'=>array(
                            'field'=>'mail',
                            'message'=>Lang::$locale['customer_with_this_mail_exist']
                        )));
                }

            }
        }

        if (isset($data['phone'])){
            if($data['phone'] == ''){
                $this->sendJSON(array('result'=>'error',
                    'data'=>array(
                        'field'=>'phone',
                        'message'=>Lang::$locale['enter_contact_phone']
                    )));
            } elseif ( strlen($data['phone']) < 15){
                $this->sendJSON(array('result'=>'error',
                    'data'=>array(
                        'field'=>'phone',
                        'message'=>Lang::$locale['enter_length_phone']
                    )
                ));
            }
        }

        if (!count($data)) {
            $this->sendJSON(array('result'=>'error','message'=>Lang::$locale['nothing_to_update']));
        }
        if (!$customerId || $customerId!=$_SESSION['customer']['id']) {
            $this->sendJSON(array('result'=>'error','message'=>Lang::$locale['wrong_customer_id']));
        }
        $customer = new CustomerVO((int)$_SESSION['customer']['id']);
        $customer->set($data);
        $customer->save();
        $customer->fetch();
        if($customer->id){
            $_SESSION['customer']=$customer->getAll();
            $this->sendJSON(array('result'=>'success','data'=>$customer->getAll()));
        }
        else {
            $this->sendJSON(array('result'=>'error','message'=>'Произошла ошибка, мы постараемся устранить ее в ближайшее время.'));
        }
    }

    public function updateDiscount(){
        $this->checkSession();
        $code = trim($_POST['code']);

        $db = Core::getDB();
        $customer = $db->selectRow('SELECT `discount_change` FROM `sr_customer` WHERE `id`=?',(int)$_SESSION['customer']['id']);
        $changeCounter = (int)$customer['discount_change'];
//        if(!$changeCounter){
//            $this->sendJSON(array('result'=>'error',
//                'data'=>array(
//                    'field'=>'discount',
//                    'message'=>Lang::$locale['discount_change_limit_error'],
//                    'counter'=>$changeCounter
//                )));
//            return;
//        }
        $currentDiscount = $db->selectRow('SELECT * FROM `sr_shop_discount` WHERE `customer_id`=?',(int)$_SESSION['customer']['id']);
        $newDiscount = $db->selectRow('SELECT * FROM `sr_shop_discount` WHERE `code`=?',$code);
        if($currentDiscount['id'] && $currentDiscount['id']==$newDiscount['id']){
            $this->sendJSON(array('result'=>'success','data'=>$currentDiscount));
            return;
        }
        $db->query('UPDATE `sr_customer` SET `discount_change`=? WHERE `id`=?',$changeCounter,(int)$_SESSION['customer']['id']);
        if(!$newDiscount['id']){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'discount',
                    'message'=>Lang::$locale['discount_card_not_found'],
                    'counter'=>$changeCounter
                )));
            return;
        }

        if($newDiscount['customer_id']!=0 && $newDiscount['customer_id']!=(int)$_SESSION['customer']['id']){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'discount-code',
                    'message'=>Lang::$locale['duplicate_discount'],
                    'counter'=>$changeCounter
                )));
            return;
        }

        if($currentDiscount['id']){
            $db->query('UPDATE `sr_shop_discount` SET `customer_id`=0 WHERE `id`=?',(int)$currentDiscount['id']);
        }
        $db->query('UPDATE `sr_shop_discount` SET `customer_id`=? WHERE `id`=?',(int)$_SESSION['customer']['id'],(int)$newDiscount['id']);
        $newDiscount['customer_id']=(int)$_SESSION['customer']['id'];
        $newDiscount['counter']=$changeCounter;
        $this->sendJSON(array('result'=>'success','data'=>$newDiscount));
    }

    public function updatePassword()
    {
        $this->checkSession();
        $customer = new DBObject('sr_customer',(int)$_SESSION['customer']['id']);
        if(md5($_POST['old-password'])!=$customer->get('pass')){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'old-password',
                    'message'=>Lang::$locale['enter_correct_current_password']
                )));
        }
        if (mb_strlen($_POST['pass'])<4 || mb_strlen($_POST['pass'])>16){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'pass',
                    'message'=>Lang::$locale['password_validation']
                )));
        }

        if ($_POST['pass']!=$_POST['pass-confirm']){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'pass-confirm',
                    'message'=>Lang::$locale['password_confirm_validation']
                )));
        }
        $customer->set(array(
            'pass'=>md5($_POST['pass']),
        ));
        $customer->save();
        $this->sendJSON(array('result'=>'success'));
    }

    public function senSupportMail()
    {
        $this->checkSession();
        if ($_POST['topic']==''){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'topic',
                    'message'=>Lang::$locale['enter_message_topic']
                )));
        }

        if ($_POST['text']==''){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'text',
                    'message'=>Lang::$locale['enter_message_text']
                )));
        }

        $this->sendLetter(
            'support',
            Core::getSettings('main','admin_mail'),
            'Запит  до служби підтримки',
            array(
                'name'=>$_SESSION['customer']['name'],
                'mail'=>$_SESSION['customer']['mail'],
                'phone'=>$_SESSION['customer']['phone'],
                'topic'=>$_POST['topic'],
                'text'=>$_POST['text'],
            )
        );
        $this->sendJSON(array('result'=>'success'));
    }

    public function requestRecover(){
        $mail = trim($_POST['mail']);
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'mail',
                    'message'=>Lang::$locale['enter_correct_email']
                )));
        }
        $db = Core::getDB();
        $row = $db->selectRow('SELECT * FROM sr_customer WHERE `mail`=?',$mail);
        if(!(int)$row['id']){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'mail',
                    'message'=>Lang::$locale['customer_wiht_email_not_found']
                )));
        }
        $recoveryCode = sha1($row['id'].$row['mail'].$row['phone'].time().rand(0,10000));
        $recoveryExpire = time()+(24*3600);
        $db->query('UPDATE `sr_customer` SET `recover_code`=?, `recover_expire`=? WHERE `id`=?',$recoveryCode,$recoveryExpire,$row['id']);
        $this->sendLetter('recover',$row['mail'],'Відновлення паролю',array(
            'link'=>HTTP_ROOT.'/customer/recover?code='.$recoveryCode
        ));
        $this->sendJSON(array('result'=>'success',
            'data'=>array(
                'message'=>Lang::$locale['pass_recovery_success']
            )));
    }

    public function sendContactForm(){
        $data = array(
            'mail'=>trim($_POST['mail']),
            'phone'=>trim($_POST['phone']),
            'name'=>trim($_POST['name']),
            'message'=>trim($_POST['message']),
            'mode'=>trim($_POST['mode'])
        );
        if (!$data['name']){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'name',
                    'message'=>Lang::$locale['enter_your_name']
                )));
        }
        $validMail = filter_var($data['mail'], FILTER_VALIDATE_EMAIL);
        $validPhone = $_POST['phone']!='';

        if (!$validPhone && $data['mode']=='callback'){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'phone',
                    'message'=>Lang::$locale['enter_contact_phone']
                )));
        } else if(!$validMail && !$validPhone) {
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'phone',
                    'message'=>Lang::$locale['enter_phone_or_mail']
                )));
        }

        if (!$data['message']){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'message',
                    'message'=>Lang::$locale['enter_message_text']
                )));
        }
        $this->sendLetter('contact',Core::getSettings('main','admin_mail'),"Зворотній зв'язок imidg.com.ua",$data,($validMail ? $data['mail'] : null),($validMail ? $data['name'] : null));
        $this->sendJSON(array('result'=>'success',
            'data'=>array(
                'message'=>Lang::$locale['message_send_success']
            )));
    }

    public function showRecover(){
        $code = trim($_GET['code']);
        $row = false;
        $error = false;
        if (!$code){
            $error = true;
        } else {
            $db = Core::getDB();
            $row = $db->selectRow('SELECT * FROM sr_customer WHERE `recover_code`=?',$code);
            if(!(int)$row['id'] || time()>(int)$row['recover_expire']){
                $error = true;
            }
        }
        $smarty = Core::getSmarty();
        $smarty->assign('code',$row ? $row['recover_code'] : null);
        $smarty->assign('error',$error);
        $smarty->assign('component','recover_pass');


    }

    public function doRecover(){
        $code = trim($_POST['code']);
        if (!$code){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'message'=>Lang::$locale['recovery_code_error']
                )));
            return;
        }
        $db = Core::getDB();
        $row = $db->selectRow('SELECT * FROM sr_customer WHERE `recover_code`=?',$code);
        if(!(int)$row['id'] || time()>(int)$row['recover_expire']){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'message'=>Lang::$locale['recovery_code_error']
                )));
            return;
        }
        if($_POST['mail']!=$row['mail']){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'mail',
                    'message'=>Lang::$locale['wrong_mail']
                )));
            return;
        }
        $pass = $_POST['pass'];
        if (mb_strlen($pass)<4 || mb_strlen($pass)>16){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'pass',
                    'message'=>Lang::$locale['password_validation']
                )));
            return;
        }

        if ($pass!=$_POST['pass-confirm']){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'pass-confirm',
                    'message'=>Lang::$locale['password_confirm_validation']
                )));
            return;
        }
        $db->query('UPDATE `sr_customer` SET `pass`=?, `recover_expire`=0 WHERE `id`=?',md5($pass),$row['id']);
        $this->sendJSON(array('result'=>'success',
            'data'=>array(
                'message'=>Lang::$locale['password_recovery_done']
            )));
    }


    public function checkSession(){
        if (!$_SESSION['customer']) {
            $this->sendJSON(array('result'=>'error','message'=>'Вы не авторизованы.'));
        }
    }

}
