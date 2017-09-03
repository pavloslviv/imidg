<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
class Search extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'show';
        $this->actions['show'] = 'show';
    }

    public function show()
    {
        $itemPerPage = 16;
        $query = trim($_REQUEST['query']);
        $searchStr = Helpers::mysql_escape($query);
        $db = Core::getDB();
        $smarty = Core::getSmarty();
        $totalRows = 0;
        $currentPage = (int)str_replace('page-', '', Core::$path[1]);
        if($currentPage<1) $currentPage=1;
        $offset = $itemPerPage * ($currentPage - 1);
        if(mb_strlen($query)>2){
            $titleField = Lang::queryField('title','sr_shop_product','p');
            $products = $db->selectPage($totalRows,
                "SELECT DISTINCT
                p.id as ARRAY_KEY,
                p.id,
                p.parent_id,
                ".$titleField." as `title`,
                p.sef,
                p.image,
                p.new,
                p.sale,
                p.instock,
                p.min_price
            FROM sr_shop_product as p
            LEFT OUTER JOIN sr_shop_option_value AS o ON p.`id`=o.`main_product_id`
            WHERE
              p.active=1 AND p.instock=1 AND p.parent_id=0 AND
              (
              ".$titleField." LIKE '%$searchStr%' OR
              ".Lang::queryField('description','sr_shop_product','p')." LIKE '%$searchStr%' OR
              ".Lang::queryField('value','sr_shop_option_value','o')." LIKE '%$searchStr%'
              )
            LIMIT ?d,?d",
                $offset,$itemPerPage);
        } else {
            $products=null;
        }
        $queryString = $_SERVER['QUERY_STRING']!='' ? '?'.$_SERVER['QUERY_STRING'] : '';
        //Breadcrumbs
        Core::$breadcrumbs['/seacrh'.$queryString]=Lang::$locale['search'];
        $smarty->assign('meta_title',Lang::$locale['search']);
        $smarty->assign('query',$query);
        $smarty->assign('page_count', ceil($totalRows / $itemPerPage));
        $smarty->assign('page_current', $currentPage);
        $smarty->assignByRef('products', $products);
        $smarty->assign('current_filters', $queryString);
        $smarty->assign('component', 'search');
    }
}
