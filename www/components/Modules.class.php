<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
 class  Modules
{
     public static function getMenus()
    {
        $itemList = new DBCollection('sr_menu', array('id', 'title', 'page_id', 'cat_left', 'cat_right', 'cat_level', 'url'));
        $itemList->fetch('', 'cat_left asc');
        $pageList = new DBCollection('sr_page', array('id', 'sef'));
        $pageList->fetch();
        $menus = array();
        foreach ($itemList->data as $id => $item) {
            if ($item['cat_level'] == 1) {
                $menus[$id] = array();
                $menuId = $id;
            }
            elseif ($item['cat_level'] == 2) {
                if ($pageList->data[$item['page_id']]) {
                    $item['url'] = (Lang::$current==Lang::$default ? '' : '/'.Lang::$current).'/page/' . $pageList->data[$item['page_id']]['sef'];
                }
                if($item['url']!=''){
                    if(($item['url']!='/' && strpos($_SERVER['REQUEST_URI'], $item['url']) !== false) || ($item['url']==$_SERVER['REQUEST_URI'])){
                        $item['active']=true;
                    }
                }
                $menus[$menuId][$id] = $item;
                $parentId = $id;
            }
            elseif ($item['cat_level'] == 3) {
                if ($pageList->data[$item['page_id']]) {
                    $item['url'] = (Lang::$current==Lang::$default ? '' : '/'.Lang::$current).'/page/' . $pageList->data[$item['page_id']]['sef'];
                }
                if($item['url']!=''){
                    if(($item['url']!='/' && strpos($_SERVER['REQUEST_URI'], $item['url']) !== false) || ($item['url']==$_SERVER['REQUEST_URI'])){
                        $item['active']=true;
                    }
                }
                $menus[$menuId][$parentId]['subitems'][$id] = $item;

                $subParentId = $id;
            }
            else {
                if ($pageList->data[$item['page_id']]) {
                    $item['url'] = '/page/' . $pageList->data[$item['page_id']]['sef'];
                }
                if($item['url']!=''){
                    if(($item['url']!='/' && strpos($_SERVER['REQUEST_URI'], $item['url']) !== false) || ($item['url']==$_SERVER['REQUEST_URI'])){
                        $item['active']=true;
                    }
                }
                $menus[$menuId][$parentId]['subitems'][$subParentId]['subitems'][$id] = $item;
            }
        }
        $smarty = Core::getSmarty();
        $smarty->assignByRef('menus', $menus);
        self::getSectionMenu();
    }

    public static function getSectionMenu(){
        $sectionList = new DBCollection('sr_shop_section', array('id', 'title', 'sef','cat_left','cat_right','cat_level'));
        $sectionList->fetch('`cat_level` = 1', 'cat_left asc');
        $smarty = Core::getSmarty();
        foreach ($sectionList->data as $k => $v) {
            $sectionList->data[$k]['children'] = self::getSubsectionMenu($v);
        }
        $smarty->assignByRef('sections', $sectionList->data);
    }

    public static function addSubsections($sectionId,$subsections){
        $smarty = Core::getSmarty();
        $sections = $smarty->getTemplateVars('sections');
        if(!$sections) return;
        $sections[$sectionId]['children'] = $subsections;
        $smarty->assignByRef('sections', $sections);
    }

     public static function getSubsectionMenu($section){
         $subcategories = new DBCollection('sr_shop_section');
         $subcategories->fetch('`cat_left`>'.$section['cat_left'].' and `cat_right`<'.$section['cat_right']);
         return $subcategories->data;
     }

     public static function getBrands()
     {
         $itemList = new DBCollection('sr_brand', array('id', 'title', 'link', 'text', 'img'));
         $itemList->fetch('img=1','RAND()');
         $smarty = Core::getSmarty();
         $smarty->assignByRef('brand', $itemList->data);
     }
}
