<?php
defined('RUN_CMS') or die('Restricted access');
/**
 * Created by JetBrains PhpStorm.
 * User: Serg
 * Date: 03.01.12
 * Time: 7:28
 * To change this template use File | Settings | File Templates.
 */
class ShopProducts extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'listItems';
        $this->actions['list'] = 'listItems';
        $this->actions['find'] = 'findItems';
        $this->actions['order'] = 'updateOrder';
        $this->actions['get'] = 'get';
        $this->actions['save'] = 'save';
        $this->actions['save_options'] = 'saveOptions';
        $this->actions['delete'] = 'delete';
        $this->actions['thumb'] = 'uploadImage';
        $this->actions['remove_thumb'] = 'deleteImage';
        $this->actions['import'] = 'importProcuctList';
    }

    public function listItems()
    {
        $page = (int)$_REQUEST['page'];
        $itemList = new DBCollection('sr_shop_product', array('id', 'parent_id', 'title','order', 'stock', 'price','active'));
        //fetchPage($pageNum, $where = '', $orderBy = null, $itemPerPage = 10)
        if(isset($_REQUEST['page'])){
            $itemList->fetchPage($page,$this->prepareFilter(),'`parent_id` asc,`order` asc, `title` asc',20);
        } else {
            $itemList->fetch($this->prepareFilter(),'`parent_id` asc,`order` asc, `title` asc');
        }

        $items = $itemList->data;
        if($_REQUEST['simple']){
            $this->sendJSON(array(
                'success'=>true,
                'products'=>array_values($items),
                'pagesCount'=>$itemList->pagesCount,
                'currentPage'=>$itemList->currentPage
            ));
            return;
        }
        $ids = Helpers::array_pluck($items,'id');
        $modList = new DBCollection('sr_shop_product', array('id', 'parent_id', 'title','order', 'stock', 'price','active'));
        $modList->fetch('`parent_id` in ('.implode(',',$ids).')','`order` asc, `title` asc');
        foreach($modList->data as $id=>$mod){
            $parent = &$items[$mod['parent_id']];
            if(!$parent['modifications']){
                $parent['modifications']=array();
            }
            array_push($parent['modifications'],$mod);
        }
        $this->sendJSON(array(
            'success'=>true,
            'products'=>array_values($items),
            'pagesCount'=>$itemList->pagesCount,
            'currentPage'=>$itemList->currentPage
        ));
    }

    public function findItems()
    {
        $itemList = new DBCollection('sr_shop_product', array('id', 'parent_id', 'title','order', 'stock', 'price'));
        $itemList->fetch("parent_id=0 and title LIKE '%".$_REQUEST['query']."%'",'`title` asc');
        $items = $itemList->data;
        $ids = Helpers::array_pluck($items,'id');
        $modList = new DBCollection('sr_shop_product', array('id', 'parent_id', 'title','order', 'stock', 'price','active'));
        $modList->fetch('`parent_id` in ('.implode(',',$ids).')','`order` asc, `title` asc');
        foreach($modList->data as $id=>$mod){
            $parent = &$items[$mod['parent_id']];
            if(!$parent['modifications']){
                $parent['modifications']=array();
            }
            array_push($parent['modifications'],$mod);
        }
        $this->sendJSON(array('success'=>true,'products'=>array_values($items)));
    }

    public function updateOrder()
    {
        if(!is_array($_POST['items'])){
            $this->sendJSON(array('success'=>false,'message'=>'No item order passed'));
        }
        $db = Core::getDB();
        foreach($_POST['items'] as $id=>$order){
            $db->query('UPDATE `sr_shop_product` SET `order`=? WHERE `id`=?',(int)$order,(int)$id);
        }
        $this->sendJSON(array('success'=>true));
    }

    public function prepareFilter(){
        $result=array('parent_id=0');
        if(!$_REQUEST['filter'] || !count($_REQUEST['filter'])){
            return implode(' and ',$result);
        }
        if(!isset($_REQUEST['filter']['active'])){
            array_push($result,"`active`>=0");
        }
        foreach ($_REQUEST['filter'] as $field=>$value){
            /*if(!$value) continue;*/
            array_push($result,"`$field`='$value'");
        }
        return implode(' and ',$result);
    }

    public function get($id=null)
    {
        $product = new DBObject('sr_shop_product',$id ? $id : (int)$_GET['id']);
        if(!$product->id){
            $this->sendJSON(array('success'=>false,'message'=>'Product not found'));
        }
        $section = new DBObject('sr_shop_section',$product->get('section_id'));
        if($section->get('cat_level')!=1){
            $topSection = new DBObject('sr_shop_section');
            $topSection->fetch('`cat_left`<'.(int)$section->get('cat_left').' and `cat_right`>'.(int)$section->get('cat_right').' and `cat_level`=1');
        } else {
            $topSection = $section;
        }
        $result = $product->getAll();
        $options = array();
        if($topSection->id){
            unset($result['section_id']);
            $result['section']=$section->getAll();
            $result['top_section']=$topSection->getAll();
            $availableOptions = new DBCollection('sr_shop_option');
            $availableOptions->fetch('`section_id`='.(int)$topSection->id);
            $options = $availableOptions->data;
            if(count($options)){
                $result['options']=$this->fillOptions($availableOptions->data,$product->id);
            } else {
                $result['options']=array();
            }
        } else {
            $result['section']= array();
            $result['top_section']= array();
            $result['options']=array();
        }

        $modificationsList = new DBCollection('sr_shop_product');
        $modificationsList->fetch('`parent_id`='.(int)$product->id,'`order` asc');
        $result['modifications']=array();
        if(count($modificationsList->data)){
            foreach($modificationsList->data as $m){
                $m['options'] = $this->fillOptions($options,$m['id']);
                array_push($result['modifications'],$m);
            }
        }
        $this->sendJSON(array('success'=>true,'product'=>$result));
    }

    private function fillOptions($availableOptions,$productId){
        $optionIds = Helpers::array_pluck($availableOptions,'id');
        $productOptions = new DBCollection('sr_shop_option_value');
        $productOptions->fetch('option_id in ('.implode(',',$optionIds).') and product_id='.(int)$productId);
        $options = $availableOptions;
        foreach($options as &$option){
            $option['option_id']=$option['id'];
            unset($option['id']);
        }
        foreach($productOptions->data as $value){
            $value['value_id']=$value['id'];
            unset($value['id']);
            $options[$value['option_id']] = array_merge($options[$value['option_id']],$value);
        }
        return array_values($options);
    }

    public function save()
    {
        if(isset($_POST['sef'])){
            $_POST['sef']=trim($_POST['sef']);
            if($_POST['sef']==''){
                $_POST['sef']=Helpers::TranslitToURL($_POST['title']);
            }
        }
        $product = new DBObject('sr_shop_product',(int)$_POST['id']);
        if($_POST['options']){
            $this->saveOptions(true,(int)$product->get('parent_id'));
            unset($_POST['options']);
        }
        if($_POST['section_id'] && $_POST['section_id']!=$product->get('section_id')){
            $this->moveProduct($product,$_POST['section_id']);
            $product->fetch();
        }
        if (isset($_POST['offer'])) {
            $this->updatePriceForPercentDiscount($product);
        }
        //print_r($_POST);
        $product->set($_POST);
        $product->save();

        if(isset($_POST['section_id']) || isset($_POST['active'])){
            $db = Core::getDB();
            $db->query('UPDATE `sr_shop_product` SET `active`=?, `section_id`=? WHERE `parent_id`=?',
                (int)$product->get('active'),(int)$product->get('section_id'),(int)$product->id
            );
        }

        $this->updateStockAndPrice($product->get('parent_id')=='0' ? $product : $product->get('parent_id'));
        $this->get($product->id);
    }

    public function  saveOptions($noReturn=false,$parentId=0){
        $productId = (int)$_POST['id'];
        $parentId = $parentId ? $parentId : (int)$_POST['parent_id'];
        $parentId = $parentId ? $parentId : $productId;
        $result=array();
        foreach($_POST['options'] as $option){
            $o = new DBObject('sr_shop_option_value',(int)$option['value_id']);
            $o->set(array(
                'value'=>$option['value']
            ));
            if(!$o->id){
                $o->set(array(
                    'option_id'=>$option['option_id'],
                    'product_id'=>$productId,
                    'main_product_id'=>$parentId
                ));
            }
            $o->save();
            $r = $o->getAll();
            $r['value_id']=$r['id'];
            unset($r['id']);
            array_push($result,$r);
        }
        if(!$noReturn){
            $this->sendJSON(array('success'=>true,'options'=>$result));
        }
    }

    public function moveProduct($product,$targetCatId){
        $currentSection = new DBObject('sr_shop_section',(int)$product->get('section_id'));
        $targetSection = new DBObject('sr_shop_section',(int)$targetCatId);
        if($currentSection->get('cat_level')==1){
            $currentTopSection = $currentSection;
        } else {
            $currentTopSection =  new DBObject('sr_shop_section');
            $currentTopSection->fetch('`cat_left`<'.(int)$currentSection->get('cat_left').' and `cat_right`>'.(int)$currentSection->get('cat_right').' and `cat_level`=1');
        }
        if($targetSection->get('cat_level')==1){
            $targetTopSection = $targetSection;
        } else {
            $targetTopSection =  new DBObject('sr_shop_section');
            $targetTopSection->fetch('`cat_left`<'.(int)$targetSection->get('cat_left').' and `cat_right`>'.(int)$targetSection->get('cat_right').' and `cat_level`=1');
        }

        if($currentTopSection->id==$targetTopSection->id){
            return;
        }

        $currentAvailableOptions = new DBCollection('sr_shop_option');
        $currentAvailableOptions->fetch('`section_id`='.(int)$currentTopSection->id);
        $targetAvailableOptions = new DBCollection('sr_shop_option');
        $targetAvailableOptions->fetch('`section_id`='.(int)$targetTopSection->id);

        $optToRemove = array();
        $db = Core::getDB();
        foreach($currentAvailableOptions->data as $option){
            $equivalent = false;
            foreach($targetAvailableOptions->data as  $targetOpt){
                if($targetOpt['title']==$option['title']){
                    $equivalent = $targetOpt['id'];
                    break;
                }
            }
            if($equivalent){
                $db->query('UPDATE `sr_shop_option_value` SET `option_id`=? WHERE `main_product_id`=? and `option_id`=?',$equivalent,$product->id,$option['id']);
            } else {
                array_push($optToRemove,$option['id']);
            }
        }
        if(count($optToRemove)){
            $db->query('DELETE FROM `sr_shop_option_value` WHERE `option_id` in (?a) and `main_product_id`=? ',$optToRemove,$product->id);
        }
    }

    public  function uploadImage(){
        include_once(ROOT . '/lib/VO/ProductImage.class.php');
        if (!(int)$_POST['id']) {
            $this->sendJSON(array('result' => 'error', 'message' => 'Product ID not specified'));
            return;
        }
        $this->deleteImage((int)$_POST['id']);
        $image = new ProductImage((int)$_POST['id']);
        if($image->upload('file')){
            $image->makeThumbs();
            $this->sendJSON(array('result' => 'success', 'image' => $image->type));
        } else {
            $this->sendJSON(array('result' => 'error', 'message' => 'Upload unsuccessful'));
        }
    }

    public function deleteImage($id=null)
    {
        $productId = $id ? $id :  (int)$_POST['id'];
        if (!$productId) {
            $this->sendJSON(array('result' => 'error', 'message' => 'Product ID not specified'));
            return false;
        }
        include_once(ROOT . '/lib/VO/ProductImage.class.php');
        $image = new ProductImage($productId);
        $image->delete();
        if($id){
            return true;
        } else {
            $this->sendJSON(array('result' => 'success'));
        }

    }

    public function delete($id=null)
    {
        $productId = $id ? $id : (int)$_POST['id'];
        $product = new DBObject('sr_shop_product',$productId);
        if(!$product->id){
            $this->sendJSON(array('success'=>false,'message'=>'Product not found'));
        }
        $this->deleteImage($productId);
        if($product->get('parent_id')!='0'){
            $this->updateStockAndPrice($product->get('parent_id'));
        }

        $db = Core::getDB();
        $db->query('DELETE FROM `sr_shop_option_value` WHERE `product_id`=?',$productId);
        $modificationsList = new DBCollection('sr_shop_product',array('id'));
        $modificationsList->fetch('`parent_id`='.(int)$product->id);
        if(count($modificationsList->data)){
            foreach($modificationsList->data as $m){
                $this->delete($m['id']);
            }
        }
        $product->delete();
        if(!$id){
            $this->sendJSON(array('success'=>true));
        }
    }

