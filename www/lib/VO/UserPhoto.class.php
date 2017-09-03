<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 25.12.11
 * Time: 18:19
 * To change this template use File | Settings | File Templates.
 */
class UserPhoto extends ImageObject
{
    protected $table = 'sr_user';
    protected $flagField = 'photo';
    protected $id;
    protected $directory = 'users';
    protected $type = 'jpg';
    protected $copies = array('middle'=>'128x128','small'=>'64x64');
}
