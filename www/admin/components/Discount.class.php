<?php
defined('RUN_CMS') or die('Restricted access');
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
class Discount extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'listItems';
        $this->actions['list'] = 'listItems';
        $this->actions['edit'] = 'edit';
        $this->actions['save'] = 'save';
        $this->actions['delete'] = 'delete';
    }
    //id, customer_id, code, type, discount, amount, customer_code, customer_name
    public function listItems()
    {
        $list = new DBCollection('sr_shop_discount');
        $filter = '1=1';
        $smarty = Core::getSmarty();
        if($_GET['name']){
            $filter.=" and `customer_name` LIKE '%".Helpers::mysql_escape($_GET['name'])."%'";
            $smarty->assign('name', $_GET['name']);
        }
        if($_GET['code']){
            $filter.=" and `code` LIKE '%".Helpers::mysql_escape($_GET['code'])."%'";
            $smarty->assign('code', $_GET['code']);
        }

        if($_GET['d_from']){
            $filter.=" and `discount`>=".(int)$_GET['d_from']."";
            $smarty->assign('d_from', $_GET['d_from']);
        }
        if($_GET['d_to']){
            $filter.=" and `discount`<=".(int)$_GET['d_to']."";
            $smarty->assign('d_to', $_GET['d_to']);
        }
        if($filter=='1=1'){
            $filter.=" and (`code` IS NULL or code='')";
        }
        $list->fetch($filter);
        $smarty->assignByRef('card_list', $list->data);
        $smarty->assign('component', 'discount');
    }

    public function save()
    {
        $data = array(
            'code'=>trim($_POST['code']),
            'type'=>trim($_POST['type']),
            'discount'=>(float)trim($_POST['discount']),
            'amount'=>(float)trim($_POST['amount']),
            'customer_code'=>trim($_POST['customer_code']),
            'customer_name'=>trim($_POST['customer_name'])
        );
        $d = new DBObject('sr_shop_discount', (int)$_POST['id']);
        $d->set($data);
        $d->save();
        $this->sendJSON(array(
            'success'=>true,
            'discount'=>$d->getAll()
        ));
    }

    public function delete()
    {
        if (!$_POST['id']) return;
        $d = new DBObject('sr_shop_discount', (int)$_POST['id']);
        $d->delete();
        $this->sendJSON(array('success'=>true));
    }

}
