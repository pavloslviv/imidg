<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:55
 * To change this template use File | Settings | File Templates.
 */
abstract class Component
{
    protected $actions = array();

    public function __construct()
    {
        $this->registerActions();
    }

    protected abstract function registerActions();

    public function run($action = null)
    {
        if ($this->actions[$action]) $this->{$this->actions[$action]}();
        else $this->{$this->actions['default']}();
    }

    public function sendJSON($data)
    {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    }

    public function sendLetter($tpl,$email,$subject,$data,$from=null,$fromName=null)
    {
        include_once(ROOT . '/lib/Mailer.class.php');
        $mailer = new Mailer();

        if($from){
            $mailer->From = $from;
        }
        if($fromName){
            $mailer->FromName = $fromName;
        }

        $mailer->Subject = $subject;
        //Generate letter body
        $smarty = Core::getSmarty();
        $smarty->assign('data', $data);
        $mailer->isHTML(true);
        $mailer->Body = $smarty->fetch('mail/'.$tpl.'.tpl');
        //Send letter
        $mailer->AddAddress($email);
        $mailer->Send();
        $mailer->ClearAddresses();
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
}
