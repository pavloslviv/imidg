<?php
defined('RUN_CMS') or die('Restricted access');
/**
 * Created by JetBrains PhpStorm.
 * User: Serg
 * Date: 03.01.12
 * Time: 7:28
 * To change this template use File | Settings | File Templates.
 */
include_once (ROOT . '/lib/Tree.class.php');
class Menu extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'listItems';
        $this->actions['list'] = 'listItems';
        $this->actions['new'] = 'newItem';
        $this->actions['save'] = 'save';
        $this->actions['del'] = 'delete';
        $this->actions['add'] = 'add';
        $this->actions['edit'] = 'edit';
        $this->actions['enumChildren'] = 'enumChildren';
    }

    public function listItems()
    {
        $itemList = new DBCollection('sr_menu', array('id', 'title', 'cat_left', 'cat_right', 'cat_level'));
        $itemList->fetch('','cat_left asc');
        $smarty = Core::getSmarty();
        $smarty->assignByRef('items', $itemList->data);
        //var_dump($itemList->data);
        $smarty->assign('component', 'menu');
    }
    public function newItem()
    {
        $smarty = Core::getSmarty();
        $pageList = new DBCollection('sr_page',array('id','title'));
        $pageList->fetch('','title asc');
        $smarty->assignByRef('pages', $pageList->data);
        $smarty->assign('item',array('page_id'=>0,'title'=>'','parent_id'=>(int)$_GET['id']));
        $smarty->display('blocks/menu_form.tpl');
        exit();
    }

    public function enumChildren($id)
    {
        $cdb = new CDatabase(DB_NAME, DB_HOST, DB_USER, DB_PASS);
        $tree = new CDBTree($cdb, 'sr_shop_section', 'id');
        return $tree->enumChildrenAll($id);

    }

    public function edit()
    {
        $smarty = Core::getSmarty();
        $pageList = new DBCollection('sr_page',array('id','title'));
        $pageList->fetch('','title asc');
        $smarty->assignByRef('pages', $pageList->data);
        $item = new DBObject('sr_menu',(int)$_GET['id']);
        $smarty->assign('item',$item->getAll());
        $smarty->display('blocks/menu_form.tpl');
        exit();
    }

    public function add()
    {
        $tree = $this->getTree();
        $tree->insert((int)$_POST['parent_id'],
            array('title' => $_POST['title'],
                'page_id' => $_POST['page_id'],
                'url' => $_POST['url']));
        $this->listItems();
    }

    public function save()
    {
        $item = new DBObject('sr_menu',(int)$_POST['id']);
        $item->set(array(
            'title' => $_POST['title'],
            'page_id' => $_POST['page_id'],
            'url' => $_POST['url']
        ));
        $item->save();
        $this->listItems();
    }

    public function delete()
    {
        if (!$_GET['id']) return;
        $tree = $this->getTree();
        $tree->deleteAll((int)$_GET['id']);
        $this->listItems();
    }

    private function moveItem($direction, $id)
    {
        $itemFrom = new DBObject('sr_menu', $id);
        $db = Core::getDB();
        if ($direction == 'up') {
            $catRight = (int)$itemFrom->get('cat_left') - 1;
            $itemRow = $db->selectRow('SELECT id FROM sr_menu WHERE cat_right=? and cat_level=?', $catRight, $itemFrom->get('cat_level'));
        }
        elseif ($direction == 'down')
        {
            $catLeft = (int)$itemFrom->get('cat_right') + 1;
            $itemRow = $db->selectRow('SELECT id FROM sr_menu WHERE cat_left=? and cat_level=?', $catLeft, $itemFrom->get('cat_level'));
        }
        else {
            return false;
        }
        $itemTo = new DBObject('sr_menu', $itemRow['id']);
        if (!$itemTo->id) return false;
        $idTo = $rowTo['id'];
        if ($itemFrom->get('cat_right') - $itemFrom->get('cat_left') > 1) {
            $childrenFrom = $db->selectCol('SELECT id FROM sr_menu WHERE cat_left>? and cat_right<? and cat_level=?', $itemFrom->get('cat_left'), $itemFrom->get('cat_right'), $itemFrom->get('cat_level') + 1);
        }
        if ($itemTo->get('cat_right') - $itemTo->get('cat_left') > 1) {
            $childrenTo = $db->selectCol('SELECT id FROM sr_menu WHERE cat_left>? and cat_right<? and cat_level=?', $itemTo->get('cat_left'), $itemTo->get('cat_right'), $itemTo->get('cat_level') + 1);
        }

        if (isset($childrenFrom) || isset($childrenTo)) {
            $tree = $this->getTree();
        }
        if (isset($childrenFrom)) {
            foreach ($childrenFrom as $moveId)
            {
                $tree->moveAll($moveId, $idTo);
            }
        }

        if (isset($childrenTo)) {
            foreach ($childrenTo as $moveId)
            {
                $tree->moveAll($moveId, $idFrom);
            }
        }

        $temp = $itemTo->getAll();
        $itemTo->set($itemFrom->getAll());
        $itemFrom->set($temp);
        $itemFrom->save();
        $itemTo->save();
    }

    private function getTree()
    {
        $cdb = new CDatabase(DB_NAME, DB_HOST, DB_USER, DB_PASS);
        return new CDBTree($cdb, 'sr_menu', 'id');
    }


}
