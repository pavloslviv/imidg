<?php

class Lang
{

    public static $default = 'uk';
    public static $locale = array();
    public static $languages = array('ru', 'uk');
    public static $current = 'uk';
    //List of fields that should be translated
    public static $fields = array(
        'sr_articles' => array(
            'title',
            'title_full',
            'meta_title',
            'meta_descr',
            'meta_keyw',
            'brief',
            'text'
        ),
        'sr_menu' => array(
            'title',
            'url'
        ),
        'sr_page' => array(
            'title',
            'meta_title',
            'meta_descr',
            'meta_keyw',
            'text'
        ),
        'sr_settings' => array(
            'value'
        ),
        'sr_shop_option' => array(
            'title'
        ),
        'sr_shop_option_value' => array(
            'value'
        ),
        'sr_shop_product' => array(
            'title',
            'description',
            'meta_title',
            'meta_description',
            'meta_keywords'
        ),
        'sr_shop_section' => array(
            'title',
            'desciption',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'offer'
        ),
        'sr_slides' => array(
            'title',
            'link'
        ),

    );

    public static function regenerateFields(){
        $db = Core::getDB();
        foreach(self::$fields as $table=>$fieldList){
            foreach($fieldList as $fieldName){
                $defaultField = $db->selectRow('SHOW FIELDS FROM '.$table.' WHERE Field=?',$fieldName);
                if(!$defaultField['Field']){
                    echo "\n<br>DEFAULT FIELD NOT FOUND ERROR: ".$table.' '.$fieldName."<br>\n";
                    return;
                }
                foreach(self::$languages as $lang){
                    if($lang==self::$default){
                        continue;
                    }
                    $transFieldName = $lang."_".$fieldName;
                    $translatedField = $db->selectRow('SHOW FIELDS FROM '.$table.' WHERE Field=?',$transFieldName);
                    if($translatedField['Field']){
                        echo "\n<br>FIELD EXISTS: ".$table.' '.$transFieldName."<br>\n";
                        continue;
                    }
                    $db->query('ALTER TABLE  '.$table.' ADD  `'.$transFieldName.'` '.$defaultField['Type'].' NOT NULL');
                    $translatedField = $db->selectRow('SHOW FIELDS FROM '.$table.' WHERE Field=?',$transFieldName);
                    echo "\n<br>FIELD CREATED: <br>\n";
                }

            }

        }
    }

    public static function prepareField($field,$table,$lang){
        if($lang==self::$default){
            return $field;
        } else if(self::$fields[$table] && in_array($field,self::$fields[$table])){
            return $lang.'_'.$field;
        } else {
            return $field;
        }
    }
    public static function queryField($field,$table,$tAlias=null,$lang=null){
        if(!$lang){
            $lang=self::$current;
        }
        if($lang==self::$default){
            return ($tAlias ? $tAlias.'.`' :'`').$field.'`';
        } else if(self::$fields[$table] && in_array($field,self::$fields[$table])){
            $transField = ($tAlias ? $tAlias.'.`' :'`').$lang.'_'.$field.'`';
            $defField = ($tAlias ? $tAlias.'.`' :'`').$field.'`';
            return "IF($transField<>'',$transField,$defField)";
        } else {
            return '`'.$field.'`';
        }
    }

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


}

?>