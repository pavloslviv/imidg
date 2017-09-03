<?php
defined('RUN_CMS') or die('Restricted access');
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
class Guestbook extends Component
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
        $guestbookList = new DBCollection('sr_guestbook', array('id', 'client_name', 'client_mail', 'client_date', 'text'));
        $guestbookList->fetch('','client_date desc');
        $smarty = Core::getSmarty();
        $smarty->assignByRef('guestbook_list', $guestbookList->data);
        $smarty->assign('component', 'guestbook_list');
    }

    public function edit()
    {
        $guestbook = new DBObject('sr_guestbook', (int)$_GET['id']);
        $smarty = Core::getSmarty();
        $smarty->assignByRef('guestbook', $guestbook->getAll());
        $smarty->assign('component', 'guestbook_edit');
    }

    public function save()
    {
        if (!$_POST['attributes']) {
            $this->listItems();
            return;
        }

        $guestbook = new DBObject('sr_guestbook', (int)$_GET['id']);
        $_POST['attributes']['active']=$_POST['attributes']['active'] ? 1 : 0;
        if (!$_POST['attributes']['client_date']) $_POST['attributes']['client_date'] = time();
        else $_POST['attributes']['client_date'] = strtotime($_POST['attributes']['client_date']);

        if (!$_POST['attributes']['response_date']) $_POST['attributes']['response_date'] = time();
        else $_POST['attributes']['response_date'] = strtotime($_POST['attributes']['response_date']);

        $guestbook->set($_POST['attributes']);
        $guestbook->save();
        $this->listItems();
    }

    public function delete()
    {
        if (!$_GET['id']) return;
        $guestbook = new DBObject('sr_guestbook', (int)$_GET['id']);
        $guestbook->delete();
        $this->listItems();
    }
}
