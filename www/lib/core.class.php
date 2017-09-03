<?php

defined('RUN_CMS') or die('Restricted access');
// Initialize Smarty
require('smarty/Smarty.class.php');
//DB initialization
require('DbSimple/Generic.php');
require('DBObject.class.php');
require('DBCollection.class.php');
require('Component.class.php');
require('ImageObject.class.php');
require('Helpers.class.php');
require('Lang.class.php');
require('shop_config.php');
// Magic Quotes Fix
if (ini_get('magic_quotes_gpc')) {
    function clean($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[clean($key)] = clean($value);
            }
        } else {
            $data = stripslashes($data);
        }

        return $data;
    }

    $_GET = clean($_GET);
    $_POST = clean($_POST);
    $_REQUEST = clean($_REQUEST);
    $_COOKIE = clean($_COOKIE);
}

if (!ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}

class Core
{

    protected static $smarty; // object instance
    protected static $db; // object instance
    protected static $dbh; //pdo instance
    protected static $cityList;
    protected static $settings;
    public static $path;
    public static $breadcrumbs=array();
    /**
     * Защищаем от создания через new Core
     *
     * @return Singleton
     */

    private function __construct()
    {

    }

    /**
     * Защищаем от создания через клонирование
     *
     * @return Singleton
     */
    private function __clone()
    { /* ... */
    }

    /**
     * Возвращает единственный экземпляр класса
     *
     * @return Smarty
     */
    public static function getSmarty()
    {
        if (is_null(self::$smarty)) {
            self::$smarty = new Smarty;
            self::$smarty->setTemplateDir(ROOT . '/template');
            self::$smarty->setCompileDir(ROOT . '/lib/smarty/compiled');
            self::$smarty->setCacheDir(ROOT . '/lib/smarty/cache');
            self::$smarty->setConfigDir(ROOT . '/lib/smarty/configs');
            self::$smarty->assign('httpRoot', HTTP_ROOT);
            self::$smarty->caching = false;
        }
        return self::$smarty;
    }

    /**
     * Возвращает единственный экземпляр класса
     *
     * @return DbSimple_Mysql
     */
    public static function getDB($dbh = null)
    {
        if ($dbh) {
            return self::getPdo();
        }

        if (is_null(self::$db)) {
            self::$db = DbSimple_Generic::connect('mysqli://'.DB_USER.':'.DB_PASS.'@'.DB_HOST.'/'.DB_NAME);

        }
        return self::$db;
    }

    public static function getSettings($section,$name=false){
        if (is_null(self::$settings)){
            self::$settings = array();
            $db = self::getDB();
            $rows = $db->select('SELECT `section`,`name`,'.Lang::queryField('value','sr_settings').' as value FROM sr_settings');
            foreach($rows as $row){
                if (!self::$settings[$row['section']]){
                    self::$settings[$row['section']]=array();
                }
                self::$settings[$row['section']][$row['name']]=$row['value'];
            }
        }
        if (!$name){
            return self::$settings[$section];
        } else {
            return self::$settings[$section][$name];
        }

    }

    public static function debugDB($switch = true)
    {
        if ($switch) {

            function myLogger($db, $sql)
            {
                // Находим контекст вызова этого запроса.
                //$caller = $db->findLibraryCaller();
                //$tip = "at " . @$caller['file'] . ' line ' . @$caller['line'];
                // Печатаем запрос (конечно, Debug_HackerConsole лучше).
                //echo "<xmp title="{$tip}">";
                print_r($sql);
                echo "</xmp>";
            }
        }
        else {
            function myLogger($db, $sql)
            {
            }
        }
        self::$db->setLogger('myLogger');
    }

    public static function log($msg)
    {
        $msg=strftime('%Y.%m.%d %H:%M:%S> ').$msg."\n";
        if ($fh = @fopen(ROOT."/logfile.txt", "a+")) {
            fputs($fh, $msg, strlen($msg));
            fclose($fh);
            return (true);
        } else {
            return (false);
        }

    }

    public static function send404() {
        $smarty = self::getSmarty();
        header("HTTP/1.0 404 Not Found");
        $smarty->assign('meta_title', 'Сторінку не знайдено');
        $smarty->assign('meta_descr', '');
        $smarty->assign('meta_keyw', '');
        $smarty->assign('component', 'page404');
        $smarty->display('index.tpl');
        exit();
    }

    public static function getPdo()
    {
        if (is_null(self::$dbh)){
            self::$dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME .";charset=UTF8", DB_USER, DB_PASS);
            self::$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        return self::$dbh;
    }
}