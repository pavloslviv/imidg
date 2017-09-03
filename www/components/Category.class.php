<?php
defined('RUN_CMS') or die('Restricted access');
/**
 * Created by JetBrains PhpStorm.
 * User: Serg
 * Date: 03.01.12
 * Time: 7:28
 * To change this template use File | Settings | File Templates.
 */
class Category extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'listItems';
        $this->actions['list'] = 'listItems';
    }

    public function listItems()
    {
        $section = $this->getSection();
        //Emulate filters for brand urls
        if(!$_REQUEST['filters'] && Core::$path[2] && strpos(Core::$path[2],'page-')===false){
            $filter = str_replace('_',' ', urldecode(Core::$path[2]));
            $_REQUEST['filters']=array();
            $sectionId =  ($section['cat_level'] > 1) ? $section['top_section']['id'] : $section['id'];

            //витягую з бази даних ід фільтру по ід рубрики
            $brandFilterId = $this->getBrandFilterId($sectionId);
            //$_REQUEST['filters'][ShopCore::$brandOptions[$section['id']]] = array($filter);
            $_REQUEST['filters'][$brandFilterId] = array($filter);
        }

        $products = $this->getProducts($section);
        if($products){
            //Fill price, stock and sale
            foreach($products as &$product){
                $this->setTypes($product);
            }
        }
        //Breadcrumbs
        Core::$breadcrumbs['/category/'.$section['sef']]=$section['title'];

        //Assign data to smarty
        $smarty = Core::getSmarty();
        $smarty->assign('meta_title', $section['meta_title'] ? $section['meta_title'] : $section['title']);
        $smarty->assign('meta_descr', $section['meta_descr']);
        $smarty->assign('meta_keyw', $section['meta_keyw']);
        $smarty->assign('section', $section);
        $smarty->assign('filters', $this->generateFilter($section));
        $smarty->assign('priceFilter', $this->generatePriceFilter($section));
        $smarty->assign('products', $products);
        $smarty->assign('component', 'category');
    }

    private function getProducts($section){
        $itemPerPage = 16;
        $db = Core::getDB();
        //Core::debugDB();
        $totalRows = 0;
        $currentPage = (int)str_replace('page-', '', Core::$path[2]);
        if($currentPage<1) $currentPage=1;
        $offset = $itemPerPage * ($currentPage - 1);
        $sql = 'SELECT DISTINCT
                    p.id as ARRAY_KEY,
                    p.id,
                    p.parent_id,
                    '.Lang::queryField('title','sr_shop_product','p').' as `title`,
                    p.sef,
                    p.image,
                    p.new,
                    p.local_brand,
                    p.sale,
                    p.instock,
                    p.min_price,
                    p.order
                    '.$this->getProductsSQL($section).
              ' LIMIT '.(int)$offset.','.(int)$itemPerPage;
        $items = $db->selectPage($totalRows,$sql);
        if(!is_array($items) || !count($items)){
            return null;
        }
        $smarty = Core::getSmarty();
        $smarty->assign('page_count', ceil($totalRows / $itemPerPage));
        $smarty->assign('page_current', $currentPage);
        return $items;
    }

    public function generateFilter($section, $secondPass=false){
        $filters = array();
        $barandFilterId = $this->getBrandFilterId($section['id']);
        foreach ($section['filterOptions'] as $optionId) {
            $filter = $this->getListForOption($section, $optionId);
//            print_r($filter); die();
            if ($filter) {
                $filters[$optionId] = $filter;
            }
        }
        if(!is_array($_REQUEST['filters'])){
            $_REQUEST['filters'] = array();
        }
        $selectedFilters =  &$_REQUEST['filters'];
        //Cleanup selected filters from missing options
        $noDeadParams = true;
        foreach ($selectedFilters as $optionId=>&$filter) {

            foreach($filter as $key=>$option){
                //die(print_r($filter));
                if(!in_array($option, Helpers::array_pluck($filters[$optionId], 'value'))){
                    unset($filter[$key]);
                    $noDeadParams = false;
                }
            }
        }
        if(!$noDeadParams && !$secondPass){
            return $this->generateFilter($section,true);
        }

        //Set current filter state variable
        $smarty = Core::getSmarty();
        $currentFilters = '';
        if(count($selectedFilters) || (int)$_REQUEST['price_from'] || (int)$_REQUEST['price_to']){
            $currentFilters = '?'.http_build_query(array(
                    'filters'=>$selectedFilters,
                    'price_from'=>(int)$_REQUEST['price_from'] ? (int)$_REQUEST['price_from'] : null,
                    'price_to'=>(int)$_REQUEST['price_to'] ? (int)$_REQUEST['price_to'] : null
                ));
        }

        $smarty->assign('current_filters',  $currentFilters);

        function cmp( $el1, $el2) {
            return strnatcmp( $el1['value'], $el2['value']);
        }

        foreach ($filters as $optionId=>&$filter){
            foreach($filter as &$option){
                $data = $selectedFilters;
                if(!is_array($data[$optionId])){
                    $data[$optionId]=array();
                }
                $s = &$data[$optionId];
                if (in_array($option['value'], $s)) {
                    $option['active'] = true;
                    if(($key = array_search($option['value'], $s)) !== false) {
                        unset($s[$key]);
                    }
                } else {
                    $s[]=$option['value'];
                }
                if(!$_REQUEST['filters']){
                    $option['link']= '/category/'.$section['sef'].'/'.urlencode(str_replace(' ','_',$option['value']));
                } else {
                    $option['link'] = '/category/'.$section['sef'].'?'.http_build_query(array(
                            'filters'=>$data,
                            'price_from'=>(int)$_REQUEST['price_from'] ? (int)$_REQUEST['price_from'] : null,
                            'price_to'=>(int)$_REQUEST['price_to'] ? (int)$_REQUEST['price_to'] : null));
                }

            }
            usort( $filter,'cmp');
        }
        return $filters;
    }

    public function generatePriceFilter($section, $secondPass=false){
        $priceFilter = array();
        $db = Core::getDB();
        $sql = 'SELECT max(max_price) as `price` FROM sr_shop_product WHERE  `section_id` IN ('.$section['cat_ids'].') AND active=1 AND instock=1 AND parent_id=0';
        $row = $db->selectRow($sql);
        if($row){
            $priceFilter['to']=ceil((float)$row['price']);
        }
        $sql = 'SELECT min(min_price) as `price` FROM sr_shop_product WHERE `section_id` IN ('.$section['cat_ids'].') AND active=1 AND instock=1 AND parent_id=0';
        $row = $db->selectRow($sql);
        if($row){
            $priceFilter['from']=floor((float)$row['price']);
        }
        $priceFilter['start']=(int)$_REQUEST['price_from']>0 ? (int)$_REQUEST['price_from'] : $priceFilter['from'];
        $priceFilter['end']=(int)$_REQUEST['price_to']>0 ? (int)$_REQUEST['price_to'] : $priceFilter['to'];
        if($priceFilter['start']<$priceFilter['from']){
            $priceFilter['start']=$priceFilter['from'];
        }
        if($priceFilter['end']>$priceFilter['to']){
            $priceFilter['end']=$priceFilter['to'];
        }
        return $priceFilter;
    }


    public function getListForOption($section, $targetOptionId)
    {
        //$sql = 'SELECT o.`value`, count(o.`value`) as cnt FROM sr_shop_option_value AS o ';
        $suffix = Lang::$current!=Lang::$default ? Lang::$current.'_' : '';
        $sql = 'SELECT o.`'.$suffix.'value` as value, count(DISTINCT p.id) as cnt FROM sr_shop_option_value AS o
                INNER JOIN sr_shop_product AS p
                ON o.`main_product_id`=p.`id` and p.`section_id` IN ('.$section['cat_ids'].')
                LEFT OUTER JOIN sr_shop_product AS m
                ON p.`id`=m.`parent_id` ';
        $conditions = array();
        foreach ($section['filterOptions'] as $optionId) {
            $filter = $_REQUEST['filters'][$optionId];
            if (!is_array($filter) || !count($filter) || $optionId == $targetOptionId) continue;
            $tableAlias = 'o' . (int)$optionId;
            $sql .= "INNER JOIN sr_shop_option_value AS " . $tableAlias . "
                    ON o.`main_product_id`=" . $tableAlias . ".`main_product_id` ";
            foreach ($filter as &$f) {
                $f = "'" . Helpers::mysql_escape($f) . "'";
            }
            array_push($conditions,
                $tableAlias . '.option_id=' . (int)$optionId . ' AND ' . $tableAlias . ".`value` in (" . implode(',', $filter) . ")"
            );
        }
        $conditions = count($conditions) ? ' AND ' . implode(' AND ', $conditions) : '';
        $priceFilter = '';
        $price_from = (float)$_REQUEST['price_from'];
        $price_to = (float)$_REQUEST['price_to'];
        if($price_from>0){
            $priceFilter.=' AND ((m.sale_price>0 AND m.sale_price>='.$price_from.') ||
                                (m.sale_price=0 AND m.price>0 AND m.price>='.$price_from.') ||
                                (m.price IS NULL AND p.sale_price>0 AND p.sale_price>='.$price_from.') ||
                                (m.price IS NULL AND p.sale_price=0 AND p.price>0 AND p.price>='.$price_from.'))';
        }
        if($price_to>0){
            $priceFilter.=' AND ((m.sale_price>0 AND m.sale_price<='.$price_to.') ||
                                (m.sale_price=0 AND m.price>0 AND m.price<='.$price_to.') ||
                                (m.price IS NULL AND p.sale_price>0 AND p.sale_price<='.$price_to.') ||
                                (m.price IS NULL AND p.sale_price=0 AND p.price>0 AND p.price<='.$price_to.'))';
        }
        $sql .= 'WHERE o.'.$suffix.'value<>"" AND p.active=1 AND o.option_id=' . (int)$targetOptionId . $conditions . $priceFilter . ' GROUP BY o.'.$suffix.'value ORDER BY o.'.$suffix.'value';
        $db = Core::getDB();
        $result = $db->select($sql);
        if (!is_array($result) || !count($result)) {
            return false;
        } else {
            return $result;
        }
    }

    public function getProductsSQL($section,$skipPrice=false){
        //$sql = 'SELECT o.`value`, count(o.`value`) as cnt FROM sr_shop_option_value AS o ';
        $sql = ' FROM sr_shop_product AS p LEFT OUTER JOIN sr_shop_product AS m ON p.`id`=m.`parent_id`';
        $conditions = array();
        foreach($section['filterOptions'] as $optionId){
            $filter = $_REQUEST['filters'][$optionId];
            if(!is_array($filter) || !count($filter)) continue;
            foreach($filter as &$f){
                $f = "'".Helpers::mysql_escape($f)."'";
            }
            $tableAlias = 'o'.(int)$optionId;
            $sql .="INNER JOIN sr_shop_option_value AS ".$tableAlias."
                    ON p.`id`=".$tableAlias.".`main_product_id` ";
            array_push($conditions,
                $tableAlias.'.option_id='.(int)$optionId.' AND '.$tableAlias.".`value` in (".implode(',',$filter).")"
            );
        }
        $conditions = count($conditions) ? ' AND '.implode(' AND ',$conditions) : '';
        $priceFilter = '';
        if(!$skipPrice){
            $price_from = (float)$_REQUEST['price_from'];
            $price_to = (float)$_REQUEST['price_to'];
            if($price_from>0){
                $priceFilter.=' AND ((m.sale_price>0 AND m.sale_price>='.$price_from.') ||
                                    (m.sale_price=0 AND m.price>0 AND m.price>='.$price_from.') ||
                                    (m.price IS NULL AND p.sale_price>0 AND p.sale_price>='.$price_from.') ||
                                    (m.price IS NULL AND p.sale_price=0 AND p.price>0 AND p.price>='.$price_from.'))';
            }
            if($price_to>0){
                $priceFilter.=' AND ((m.sale_price>0 AND m.sale_price<='.$price_to.') ||
                                    (m.sale_price=0 AND m.price>0 AND m.price<='.$price_to.') ||
                                    (m.price IS NULL AND p.sale_price>0 AND p.sale_price<='.$price_to.') ||
                                    (m.price IS NULL AND p.sale_price=0 AND p.price>0 AND p.price<='.$price_to.'))';
            }
        }
        $sql .= 'WHERE p.`parent_id`=0 and p.`active`=1 and p.`section_id` in ('.$section['cat_ids'].')'.$conditions.$priceFilter.' ORDER BY p.`instock` desc, p.`order` asc';
        return $sql;
    }

    private function getSection(){
        $section = new DBObject('sr_shop_section');
        $section->fetch('`sef`="'.Helpers::mysql_escape(Core::$path[1]).'"');

        if ($section->get('cat_level') > 1) {
            $top_section = new DBObject('sr_shop_section');
            $top_section->fetch('`cat_left`<'.$section->get('cat_left').' and `cat_right`>'.$section->get('cat_right').' and `cat_level`=1');
            Modules::addSubsections($top_section->id,Modules::getSubsectionMenu($top_section->getAll()));
            Core::$breadcrumbs['/category/'.$top_section->get('sef')]=$top_section->get('title');
        } else {
            $top_section = $section;
        }
        if ($section->get('cat_right') - $section->get('cat_left') > 1) {
            $subcategories = new DBCollection('sr_shop_section');
            $subcategories->fetch('`cat_left`>'.$section->get('cat_left').' and `cat_right`<'.$section->get('cat_right'));
            Modules::addSubsections($section->id,$subcategories->data);
            $catIds = Helpers::array_pluck($subcategories->data,'id');
            array_push($catIds,$section->id);
            $section->set('cat_ids',implode(',',$catIds));
        } else {
            $section->set('cat_ids',$section->id);
        }
        $availableOptions = new DBCollection('sr_shop_option');
        $availableOptions->fetch('`section_id`='.(int)$top_section->id,'`order` asc');
        $result = $section->getAll();

        $result['options'] = $availableOptions->data;
        $result['top_section'] = $top_section->getAll();
        $filterOptions = array();
        foreach($result['options'] as $option){
            if($option['is_filter']==1){
                array_push($filterOptions,$option['id']);
            }
        }
        $result['filterOptions'] = $filterOptions;

        return $result;
    }

    private function setTypes(&$product){
        $intProp = array('id','section_id','parent_id','order','stock','active','home','new','instock');
        $floatProp = array('price','sale_price','min_price','max_price');
        foreach($intProp as $name){
            $product[$name]=(int)$product[$name];
        }
        foreach($floatProp as $name){
            $product[$name]=(float)$product[$name];
        }
    }

    private function getItemPrice(&$product){
        if(!$product['modifications'] || !count($product['modifications'])){
            $product['stock']=!!$product['stock'];
            $product['sale']=!!$product['sale_price'];
            return;
        }
        $product['stock'] = false;
        $product['sale'] = false;
        unset($product['price']);
        foreach($product['modifications'] as $m){
            if(!$m['stock']) continue;
            $product['stock'] = true;
            if($m['sale_price']>0){
                $product['sale'] = true;
                if($product['price']>$m['sale_price'] || !$product['price']) $product['price']=$m['sale_price'];
            } elseif($m['price']>0){
                if($product['price']>$m['price'] || !$product['price']) $product['price']=$m['price'];
            }
        }
    }

    private function getBrandFilterId($id)
    {
        $db = Core::getDB();
        /*$sth = $db->prepare("select id, title from sr_shop_option where section_id = :id");
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        //$sth->debugDumpParams();
        $result = $sth->fetchAll();
        //я знаю, що це неправильно, але пдо матюкається на like в prepared statement
        foreach ($result as $r) {
            if ($r['title'] == 'Бренд') {
                return $r['id'];
            }
        }*/
        $sql = "select id from sr_shop_option where section_id = {$id} and title like '%Бренд%'";
        $filter =  $db->query($sql);
        return  $filter[0]['id'];

    }

}
