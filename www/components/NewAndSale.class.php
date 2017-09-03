<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
class NewAndSale extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'show';
        $this->actions['show'] = 'show';
    }

    public function show()
    {
        $smarty = Core::getSmarty();
        $products = new DBCollection('sr_shop_product', array('id', 'title', 'sef', 'new','sale', 'image', 'instock','min_price','max_price'));
        $products->fetchPage((int)str_replace('page-', '', Core::$path[1]),'`parent_id`=0 and (`sale`=1 or `new`=1) and `instock`=1 and `active`=1','`order` asc',16);

        //Breadcrumbs
        Core::$breadcrumbs['/new_and_sale']=Lang::$locale['new_and_sale'];
        $smarty->assign('page_count', $products->pagesCount);
        $smarty->assign('page_current', $products->currentPage);
        $smarty->assignByRef('products', $products->data);
        $smarty->assign('component', 'new_and_sale');
    }
}