//    public function updateStockAndPrice($product){
//        if(!($product instanceof DBObject)){
//            $product = new DBObject('sr_shop_product',(int)$product);
//        }
//        if(!$product->id) return false;
//        $modificationsList = new DBCollection('sr_shop_product',array('id','parent_id','price','sale_price','stock','reserved'));
//        $modificationsList->fetch('`parent_id`='.(int)$product->id);
//        $minPrice = false;
//        $maxPrice = false;
//        $inStock = 0;
//        $isSale = 0;
//        if(is_array($modificationsList->data) && count($modificationsList->data)){
//            foreach($modificationsList->data as $m){
//                $price = (float)$m['sale_price']>0 ? (float)$m['sale_price'] : (float)$m['price'];
//                $stock = (int)$m['stock']-(int)$m['reserved'];
//                if($stock){
//                    if($minPrice===false || $minPrice>$price) $minPrice=$price;
//                    if($maxPrice===false || $maxPrice<$price) $maxPrice=$price;
//                }
//                if($stock) $inStock=1;
//                if((float)$m['sale_price']>0) $isSale=1;
//            }
//        } else {
//            $inStock = (int)$product->get('stock')-(int)$product->get('reserved')>0 ? 1 : 0;
//            $isSale = (float)$product->get('sale_price')>0 ? 1 : 0;
//        }
//
//        $productPrice = (float)$product->get('sale_price')>0 ? (float)$product->get('sale_price') : (float)$product->get('price');
//        if($minPrice===false) $minPrice=$productPrice;
//        if($maxPrice===false) $maxPrice=$productPrice;
//        $product->set(array(
//            'min_price'=>$minPrice,
//            'max_price'=>$maxPrice,
//            'instock'=>$inStock,
//            'sale'=>$isSale
//        ));
//        $product->save();
//    }

    public function importProcuctList(){
        if (!$_FILES['file']['name']) {
            $this->sendJSON(array('result' => 'error', 'message' => 'Upload unsuccessful'));
            return;
        }
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        if (strtolower($ext)!='xml') {
            $this->sendJSON(array('result' => 'error', 'message' => 'File should be in XML format'));
            return;
        }
        $fileName = ROOT . '/media/files/' . time() . '.xml';
        $uploadResult = move_uploaded_file($_FILES['file']['tmp_name'], $fileName);
        if(!$uploadResult){
            $this->sendJSON(array('result' => 'error', 'message' => 'Upload unsuccessful'));
            return;
        }
        $db = Core::getDB();
        $existingItems = $db->select('SELECT code as ARRAY_KEY,id,code,parent_id,stock,price,sale_price FROM sr_shop_product WHERE NOT code IS NULL');
        $data = file_get_contents($fileName);
        $xml = new SimpleXMLElement($data);
        $newItems = array();
        $newCount = 0;
        $updCount = 0;
        $skipCount = 0;
        foreach($xml->item as $item){
            $stock = (int)$item->stock[0];
            $code = (string)$item->code[0];
            $price = (float)$item->price[0];
            if($newItems[$code]) continue;
            $newItems[$code]=true;
            if((!$stock && !$existingItems[$code]) || $price<=0.01) {
                $skipCount++;
                continue;
            }

            if($existingItems[$code]){
                $product = new DBObject('sr_shop_product',$existingItems[$code]['id']);
                if((float)$existingItems[$code]['sale_price']>0){
                    $product->set(array(
                        "stock"=>$stock
                    ));
                } else {
                    $product->set(array(
                        "price"=>(float)$item->price[0],
                        "stock"=>$stock
                    ));
                }
                $product->save();
                $updCount++;
                $this->updateStockAndPrice($product->get('parent_id')=='0' ? $product : $product->get('parent_id'));
            } else {
                $product = new DBObject('sr_shop_product');
                $product->set(array(
                    "code"=>$code,
                    "title"=>(string)$item->title[0],
                    "price"=>(float)$item->price[0],
                    "stock"=>$stock,
                    'active'=>-1
                ));
                $product->save();
                $newCount++;
            }
        }
        $this->sendJSON(array('result' => 'success', 'data' => array('new'=>$newCount,'update'=>$updCount,'skip'=>$skipCount)));
    }

    private function updatePriceForPercentDiscount($product)
    {
        $offerRate = trim($_POST['offer']);
        $offer  = $_POST['price'] * ($offerRate/100);

        if ($offerRate == 0 && !$this->checkIfSalePriceChanged($product)) {
            $_POST['sale_price'] = 0;
        } else if ($offerRate != 0) {
            $_POST['sale_price'] = money_format('%i',$_POST['price'] -  $offer);
        }

        $modificationsList = new DBCollection('sr_shop_product');

        $modificationsList->fetch('`parent_id`='.(int)$product->id);
        if(count($modificationsList->data)){
            foreach($modificationsList->data as $m){
                $m['offer'] = $offerRate;
                $mp = new DBObject('sr_shop_product',$m['id']);
                $offer  = $m['price'] * ($offerRate/100);
                if ($offerRate == 0) {
                    $m['sale_price'] = 0;
                } else {
                    $m['sale_price'] = money_format('%i',$m['price'] -  $offer);
                }
                $mp->set($m);
                $mp->save();
            }
        }
    }

    public function checkIfSalePriceChanged($product)
    {
        $current = (float)$product->get('sale_price');
        $salePrice = (float)trim($_POST['sale_price']);

        if ($salePrice == $current) {
            return false;
        }
        return true;
    }
}
