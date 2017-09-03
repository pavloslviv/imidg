<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 25.12.11
 * Time: 18:19
 * To change this template use File | Settings | File Templates.
 */
class ArticleImage extends ImageObject
{
    protected $table = 'sr_articles';
    protected $flagField = 'image';
    protected $id;
    protected $directory = 'articles';
    protected $type = 'jpg';
    protected $copies = array('thumb' => '244x184','medium' => '470x355');
}
