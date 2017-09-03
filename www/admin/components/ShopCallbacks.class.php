<?php
defined('RUN_CMS') or die('Restricted access');
/**
 * Created by JetBrains PhpStorm.
 * User: Serg
 * Date: 03.01.12
 * Time: 7:28
 * To change this template use File | Settings | File Templates.
 */
class ShopCallbacks extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'listItems';
        $this->actions['list'] = 'listItems';
        $this->actions['get'] = 'get';
        $this->actions['save'] = 'save';
        $this->actions['item_add'] = 'addItem';
        $this->actions['item_update'] = 'updateItem';
        $this->actions['del'] = 'delete';
        $this->actions['status'] = 'changeStatus';
        $this->actions['reassign_order'] = 'reassignOrder';
        $this->actions['send_payment'] = 'sendPayment';
    }

    public function listItems()
    {
        $page = (int)$_REQUEST['page'];
        $orderList = new DBCollection('sr_shop_callback', array('id','customer_name', 'customer_phone','date', 'status'));
        if(isset($_REQUEST['page'])){
            $orderList->fetchPage($page,$this->prepareFilter(),'date desc',20);
        } else {
            $orderList->fetch($this->prepareFilter(),'date desc');
        }
/*
        $statuses = array_unique(Helpers::array_pluck($orderList->data,'status_id'));
        $statusesList = new DBCollection('sr_shop_orders_status', array('id', 'text'));
        $statusesList->fetch('`id` in ('.implode(',',$statuses).')');
        foreach($orderList->data as &$order){
            $order['status']=$statusesList->data[$order['status_id']];
        }*/
        $this->sendJSON(array(
            'success'=>true,
            'orders'=>array_values($orderList->data),
            'pagesCount'=>$orderList->pagesCount,
            'currentPage'=>$orderList->currentPage
        ));
    }

    public function prepareFilter(){
        if(!$_REQUEST['filter'] || !count($_REQUEST['filter'])){
            return '';
        }
        $result=array();
        foreach ($_REQUEST['filter'] as $field=>$value){
            if(!$value) continue;
            array_push($result,"`$field`='".Helpers::mysql_escape($value)."'");
        }
        return implode(' and ',$result);
    }

    public function get($id=null)
    {
        $order = new DBObject('sr_shop_callback',$id ? $id : (int)$_GET['id']);
        if(!$order->id){
            $this->sendJSON(array('success'=>false,'message'=>'Order not found'));
        }
        //$customer = new DBObject('sr_customer',$order->get('customer_id'));
        $result = $order->getAll();
        //unset($result['customer_id']);
        //$result['customer']=$customer->getAll();
        //$items = new DBCollection('sr_shop_order_item');
       // $items->fetch('`order_id`='.(int)$order->id);
        //$result['items']=array_values($items->data);
        $this->sendJSON(array('success'=>true,'order'=>$result));
    }

    public function save()
    {
        $allowedFields = array('customer_name', 'customer_mail', 'customer_phone', 'payment', 'shipment', 'payment_data', 'shipment_data','date');
        if($_POST['items']){
            unset($_POST['items']);
        }
        $data=array();
        foreach($allowedFields as $field){
            if(!isset($_POST[$field])) continue;
            if($field=='shipment_data' || $field=='payment_data'){
                $data[$field]= json_encode($_POST[$field]);
            } else {
                $data[$field]= htmlspecialchars(trim($_POST[$field]));
            }

        }
        $order = new DBObject('sr_shop_order',(int)$_POST['id']);
        $order->set($data);
        if(!$order->id){
            $order->set('status','new');
        }
        $order->save();
        $this->sendJSON(array('success'=>true,'order'=>$order->getAll()));
    }

    public function reassignOrder(){
        $order = new DBObject('sr_shop_order',(int)$_POST['order_id']);
        $customer = new DBObject('sr_customer',(int)$_POST['customer_id']);
        if(!$order->id || !$customer->id){
            $this->sendJSON(array('success'=>false,'message'=>'Заказ или клиент не найден'));
        }
        if($order->get('customer_id') && $order->get('status')=='done'){
            $this->updateDiscount($order->get('customer_id'),$order->get('total'),true);
        }
        $order->set('customer_id',$customer->id);
        if(!$order->get('customer_name')){
            $order->set('customer_name',$customer->get('name'));
        }
        if(!$order->get('customer_mail')){
            $order->set('customer_mail',$customer->get('mail'));
        }
        if(!$order->get('customer_phone')){
            $order->set('customer_phone',$customer->get('phone'));
        }
        $order->save();
        if($order->get('status')=='done'){
            $this->updateDiscount($order->get('customer_id'),$order->get('total'));
        }
        $this->get($order->id);
    }

    public function  addItem(){
        $orderId = (int)$_POST['order_id'];
        $productId = (int)$_POST['product_id'];
        $qty = 1;
        $order = new DBObject('sr_shop_order',$orderId);
        if(!$order->id){
            $this->sendJSON(array('success'=>false,'message'=>'Order not found'));
        }
        if($order->get('status')!=='new' && $order->get('status')!=='processing'){
            $this->sendJSON(array('success'=>false,'message'=>'Order not editable'));
        }
        $p = new DBObject('sr_shop_product',$productId);
        $discount = ShopCore::getDiscount($order->get('customer_id'));
        $this->setTypes($p);
        if(!$p->id){
            $this->sendJSON(array('success'=>false,'message'=>'Product not found'));
        }
        if($p->get('parent_id')!=0){
            $m = $p;
            $p = new DBObject('sr_shop_product',$m->get('parent_id'));
            $this->setTypes($p);
        } else {
            $m = false;
        }
        if($m){
            if($m->get('sale_price')>0){
                $price = $m->get('sale_price');
                $comment = 'Акция. Стандартная цена: '.$m->get('price').' грн';
            } else {
                if(!$discount){
                    $price = $m->get('price');
                    $comment='';
                } else {
                    $price = round($m->get('price')*(1-$discount));
                    $comment='Дисконт '.($discount*100).'%. Стандартная цена: '.$m->get('price').' грн';
                }
            }
            $m->set('reserved',$m->get('reserved')+$qty);
            $m->save();
        } else {
            if($p->get('sale_price')>0){
                $price = $p->get('sale_price');
                $comment = 'Акция. Стандартная цена: '.$p->get('price').' грн';
            } else {
                if(!$discount){
                    $price = $p->get('price');
                    $comment='';
                } else {
                    $price = round($p->get('price')*(1-$discount));
                    $comment='Дисконт '.($discount*100).'%. Стандартная цена: '.$p->get('price').' грн';
                }
            }
            $p->set('reserved',$p->get('reserved')+$qty);
            $p->save();
        }

        $orderItem = new DBObject('sr_shop_order_item');
        $orderItem->set(array(
            'order_id'=>$order->id,
            'product_id'=>$m ? $m->id : $p->id,
            'code'=>$m ? $m->get('code') : $p->get('code'),
            'title'=>$p->get('title').($m ? ' - '.$m->get('title') : ''),
            'price'=>$price,
            'qty'=>$qty,
            'comment'=>$comment
        ));
        $orderItem->save();
        $this->updateStockAndPrice($p);

        $order->set('total',$this->recountOrderTotal($order->id));
        $order->save();

        if($orderItem->id){
            $this->sendJSON(array(
                'success'=>true,
                'order_item'=>$orderItem->getAll(),
                'total'=>$order->get('total')
            ));
        } else {
            $this->sendJSON(array('success'=>false,'message'=>'Unknown error'));
        }

    }

    public function  updateItem(){
        $itemId = (int)$_POST['id'];
        $orderItem = new DBObject('sr_shop_order_item',$itemId);
        $qty = (int)$_POST['qty'];
        $price = (float)$_POST['price'];
        $this->setTypes($orderItem);
        if(!$orderItem->id){
            $this->sendJSON(array('success'=>false,'message'=>'Order item not found'));
        }
        $order = new DBObject('sr_shop_order',(int)$orderItem->get('order_id'));
        if(!$order->id){
            $this->sendJSON(array('success'=>false,'message'=>'Order not found'));
        }
        if($order->get('status')!=='new' && $order->get('status')!=='processing'){
            $this->sendJSON(array('success'=>false,'message'=>'Order not editable'));
        }
        $p = new DBObject('sr_shop_product',(int)$orderItem->get('product_id'));
        //If product present in DB update reserved
        if($p->id){
            $this->setTypes($p);
            $p->set('reserved',(int)$p->get('reserved')-(int)$orderItem->get('qty')+$qty);
            $p->save();
            $this->updateStockAndPrice($p->get('parent_id')==0 ? $p : $p->get('parent_id'));
        }
        if($qty>0){
            $orderItem->set(array(
                'qty'=>$qty,
                'price'=>$price
            ));
            $orderItem->save();
        } else {
            $orderItem->delete();
        }
        $order->set('total',$this->recountOrderTotal($order->id));
        $order->save();
        $this->sendJSON(array(
            'success'=>true,
            'order_item'=>$orderItem->getAll(),
            'total'=>$order->get('total')
        ));
    }

    public function changeStatus($orderId=null,$status=null){
        //$returnResult = $orderId && $status;
        $allowedStatuses = ShopCore::$callbaсkStatuses;
        if(!$orderId){
            $orderId = (int)$_POST['id'];
        }
        if(!$status){
            $status = $_POST['status'];
        }
        $order = new DBObject('sr_shop_callback',$orderId);

        if(!$order->id){
            $this->sendJSON(array('success'=>false,'message'=>'Product not found'));
        }
        if(!$allowedStatuses[$status]){
            $this->sendJSON(array('success'=>false,'message'=>'Product not found'));
        }
        if($order->get('status')==$status){
            $this->get($orderId);
            return;
        }
        $order->set('status',$status);
        $order->save();
        $this->get($orderId);
    }

    public function updateWarehouse($orderId,$warehouse=null,$reserved=null){
        $items = new DBCollection('sr_shop_order_item',array('id','order_id','product_id','qty'));
        $items->fetch('order_id='.(int)$orderId);
        if(!count($items->data)) return;
        foreach($items->data as $id=>$item){
            $product = new DBObject('sr_shop_product',(int)$item['product_id']);
            if(!$product->id) continue;
            if($warehouse){
                $product->set('stock',$product->get('stock')+$warehouse*$item['qty']);
            }
            if($reserved){
                $product->set('reserved',$product->get('reserved')+$reserved*$item['qty']);
            }
            $product->save();
            $this->updateStockAndPrice($product->get('parent_id')=='0' ? $product : $product->get('parent_id'));
        }

    }

    public function recountOrderTotal($orderId){
        $items = new DBCollection('sr_shop_order_item',array('id','order_id','price','qty'));
        $items->fetch('order_id='.(int)$orderId);
        if(!count($items->data)) return 0;
        $totalPrice = 0;
        foreach($items->data as $id=>$item){
            $totalPrice+=(int)$item['qty']*(float)$item['price'];
        }
        return $totalPrice;
    }

    public function updateStockAndPrice($product){
        if(!($product instanceof DBObject)){
            $product = new DBObject('sr_shop_product',(int)$product);
        }
        if(!$product->id) return false;
        $modificationsList = new DBCollection('sr_shop_product',array('id','parent_id','price','sale_price','stock','reserved'));
        $modificationsList->fetch('`parent_id`='.(int)$product->id);
        $minPrice = false;
        $maxPrice = false;
        $inStock = 0;
        $isSale = 0;
        if(is_array($modificationsList->data) && count($modificationsList->data)){
            foreach($modificationsList->data as $m){
                $price = (float)$m['sale_price']>0 ? (float)$m['sale_price'] : (float)$m['price'];
                $stock = (int)$m['stock']-(int)$m['reserved'];
                if($stock){
                    if($minPrice===false || $minPrice>$price) $minPrice=$price;
                    if($maxPrice===false || $maxPrice<$price) $maxPrice=$price;
                }
                if($stock) $inStock=1;
                if((float)$m['sale_price']>0) $isSale=1;
            }
        } else {
            $inStock = (int)$product->get('stock')-(int)$product->get('reserved')>0 ? 1 : 0;
            $isSale = (float)$product->get('sale_price')>0 ? 1 : 0;
        }

        $productPrice = (float)$product->get('sale_price')>0 ? (float)$product->get('sale_price') : (float)$product->get('price');
        if($minPrice===false) $minPrice=$productPrice;
        if($maxPrice===false) $maxPrice=$productPrice;
        $product->set(array(
            'min_price'=>$minPrice,
            'max_price'=>$maxPrice,
            'instock'=>$inStock,
            'sale'=>$isSale
        ));
        $product->save();
    }

    private function updateDiscount($customerId,$amount,$substract=false){
        $discount = new DBObject('sr_shop_discount');
        $discount->fetch('`customer_id`='.(int)$customerId);
        $discountLimits = json_decode(Core::getSettings('shop','discounts'),true);
        if(!$discount->id && ((float)$amount<(float)$discountLimits[0]['amount'] || $substract)) return;
        if($discount->get('type')=='fixed') return;
        if(!$discount->id){
            $customer = new DBObject('sr_customer',(int)$customerId);
            $discount->set(array(
                'customer_id'=>(int)$customerId,
                'customer_name'=>$customer->get('name'),
                'type'=>'cumulative',
                'discount'=>0,
                'amount'=>0
            ));
        }
        $discount->set('amount',(float)$discount->get('amount')+($substract ? -1 : 1)*(float)$amount);
        if((float)$discount->get('amount')<(float)$discountLimits[0]['amount'] && $substract){
            $discount->set('discount',0);
        } else {
            foreach($discountLimits as $d){
                //If limit amount is higher than users amount we should stop
                if((float)$discount->get('amount')<(float)$d['amount']){
                    break;
                }
                //Don't decrease discount, except case when we substract amount
                if((float)$discount->get('discount')>(float)$d['percent'] && !$substract){
                    continue;
                }
                $discount->set('discount',(float)$d['percent']);
            }
        }

        $discount->save();
    }

    private function setTypes(&$product){
        $isObject = $product instanceof DBObject;
        $intProp = array('id','section_id','parent_id','order','stock','reserved','active','home','new','hit','sale');
        $floatProp = array('price','sale_price');
        foreach($intProp as $name){
            if($isObject){
                if ($product->get($name)===null) continue;
                $product->set($name,(int)$product->get($name));
            } else {
                if(!array_key_exists($name,$product)) continue;
                $product[$name]=(int)$product[$name];
            }

        }
        foreach($floatProp as $name){
            if($isObject){
                if ($product->get($name)===null) continue;
                $product->set($name,(float)$product->get($name));
            } else {
                if(!array_key_exists($name,$product)) continue;
                $product[$name]=(float)$product[$name];
            }
        }
    }

    public function sendPayment(){
        $orderId = (int)$_POST['order_id'];
        $order = new DBObject('sr_shop_order',$orderId);
        if(!$order->id){
            $this->sendJSON(array('success'=>false,'message'=>'Order not found'));
        }
        if($order->get('status')!=='new' && $order->get('status')!=='processing'){
            $this->sendJSON(array('success'=>false,'message'=>'Order not editable'));
        }
        $paymentData = json_decode($order->get('payment_data'),true);
        $paymentData['last_request']=time();
        $data = array(
            'uniquid'=>uniqid(),
            'payment_data'=>json_encode($paymentData)
        );
        $order->set($data);
        $order->save();
        $this->sendLetter(
            'payment',
            $order->get('customer_mail'),
            'Оплата замовлення №'.$order->id,
            array(
                'id'=>$order->id,
                'total'=>(float)$order->get('total'),
                'link'=>HTTP_ROOT.'/payment/pay/'.$order->get('uniquid')
            )
        );

        $this->sendJSON(array('success'=>true,'data'=>$data));

    }

    public function delete()
    {
    }
}
