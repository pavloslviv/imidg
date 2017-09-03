<?php
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
        $this->actions['post'] = 'post';
    }

    public function run($action = null)
    {
        if($_GET['action']) $this->{$_GET['action']}();
        elseif (!Core::$path[1] || preg_match('`page-\d+`', Core::$path[1])) $this->listItems();
        else $this->{$this->actions['default']}();
    }

    public function listItems()
    {
        //Core::debugDB(true);
        Core::$breadcrumbs['/guest_book']=Lang::$locale['guest_book'];
        $itemList = new DBCollection('sr_guestbook', array('id','client_name','text','client_date','response_text','response_date'));
        $itemList->fetchPage((int)str_replace('page-','',Core::$path[1]),'`active`=1','`client_date` desc');
        $smarty = Core::getSmarty();
        $smarty->assign('page_count', $itemList->pagesCount);
        $smarty->assign('page_current', $itemList->currentPage);
        $smarty->assignByRef('items', $itemList->data);
        $smarty->assign('component', 'guestbook');

    }

    public function post()
    {
        if ($_POST['client_name']==''){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'client_name',
                    'message'=>Lang::$locale['enter_your_name']
                )));
        }

        if (!filter_var($_POST['client_mail'], FILTER_VALIDATE_EMAIL)){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'client_mail',
                    'message'=>Lang::$locale['enter_correct_email']
                )));
        }

        if ($_POST['text']==''){
            $this->sendJSON(array('result'=>'error',
                'data'=>array(
                    'field'=>'text',
                    'message'=>Lang::$locale['enter_feedback_text']
                )));
        }

        $post = new DBObject('sr_guestbook');
        $post->set(array(
            'client_name'=>htmlspecialchars($_POST['client_name']),
            'client_mail'=>htmlspecialchars($_POST['client_mail']),
            'text'=>htmlspecialchars($_POST['text']),
            'client_date'=>time(),
            'active'=>0
        ));
        $post->save();
        if($post->id){
            $this->sendJSON(array('result'=>'success'));
        } else {
            $this->sendJSON(array(
                'result'=>'error',
                'message'=>Lang::$locale['unknown_error']
            ));
        }
    }

}
