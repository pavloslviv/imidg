<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 21.01.12
 * Time: 14:18
 * To change this template use File | Settings | File Templates.
 */
class Helpers
{
    static function TranslitToURL($var)
    {
        $var = trim($var);
        $array_find = array('&quot;', ' ', '/', '\\', '$', '^', '*', '(', ')', '%', '<', '>', '|', '[', ']', '"', ':', ',', '.', '@', '&');
        $array_repl = array('', '_', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'a', '');
        $var = str_replace($array_find, $array_repl, $var);

        $array_find = array("а", "б", "в", "г", "д", "е", "з", "и", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "ц", "ъ", "ы", "ь");
        $array_repl = array("a", "b", "v", "g", "d", "e", "z", "i", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "с", "", "y", "");
        $var = str_replace($array_find, $array_repl, $var);

        $array_find = array("А", "Б", "В", "Г", "Д", "Е", "З", "И", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Ц", "Ъ", "Ы", "Ь");
        $array_repl = array("a", "b", "v", "g", "d", "e", "z", "i", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "с", "", "y", "");
        $var = str_replace($array_find, $array_repl, $var);

        $array_find = array("э", "х", "й", "ё", "ж", "ч", "ш", "щ", "ю", "я", "Э", "Х", "Й", "Ё", "Ж", "Ч", "Ш", "Щ", "Ю", "Я");
        $array_repl = array("eh", "kh", "jj", "jo", "zh", "ch", "sh", "shh", "ju", "ja", "eh", "kh", "jj", "jo", "zh", "ch", "sh", "shh", "ju", "ja");
        $var = str_replace($array_find, $array_repl, $var);
        $var = preg_replace("/[^a-zA-Z0-9_-]/", '', $var);
        return $var;
    }

    static function array_pluck($array,$neededKey)
    {
        if (is_array($neededKey) || !is_array($array)) return array();
        $result = array();
        foreach($array as $key=>$childArray){
            if($childArray[$neededKey]) array_push($result,$childArray[$neededKey]);
        }
        return $result;
    }
    static function mysql_escape($string){
        $db = Core::getDB();
        return mysqli_real_escape_string($db->link,$string);
    }
}
