<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 24.09.13
 * Time: 11:07
 * To change this template use File | Settings | File Templates.
 */

class Product extends DBObject{

    public $table = 'sr_shop_product';

    public function __construct($id){
        $this->id = $id;
        if ($this->id) $this->fetch();
    }
}