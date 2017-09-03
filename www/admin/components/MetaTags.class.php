<?php

defined('RUN_CMS') or die('Restricted access');

class MetaTags extends Component
{

    protected function registerActions()
    {
        $this->actions['default'] = 'showList';
        $this->actions['list'] = 'showList';
        $this->actions['edit'] = 'get';
        $this->actions['save'] = 'save';
        $this->actions['delete'] = 'del';
        $this->actions['add'] = 'add';
    }
    
    public function add(){
        $db = Core::getDB();
        $newUrlId = $db->query('INSERT INTO `sr_meta_tag` (`url`) VALUES (?)',$_POST['new_url']);
        $newRow = $db->selectRow('SELECT * FROM `sr_meta_tag` WHERE id=?',$newUrlId);
        if($newRow){
            $this->sendJSON(array('result'=>'success','data'=>$newRow));
        } else {
            $this->sendJSON(array('result'=>'error','data'=>null));
        }
    }

    public function get(){
        $db = Core::getDB();
        $row = $db->selectRow('SELECT * FROM `sr_meta_tag` WHERE id=?',(int)$_GET['id']);
        if($row){
            $this->sendJSON(array('result'=>'success','data'=>$row));
        } else {
            $this->sendJSON(array('result'=>'error','data'=>null));
        }
    }

    public function showList()
    {
        $db = Core::getDB();
        $smarty = Core::getSmarty();
        $list = $db->select('SELECT id AS ARRAY_KEY,id,url,title FROM `sr_meta_tag` ORDER BY url asc');
        $smarty->assign('list', json_encode($list));
        $smarty->assign('editor_enable',true);
        $smarty->assign('component', 'meta_tags');
    }

    public function save()
    {
        $params = $_POST['meta'];
        $id = $params['id'];
        unset($params['id']);
        $db = Core::getDB();
        $db->query('UPDATE `sr_meta_tag` SET ?a WHERE id=?', $params,$id);
        $row = $db->selectRow('SELECT * FROM `sr_meta_tag` WHERE id=?',$id);
        if($row){
            $this->sendJSON(array('result'=>'success','data'=>$row));
        } else {
            $this->sendJSON(array('result'=>'error','data'=>null));
        }
    }
    public function del(){
        $db = Core::getDB();
        $res = $db->query('DELETE FROM `sr_meta_tag` WHERE id=?',$_GET['id']);
        if($res){
            $this->sendJSON(array('result'=>'success','data'=>null));
        } else {
            $this->sendJSON(array('result'=>'error','data'=>null));
        }
    }
}