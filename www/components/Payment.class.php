<?php
include_once(ROOT . '/lib/Mailer.class.php');
class Payment extends Component
{
    var $expertId;
    private $pass = 'lvgst0s4FQBdE4gNw3854s05qm93PM15';
    private $merchantID= '101587';

    protected function registerActions()
    {
        $this->actions['default'] = 'pay';
        $this->actions['pay'] = 'pay';
        $this->actions['success'] = 'success';
        $this->actions['privat'] = 'result';
    }

    public function run($action = null)
    {
        if(!$action) {
            $action = Core::$path[1];
        }
        if ($this->actions[$action]) $this->{$this->actions[$action]}();
        else $this->{$this->actions['default']}();
    }

    private function pay()
    {
        $uniqid = trim(Core::$path[2]);
        /*if(!$uniqid){
            header('Location: /');
            exit();
        }*/
        $order = new DBObject('sr_shop_order');
        $order->fetch("`uniquid`='".Helpers::mysql_escape($uniqid)."'");
        $smarty = Core::getSmarty();
        //if($order->id){
            $smarty->assign('order', $order->getAll());
            $smarty->assign('form', $this->generateForm(array(
                'id'=>$order->id,
                'order_id'=>$order->get('uniquid'),
                'customer'=>$order->get('customer_name'),
                'amount'=>(float)$order->get('total')
            )));
       // }
        Core::$breadcrumbs['/articles']=Lang::$locale['payment'];

        $smarty->assign('meta_title', Lang::$locale['payment']);
        $smarty->assign('component', 'payment');

    }

    private function success()
    {
        $uniqid = trim(Core::$path[2]);
        if(!$uniqid){
            header('Location: /');
            exit();
        }
        $d = $this->parseResponse();
        $order = $d['order'];
        $data = $d['data'];

        $smarty = Core::getSmarty();
        if($order && $order->id){
            $smarty->assign('order', $order->getAll());
            $smarty->assign('data', $data);
        }

        Core::$breadcrumbs['/articles']='Оплата';

        $smarty->assign('meta_title', Lang::$locale['payment']);
        $smarty->assign('component', 'payment_success');

    }

    public function result()
    {
        $d = $this->parseResponse();
        $order = $d['order'];
        $data = $d['data'];

        $paymentData = json_decode($order->get('payment_data'),true);
        if(!$paymentData['payments']){
            $paymentData['payments']=array();
        }
        array_push($paymentData['payments'],$data);
        $order->set('payment_data',json_encode($paymentData));
        $order->save();
        echo 'OK';
        exit();
    }

    public function parseResponse(){
        $rawData = array();
        parse_str($_POST['payment'],$rawData);
        $data = array(
            'amt'=>(float)$rawData['amt'], //amount
            'ccy'=>$rawData['ccy'], //currency
            'details'=>$rawData['details'], //payment details
            'ext_details'=>$rawData['ext_details'],
            'pay_way'=>$rawData['pay_way'], // payment method
            'order'=>$rawData['order'], //order id
            'merchant'=>$rawData['merchant'], // merchant id
            'state'=>$rawData['state'], // status
            'date'=>$rawData['date'],// (фомат ddMMyyHHmmss)
            'ref'=>$rawData['ref'], //          0 1 2 3 4 5
            'sender_phone'=> $rawData['sender_phone'],
            'signature'=>$rawData['signature'],
        );
        $localSignature = sha1(md5($_POST['payment'].$this->pass));
        if($localSignature!==$_POST['signature'] || $data['merchant']!=$this->merchantID){
            header('HTTP/1.0 403 Forbidden');
            die('Access denied');
        }
        $order = new DBObject('sr_shop_order');
        $order->fetch("`uniquid`='".Helpers::mysql_escape($data['order'])."'");
        if(!$order->id){
            header('HTTP/1.0 404 not found');
            die('Order not found');
        }

        //Parse date
        $dateArray = explode(',',chunk_split($data['date'],2,','));
        $data['raw_date']=$data['date'];
        $data['date']=mktime(
            $dateArray[3],
            $dateArray[4],
            $dateArray[5],
            $dateArray[1],
            $dateArray[0],
            $dateArray[2]
        );
        return array('order'=>$order,'data'=>$data);
    }

    public function generateForm($data){
        return '
            <form method="POST" action="https://api.privatbank.ua/p24api/ishop">
                <input type="hidden" name="amt" value="'.$data['amount'].'" />
                <input type="hidden" name="ccy" value="UAH" />
                <input type="hidden" name="merchant" value="'.$this->merchantID.'" />
                <input type="hidden" name="order" value="'.$data['order_id'].'" />
                <input type="hidden" name="details" value="Оплата заказа №'.$data['id'].'" />
                <input type="hidden" name="ext_details" value="Клиент: '.$data['customer'].'" />
                <input type="hidden" name="pay_way" value="privat24" />
                <input type="hidden" name="server_url" value="'.HTTP_ROOT.'/payment/privat" />
                <input type="hidden" name="return_url" value="'.HTTP_ROOT.'/payment/success/'.$data['order_id'].'" />
                <button class="btn-red-big" type="submit">'.Lang::$locale['pay_with_privat24'].'</button>
            </form>';
    }

}
