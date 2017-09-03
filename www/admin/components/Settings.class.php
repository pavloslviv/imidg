<?php
defined('RUN_CMS') or die('Restricted access');
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
class Settings extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'listItems';
        $this->actions['list'] = 'listItems';
        $this->actions['map'] = 'getMap';
        $this->actions['discounts'] = 'getDiscounts';
        $this->actions['save'] = 'save';
        $this->actions['delete'] = 'delete';
    }

    public function listItems()
    {
        $list = new DBCollection('sr_settings');
        $list->fetch('','`section`');
        $smarty = Core::getSmarty();
        $smarty->assignByRef('list', $list->data);
        $smarty->assign('component', 'settings');
        $smarty->assign('editor_enable', true);
    }

    public function getMap()
    {
        $map = new DBObject('sr_settings');
        $map->fetch("section='shop' and name='map'");
        $smarty = Core::getSmarty();
        $smarty->assignByRef('map', $map->getAll());
        $smarty->assign('component', 'map');
    }

    public function getDiscounts()
    {
        $map = new DBObject('sr_settings');
        $map->fetch("section='shop' and name='discounts'");
        $smarty = Core::getSmarty();
        $smarty->assignByRef('discounts', $map->getAll());
        $smarty->assign('component', 'discounts');
    }

    public function save()
    {
        if($_POST['isJSON']){
            $value = json_encode(json_decode($_POST['value'],true));
        } else {
            $value = trim($_POST['value']);
        }
        $item = new DBObject('sr_settings');
        $item->fetch("`name`='".Helpers::mysql_escape($_POST['name'])."' and `section`='".Helpers::mysql_escape($_POST['section'])."'");
        $item->set('value',$value);
        $item->save();
        $this->sendJSON(array('result'=>'success'));
    }
}
