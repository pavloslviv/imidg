<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 25.12.11
 * Time: 18:19
 * To change this template use File | Settings | File Templates.
 */
class AdsImage extends ImageObject
{
    protected $table = 'sr_slides';
    protected $flagField = 'img';
    protected $id;
    protected $directory = 'blocks';
    protected $type = 'jpg';
    protected $copies = array();
}
