<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 25.12.11
 * Time: 18:19
 * To change this template use File | Settings | File Templates.
 */
class BrandImage extends ImageObject
{
    protected $table = 'sr_brand';
    protected $flagField = 'img';
    protected $id;
    protected $directory = 'brand';
    protected $type = 'jpg';
    protected $copies = array();
}
