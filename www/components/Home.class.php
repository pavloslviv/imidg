<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
class Home extends Component
{
    protected $excludeProducts = array();
    protected function registerActions()
    {
        $this->actions['default'] = 'show';
        $this->actions['show'] = 'show';
    }

    public function show()
    {
        $page = new DBObject('sr_page', 1);
        $smarty = Core::getSmarty();

        $smarty->assign('meta_title',$page->get('meta_title') ? $page->get('meta_title') : $page->get('title'));
        $smarty->assign('meta_descr',$page->get('meta_descr'));
        $smarty->assign('meta_keyw',$page->get('meta_keyw'));

        $smarty->assignByRef('page', $page);
        $smarty->assignByRef('products', $this->getProducts());
        $smarty->assignByRef('product_hit', $this->getHits());
        $smarty->assignByRef('product_new', $this->getNewAndSale());
        $smarty->assign('component', 'home');
        $this->getAds();
    }

    private function getProducts(){
        $products = new DBCollection('sr_shop_product', array('id', 'title', 'sef', 'new','sale', 'image', 'instock','min_price','max_price'));
        $exclude = count($this->excludeProducts) ? ' and id not in ('.implode(',',$this->excludeProducts).')' : '';
        $products->fetch("`parent_id`=0 and `home`=1 and `instock`=1 and `active`=1".$exclude,"RAND()",4);
        $this->excludeProducts = array_merge($this->excludeProducts,array_keys($products->data));
        return $products->data;
    }

    private function getHits(){
        $products = new DBCollection('sr_shop_product', array('id', 'title', 'sef', 'new','sale', 'image', 'instock','min_price','max_price'));
        $exclude = count($this->excludeProducts) ? ' and id not in ('.implode(',',$this->excludeProducts).')' : '';
        $products->fetch("`parent_id`=0 and `hit`=1 and `instock`=1 and `active`=1".$exclude,"RAND()",15);
        $this->excludeProducts = array_merge($this->excludeProducts,array_keys($products->data));
        return $products->data;
    }

    private function getNewAndSale(){
        $products = new DBCollection('sr_shop_product', array('id', 'title', 'sef', 'new','sale', 'image', 'instock','min_price','max_price'));
        $exclude = count($this->excludeProducts) ? ' and id not in ('.implode(',',$this->excludeProducts).')' : '';
        $products->fetch("`parent_id`=0 and (`new`=1 or `sale`=1) and `instock`=1 and `active`=1".$exclude,"RAND()", 15);
        $this->excludeProducts = array_merge($this->excludeProducts,array_keys($products->data));
        return $products->data;
    }

    public function getAds()
    {
        $itemList = new DBCollection('sr_slides', array('id', 'title', 'link', 'text', 'img'));
        $itemList->fetch('img=1','RAND()');
        $smarty = Core::getSmarty();
        $smarty->assignByRef('slides', $itemList->data);
    }

}
