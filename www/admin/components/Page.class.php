<?php
defined('RUN_CMS') or die('Restricted access');
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
class Page extends Component
{
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
        $pageList = new DBCollection('sr_page', array('id', 'title', 'sef'));
        $pageList->fetch('','`title` asc');
        $smarty = Core::getSmarty();
        $smarty->assignByRef('pages', $pageList->data);
        $smarty->assign('component', 'page_list');
    }

    public function edit()
    {
        $page = new DBObject('sr_page', (int)$_GET['id']);
        $smarty = Core::getSmarty();
        $smarty->assignByRef('page', $page);
        $smarty->assign('editor_enable',true);
        $smarty->assign('component', 'page_edit');
    }

    public function save()
    {
        if (!$_POST['attributes']) {
            $this->listItems();
            return;
        }
        $page = new DBObject('sr_page', (int)$_GET['id']);
        $_POST['attributes']['sef']=Helpers::TranslitToURL($_POST['attributes']['sef']?$_POST['attributes']['sef']:$_POST['attributes']['title']);
        $page->set($_POST['attributes']);
        $page->save();
        if($page->get('sef')=='') {
            $page->set(array('sef'=>$page->id));
            $page->save();
        }
        $this->listItems();
    }
    public function delete(){
        if (!$_GET['id']) return;
        $page = new DBObject('sr_page', (int)$_GET['id']);
        $page->delete();
        $this->listItems();
    }
}
