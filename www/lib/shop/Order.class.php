<?php

class Order extends DBObject{

    public $table = 'sr_shop_order';
    public $id;
    public $userId;
    public $items = array();

    public function __construct($id){
        $this->id = $id;
        if ($this->id) $this->fetch();
    }

    public function fetch(){
        if(parent::fetch()){
            return $this->loadItems();
        } else {
            return false;
        }
    }

    public function loadItems(){
        if(!$this->id) return false;
        $items = new DBCollection('sr_shop_order_item');
        $result = $items->fetch('order_id='.$this->id);
        $this->items = $items->data;
        return $result;
    }

    public function updateSum(){
        $total = 0;
        foreach($this->items as $itemId=>$item){
            $total+=$item['price']*$item['qty'];
        }
        $this->set('total',$total);
    }

}