<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
class Mailman extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'listItems';
        $this->actions['list'] = 'listItems';
        $this->actions['edit'] = 'edit';
        $this->actions['save'] = 'save';
        $this->actions['delete'] = 'delete';
        $this->actions['get_contacts'] = 'getSubscribers';
        $this->actions['send'] = 'send';
        $this->actions['finish_sending'] = 'finishSending';
    }

    public function listItems()
    {
        $mailList = new DBCollection('sr_mailman', array('id', 'subject', 'from', 'date','file'));
        $mailList->fetch('','`date` desc');
        $smarty = Core::getSmarty();
        $smarty->assignByRef('mail_list', $mailList->data);
        $smarty->assign('component', 'mailman_list');
    }

    public function edit()
    {
        $mail = new DBObject('sr_mailman', (int)$_GET['id']);
        $smarty = Core::getSmarty();
        $smarty->assignByRef('mail', $mail);
        $smarty->assign('editor_enable', true);
        $smarty->assign('component', 'mailman_edit');
    }

    public function save()
    {
        if (!$_POST['attributes']) {
            $this->listItems();
            return;
        }
        $letter = new DBObject('sr_mailman', (int)$_GET['id']);
        $letter->set($_POST['attributes']);
        $letter->save();
        if ($letter->id && $_FILES['file']['name']) {
            $file_name = ROOT . '/media/mailman/' . $letter->id . '.' . (array_pop(explode('.',$_FILES['file']['name'])));
            move_uploaded_file($_FILES['file']['tmp_name'], $file_name);
            $letter->set(array('file'=>$_FILES['file']['name']));
            $letter->save();
            /*if(file_exists($file_name)){
                $target = ROOT . '/media/mailman/' . $letter->id . '.base64';
                $rhandle = fopen($file_name,'r');
                stream_filter_append($rhandle, 'convert.base64-encode');
                $whandle = fopen($target,'w');
                stream_copy_to_stream($rhandle,$whandle);
                fclose($rhandle);
                fclose($whandle);
            }*/

        }
        $this->listItems();
    }

    public function delete()
    {

        if (!$_GET['id']) return;
        $letter = new DBObject('sr_mailman', (int)$_GET['id']);
        $filename = ROOT . '/media/mailman/' . $letter->id . '.' . array_pop(explode('.', $letter->get('file')));
        if(file_exists($filename)){
            unlink($filename);
        }
        $letter->delete();
        $this->listItems();
    }

    public function getSubscribers(){
        //Get subscribers
        $itemList = new DBCollection('sr_customer', array('id', 'name', 'mail'));
        $itemList->fetch('subscribe=1');
        $this->sendJSON(array('result'=>'success','data'=>array_values($itemList->data)));
    }

    public function send()
    {
        //ini_set('max_execution_time', 300);
        ob_start();
        include_once(ROOT . '/lib/Mailer.class.php');
        $subscribers = $_POST['subscribers'];
        //var_dump($itemList->data);
        $counter = 0;
        $successCounter = 0;
        $mailer = new Mailer(true);
        $letter = new DBObject('sr_mailman', (int)$_POST['id']);
        $mailer->Subject = $letter->get('subject');
        //Generate letter body
        $mailer->isHTML(true);
        if($letter->get('from')!=''){
            $mailer->From = $letter->get('from');
        }
        if($letter->get('file')!=''){
           // $base64file = ROOT . '/media/mailman/' . $letter->id . '.base64';
            $filename = ROOT . '/media/mailman/' . $letter->id . '.' . array_pop(explode('.', $letter->get('file')));
            /*if(file_exists($base64file)){
                $mailer->AddStringAttachment(file_get_contents($base64file), $letter->get('file'));
            } else {*/
                $mailer->AddAttachment($filename, $letter->get('file'));
            //}


        }
        //Send letter
        foreach ($subscribers as &$item) {
            $mailer->Body = $this->template($letter->get('subject'),$letter->get('body'),$item['id']);
            $mailer->AddAddress($item['mail'], $item['name']);
            if ($result = $mailer->Send()) {
                $successCounter++;
                $item['ok']=true;
            } else {
                $item['ok']=false;
            }
            $counter++;
            $mailer->ClearAddresses();
        }

        $result = array();
        if ($successCounter != $counter) {
            $result['message'] = "Не все письма отправлены успешно. Всего подписчиков: $counter. Отправлено писем: $successCounter.";
            $result['result'] = 'error';
        }
        else {
            $result['message'] = "Рассылка прошла успешно. Всего подписчиков: $counter. Отправлено писем: $successCounter.";
            $result['result'] = 'success';
            /**/
        }
        $result['data']=$subscribers;
        $result['raw_errors'] = ob_get_clean();
        $this->sendJSON($result);
    }
    public function finishSending(){
        $db = Core::getDB();
        $db->query('UPDATE sr_mailman SET date=? WHERE id=?', time(), (int)$_POST['id']);
        $this->sendJSON(array('result'=>'success'));
    }
    public function template($subject,$text,$contact_id){
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <title>'.$subject.'</title>
            <style type="text/css">
                html, body {
                    margin: 0;
                    padding: 0;
                }

                body {
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 14px;
                    color: #4c4c4c;
                    padding: 50px;
                }

                a img {
                    border: none;
                }

                h1, h2, h3 {
                    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                    color: #2796b6;
                }

                h1 {
                    font-size: 24px;
                    font-weight: normal;
                }

                p {
                    margin: 0;
                }
            </style>
        </head>
        <body>
        '.$text.'
        <div style="text-align: center; clear: both;"><a href="{$HTTP_ROOT}/remove_contact/'.$contact_id.'">Дла того чтобы отписаться от рассылки перейдите по этой ссылке</a></div>
        </body>
        </html>';
    }
}
