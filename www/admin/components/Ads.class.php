<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
class Ads extends Component
{
    public function __construct()
    {
        parent::__construct();
        include_once(ROOT . '/lib/VO/AdsImage.class.php');
    }

    protected function registerActions()
    {
        $this->actions['default'] = 'listItems';
        $this->actions['list'] = 'listItems';
        $this->actions['edit'] = 'edit';
        $this->actions['save'] = 'save';
        $this->actions['delete'] = 'delete';
    }

    public function listItems()
    {
        $itemList = new DBCollection('sr_slides', array('id', 'title', 'link', 'text', 'img'));
        $itemList->fetch();
        $smarty = Core::getSmarty();
        $smarty->assignByRef('items', $itemList->data);
        $smarty->assign('component', 'ads_list');
    }

    public function edit()
    {
        $item = new DBObject('sr_slides', (int)$_GET['id']);
        $smarty = Core::getSmarty();
        $smarty->assignByRef('item', $item);
        $smarty->assign('component', 'ads_edit');
    }

    public function save()
    {
        if (!$_POST['attributes']) {
            $this->listItems();
            return;
        }
        $item = new DBObject('sr_slides', (int)$_GET['id']);
        $item->set($_POST['attributes']);
        $item->save();
        $image = new AdsImage($item->id);
        if($image->upload('file')) $image->safeResize(940, 280);
        $this->listItems();
    }

    public function delete()
    {
        if (!$_GET['id']) return;
        $item = new DBObject('sr_slides', (int)$_GET['id']);
        $image = new AdsImage($item->id);
        $image->delete();
        $item->delete();
        $this->listItems();
    }
}
