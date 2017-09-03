<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 30.06.12
 * Time: 11:46
 * To change this template use File | Settings | File Templates.
 */
class CustomerVO extends DBObject
{

    public function __construct($id = null)
    {
        $this->table = 'sr_customer';
        $this->id = $id;
        if ($this->table && $this->id) $this->fetch();
        //TODO: Check what fields have table
        /*$db = Core::getDB();
        $fields = $db->select('show columns from ?',$table);
        while(){

        }*/
    }

    public function getAll()
    {
        $result=$this->_attributes;
        $result['id']=$this->id;
        unset($result['pass']);
        $db = Core::getDB();
        $discount = $db->selectRow("select code, discount, type, amount from sr_shop_discount where customer_id = '{$this->id}' limit 1");
        $result['discount_code'] = $discount['code'];
        $result['discount_discount'] = $discount['discount'];
        $result['discount_type'] = $discount['type'];
        $result['discount_amount'] = $discount['amount'];
        return $result;
    }

}
