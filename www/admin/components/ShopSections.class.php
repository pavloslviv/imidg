<?php
defined('RUN_CMS') or die('Restricted access');
/**
 * Created by JetBrains PhpStorm.
 * User: Serg
 * Date: 03.01.12
 * Time: 7:28
 * To change this template use File | Settings | File Templates.
 */
include_once(ROOT . '/lib/Tree.class.php');
include_once('Menu.class.php');
class ShopSections extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'listItems';
        $this->actions['list'] = 'listItems';
        $this->actions['add'] = 'add';
        $this->actions['edit'] = 'edit';
        $this->actions['get'] = 'edit';
        $this->actions['save'] = 'save';
        $this->actions['save_descr'] = 'saveDescription';
        $this->actions['del'] = 'delete';
        $this->actions['list_options'] = 'listOptions';
        $this->actions['save_option'] = 'saveOption';
        $this->actions['delete_option'] = 'deleteOption';
    }

    public function listItems()
    {
        $itemList = new DBCollection('sr_shop_section', array('id', 'title', 'cat_left', 'cat_right', 'cat_level'));
        $itemList->fetch('cat_level','cat_left asc');
        if($_GET['json']){
            $this->sendJSON(array('success'=>true,'sections'=>array_values($itemList->data)));
            return;
        }
        $smarty = Core::getSmarty();
        $smarty->assignByRef('items', $itemList->data);
        $smarty->assign('component', 'shop_sections');
//        print_r($itemList);
    }

    public function add()
    {
        $sef=Helpers::TranslitToURL($_POST['title']);
        $tree = $this->getTree();
        $result = $tree->insert((int)$_POST['parent'], array('title' => $_POST['title'],'sef'=>$sef));
        $this->sendJSON(array('success'=>!!$result,'sectionId'=>$result));
    }

    public function edit()
    {
        $section = new DBObject('sr_shop_section',(int)$_GET['id']);
        if($_GET['json']){
            $this->sendJSON(array('success'=>true,'section'=>$section->getAll()));
            return;
        }
        $smarty = Core::getSmarty();
        $smarty->assign('section',$section->getAll());
        $smarty->assign('editor_enable', true);
        $smarty->assign('component', 'shop_section_edit');
    }

    public function save()
    {
        $_POST['section']['sef']=trim($_POST['section']['sef']);
        if($_POST['section']['sef']==''){
            $_POST['section']['sef']=Helpers::TranslitToURL($_POST['section']['title']);
        }
        $section = new DBObject('sr_shop_section',(int)$_GET['id']);
        if (isset($_POST['section']['offer'])) {
            $this->updatePriceForPercentDiscount($section);
        }
        $section->set($_POST['section']);
        $section->save();
        $this->sendJSON(array('success'=>true,'section'=>$section->getAll()));
    }
    public function saveDescription()
    {
        $section = new DBObject('sr_shop_section',(int)$_GET['id']);
        $section->set('description',$_POST['description']);
        $section->save();
        $this->sendJSON(array('success'=>true,'section'=>$section->getAll()));
    }

    public function delete()
    {
        if (!$_POST['id']) return;
        $tree = $this->getTree();
        $categoriesToDelete = $this->getCategoriesToDelete($_POST['id']);
        echo $categoriesToDelete;
        if ($tree->deleteAll((int)$_POST['id'])) {
            $db = Core::getDB();
            $sql = "update sr_shop_product
                    set section_id = -1
                    where section_id in ({$categoriesToDelete})";
            return $db->query($sql);
        }
        return false;
    }

    public  function listOptions(){
        if (!$_GET['id']) return;
        $itemList = new DBCollection('sr_shop_option');
        $itemList->fetch('section_id='.(int)$_GET['id']);
        $this->sendJSON(array('success'=>true,'options'=>$itemList->data));
    }

    public function  saveOption(){
        if(!(int)$_GET['id'] && !$_POST['option']['section_id']){
            $this->sendJSON(array('success'=>false));
            return;
        }
        $option = new DBObject('sr_shop_option',(int)$_GET['id']);
        $option->set($_POST['option']);
        $option->save();
        if(!$option->id){
            $this->sendJSON(array('success'=>false));
            return;
        }
        $this->sendJSON(array('success'=>true,'option'=>$option->getAll()));
    }

    public function  deleteOption(){
        if(!(int)$_GET['id']){
            $this->sendJSON(array('success'=>false));
            return;
        }
        $option = new DBObject('sr_shop_option',(int)$_GET['id']);
        $option->delete();
        $this->sendJSON(array('success'=>true));
    }

    private function updatePriceForPercentDiscount($section)
    {
        $offerRate = trim($_POST['section']['offer']);
        $menu  = new Menu();
        $result = $menu->enumChildren($section->get('id'));
        $ids =  array();
        while ($row = mysql_fetch_assoc($result)) {
            //print_r($row);
            $ids[] = $row['id'];
        }
        $ids[] = $section->get('id');
        //print_r($ids);
        foreach ($ids as $id) {
            $products =  new DBCollection('sr_shop_product');
            $products->fetch('section_id='.$id);
            foreach ($products->data as $p) {
                $offer  = $p['price'] * ($offerRate/100);
                if ($offerRate == 0 && $p['has_percent_offer'] == 1) {
                    $sale_price = 0;
                    $hasPercentOffer = 0;
                    $sale = 0;
                } else if ($offerRate != 0 && $p['sale_price'] == 0) {
                    $sale_price = money_format('%.0n',($p['price'] -  $offer));
                    $hasPercentOffer = 1;
                    $sale = 1;
                } else {
                    continue;
                }
                $mp = new DBObject('sr_shop_product',$p['id']);
                $mp->set(array('sale_price' => $sale_price, 'has_percent_offer'=>$hasPercentOffer, 'sale'=>$sale));
                $mp->save();
                $this->updateStockAndPrice($mp->get('parent_id')=='0' ? $mp : $mp->get('parent_id'));
            }
        }

        //print_r($products); die();



    }

    private function getTree()
    {
        $cdb = new CDatabase(DB_NAME, DB_HOST, DB_USER, DB_PASS);
        return new CDBTree($cdb, 'sr_shop_section', 'id');
    }

    private function getCategoriesToDelete($id)
    {
        $tree = $this->getTree();
        $children = $tree->enumChildrenAll($id);
        $categories = array();
        $categories[] = $id;
        while ($row = mysql_fetch_assoc($children)) {
            $categories[] = $row['id'];
        }
        return implode(',', $categories);
    }

}
