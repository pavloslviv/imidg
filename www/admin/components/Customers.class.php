<?php
defined('RUN_CMS') or die('Restricted access');
include_once(ROOT . '/lib/VO/Customer.class.php');
class Customers extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'listItems';
        $this->actions['list'] = 'listItems';
        $this->actions['search'] = 'search';
        $this->actions['edit'] = 'edit';
        $this->actions['save'] = 'save';
        $this->actions['delete'] = 'delete';
        $this->actions['import'] = 'importCardList';
        $this->actions['export'] = 'exportCustomerList';
    }

    public function listItems()
    {
        //Core::debugDB();
        $pageList = new DBCollection('sr_customer', array('id', 'name', 'mail','phone'));
        $pageList->fetch();
        if($_REQUEST['json']){
            $this->sendJSON(array('success'=>true,'customers'=>array_values($pageList->data)));
        } else {
            $smarty = Core::getSmarty();
            $smarty->assignByRef('customers', $pageList->data);
            $smarty->assign('component', 'customer_list');
        }

    }

    public function search()
    {
        //Core::debugDB();
        $pageList = new DBCollection('sr_customer', array('id', 'name', 'mail','phone'));
        $pageList->fetch("`name` LIKE '%".Helpers::mysql_escape($_GET['query'])."%' or `mail` LIKE '%".Helpers::mysql_escape($_GET['query'])."%' or `phone` LIKE '%".Helpers::mysql_escape($_GET['query'])."%'");
        $this->sendJSON(array('success'=>true,'customers'=>array_values($pageList->data)));

    }

    public function edit()
    {
        $customer = new CustomerVO((int)$_GET['id']);
        $customerData = $customer->getAll();
        if($_REQUEST['json']){
            $this->sendJSON(array('success'=>true,'customer'=>$customerData));
        } else {
            $smarty = Core::getSmarty();
            $smarty->assignByRef('customer', $customerData);
            $smarty->assign('component', 'customer_edit');
        }
    }

    public function save()
    {
        if (!$_POST['customer']) {
            $this->edit();
            return;
        }
        //Core::debugDB();
        $data = $_POST['customer'];
        $customer_id = $_POST['id'];
        if ($data['pass'] != '') {
            $data['pass'] = md5($data['pass']);
        } else {
            unset($data['pass']);
        }

        if ($data['discount']) {
            $db = Core::getDB();
            $currentDiscount = $db->selectRow('SELECT * FROM `sr_shop_discount` WHERE `customer_id`=?',$customer_id);
            $newDiscount = $db->selectRow('SELECT * FROM `sr_shop_discount` WHERE `code`=?',$data['discount']);
            if($currentDiscount['id'] && $currentDiscount['id']==$newDiscount['id']){
                $this->sendJSON(array('result'=>'success','data'=>$currentDiscount));
                return;
            }
            if(!$newDiscount['id']){
                $this->sendJSON(array('result'=>'error',
                    'data'=>array(
                        'field'=>'discount',
                        'message'=>Lang::$locale['discount_card_not_found'],
                    )));
                return;
            }

            if($newDiscount['customer_id']!=0 && $newDiscount['customer_id']!=$customer_id){
                $this->sendJSON(array('result'=>'error',
                    'data'=>array(
                        'field'=>'discount-code',
                        'message'=>Lang::$locale['duplicate_discount'],
                    )));
                return;
            }
            //016142

            if($currentDiscount['id']){
                $db->query('UPDATE `sr_shop_discount` SET `customer_id`=0 WHERE `id`=?',(int)$currentDiscount['id']);
            }
            $db->query('UPDATE `sr_shop_discount` SET `customer_id`=? WHERE `id`=?',$customer_id,(int)$newDiscount['id']);
            $newDiscount['customer_id']=$data['discount'];

            unset($data['discount']);
        }
        $customer = new CustomerVO((int)$_REQUEST['id']);
        $customer->set($data);
        $customer->save();
        if($_REQUEST['json']){
            if($customer->id){
                $this->sendJSON(array('success'=>true,'customer'=>$customer->getAll()));
            } else {
                $this->sendJSON(array('success'=>false));
            }
        } else {
            $this->listItems();
        }
    }

    public function delete()
    {
        if (!$_GET['id']) return;
        $page = new CustomerVO((int)$_GET['id']);
        $page->delete();
        $this->listItems();
    }

    public function importCardList(){
        if (!$_FILES['file']['name']) {
            $this->sendJSON(array('result' => 'error', 'message' => 'Upload unsuccessful'));
            return;
        }
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        if (strtolower($ext)!='xml') {
            $this->sendJSON(array('result' => 'error', 'message' => 'File should be in XML format'));
            return;
        }
        $fileName = ROOT . '/media/files/' . time() . '.xml';
        $uploadResult = move_uploaded_file($_FILES['file']['tmp_name'], $fileName);
        if(!$uploadResult){
            $this->sendJSON(array('result' => 'error', 'message' => 'Upload unsuccessful'));
            return;
        }
        $db = Core::getDB();
        $existing = $db->select('SELECT  `code` AS ARRAY_KEY, sr_shop_discount . *
                                    FROM sr_shop_discount
                                    WHERE NOT  `code` IS NULL');
        $data = file_get_contents($fileName);
        $xml = new SimpleXMLElement($data);
        $newCount = 0;
        $updCount = 0;
        foreach($xml->card as $item){
            $cardData = array(
                'code'=>(string)$item->code,
                'type'=>(string)$item->type=='fixed' ? 'fixed' : 'cumulative',
                'discount'=>(float)$item->discount,
                'amount'=>(float)$item->amount,
                'customer_code'=>(string)$item->customer_code,
                'customer_name'=>(string)$item->customer_name,
            );

            if($existing[$cardData['code']]){
                //Check diff with exitsing data to avoid unnecesary queries
                $existingData = $existing[$cardData['code']];
                unset($existingData['id']);
                unset($existingData['customer_id']);
                $existingData['discount']=(float)$existingData['discount'];
                $existingData['amount']=(float)$existingData['amount'];
                $updatedFields = array_diff_assoc($cardData,$existingData);
                if(!count($updatedFields)){
                    continue;
                }
                $db->query('UPDATE sr_shop_discount SET ?a WHERE `id`=?', $updatedFields,(int)$existing[$cardData['code']]['id']);
                $updCount++;
            } else {
                $db->query(
                    'INSERT INTO sr_shop_discount (?#) VALUES(?a)',
                    array_keys($cardData),
                    array_values($cardData)
                );
                $newCount++;
            }
        }
        $this->sendJSON(array('result' => 'success', 'data' => array('new'=>$newCount,'update'=>$updCount)));
    }

    public function exportCustomerList(){
        $headings = array('User id', 'Email', 'Name', 'Phone', 'Address', 'Subscriber', 'Discount code', 'Discount name', 'Discount, %', 'Discount amount, UAH');
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        header("Content-Type: application/octet-stream");
        $filename = 'customers_' . date('Ymd') .'_' . date('His');
        header("Content-Disposition: attachment; filename=\"$filename.csv\";" );
        header("Content-Transfer-Encoding: binary");
        $db = Core::getDB();
        $db->query('SET names cp1251');
        $db->query("SET character_set_client='cp1251'");
        $db->query("SET character_set_results='cp1251'");
        $db->query("SET collation_connection='cp1251_general_ci'");
        //	id mail name phone address subscribe
        $customers = $db->select('SELECT c.id, c.mail, c.name, c.phone, c.address, c.subscribe, d.customer_code, d.customer_name, d.discount, d.amount
                                  FROM `sr_customer` AS c LEFT OUTER JOIN `sr_shop_discount` AS d ON c.id = d.customer_id');

        // Open the output stream
        $fh = fopen('php://output', 'w');

        fputcsv($fh, $headings,';');

        foreach($customers as $customer){
            $customer['subscribe']= (int)$customer['subscribe'] ? 'yes' : '';
            fputcsv($fh, $customer,';');
        }



        exit();
    }

}
