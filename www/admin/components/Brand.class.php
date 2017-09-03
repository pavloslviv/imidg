<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
class Brand extends Component
{
    public function __construct()
    {
        parent::__construct();
        include_once(ROOT . '/lib/VO/BrandImage.class.php');
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
        $itemList = new DBCollection('sr_brand', array('id', 'title', 'link', 'text', 'img'));
        $itemList->fetch();
        $smarty = Core::getSmarty();
        $smarty->assignByRef('items', $itemList->data);
        $smarty->assign('component', 'brand_list');
    }

    public function edit()
    {
        $item = new DBObject('sr_brand', (int)$_GET['id']);
        $smarty = Core::getSmarty();
        $smarty->assignByRef('item', $item);
        $smarty->assign('component', 'brand_edit');
    }

    public function save()
    {
        if (!$_POST['attributes']) {
            $this->listItems();
            return;
        }
        $item = new DBObject('sr_brand', (int)$_GET['id']);
        $item->set($_POST['attributes']);
        $item->save();
        $image = new BrandImage($item->id);
        if($image->upload('file')) $image->safeResize(300, 60);
        $this->listItems();
    }

    public function delete()
    {
        if (!$_GET['id']) return;
        $item = new DBObject('sr_brand', (int)$_GET['id']);
        $image = new BrandImage($item->id);
        $image->delete();
        $item->delete();
        $this->listItems();
    }
}
