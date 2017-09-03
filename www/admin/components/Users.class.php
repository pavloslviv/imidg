<?php
defined('RUN_CMS') or die('Restricted access');
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
if ($_SESSION['user']['level'] != 'admin') die('Restricted access!!!');
class Users extends Component
{
    public function __construct()
    {
        parent::__construct();
        include_once(ROOT . '/lib/VO/UserPhoto.class.php');
        include_once(ROOT . '/lib/VO/UserPhoto.class.php');
    }

    protected function registerActions()
    {
        $this->actions['default'] = 'listItems';
        $this->actions['list'] = 'listItems';
        $this->actions['save'] = 'save';
        $this->actions['delete'] = 'delete';
        $this->actions['upload'] = 'uploadPhoto';
    }

    public function listItems()
    {
        $companyList = new DBCollection('sr_user');
        $companyList->fetch('', '`level` asc, `full_name` asc');
        foreach($companyList->data as &$item){
            unset($item['pass']);
        }
        $smarty = Core::getSmarty();
        $smarty->assignByRef('users', $companyList->data);
        $smarty->assign('component', 'users');
    }

    public function save()
    {
        $table = 'sr_user';
        $data = array(
            'username' => $_POST['username'],
            'full_name' => $_POST['full_name'],
            'phone' => $_POST['phone'],
            'mail' => $_POST['mail']
        );
        if ($_POST['pass']!=''){
            $data['pass']=md5($_POST['pass']);
        }
        $obj = new DBObject($table, (int)$_POST['id']);
        $obj->set($data);
        $obj->save();
        $obj->fetch();
        $this->sendJSON(array('result' => 'success', 'data' => $obj->getAll()));
    }

    public function uploadPhoto()
    {
        if (!(int)$_POST['id']) {
            $this->sendJSON(array('result' => 'error', 'message' => 'User ID not specified'));
            return;
        }
        $image = new UserPhoto((int)$_POST['id']);
        $image->upload('file');
        $image->safeResize(256, 256);
        $image->makeThumbs();
        $this->sendJSON(array('result' => 'success', 'url' => $image->getFullURL(), 'thumb_url' => $image->getFullURL('small')));
    }

    public function delete()
    {
        if (!$_GET['id'] || $_GET['id']==1) {
            $this->sendJSON(array('result' => 'error', 'message' => 'User ID not specified'));
            return;
        }
        $item = new DBObject('sr_user', (int)$_GET['id']);
        $image = new UserPhoto($item->id);
        $image->delete();
        $item->delete();
        if ($_POST['mode']=='ajax'){
            $this->sendJSON(array('result' => 'success'));
        }
        else {
            $this->listItems();
        }
    }

    public function sendJSON($data)
    {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    }
}
