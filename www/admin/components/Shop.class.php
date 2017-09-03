<?php
defined('RUN_CMS') or die('Restricted access');
/**
 * Created by JetBrains PhpStorm.
 * User: Serg
 * Date: 03.01.12
 * Time: 7:28
 * To change this template use File | Settings | File Templates.
 */
class Shop extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'view';
    }

    public function view(){
        $smarty = Core::getSmarty();
        $smarty->assign('lockedOptions', ShopCore::$lockedOptions);
        $smarty->assign('modificatorOptions', ShopCore::$modifications);
        $smarty->assign('paymentMethods', ShopCore::$paymentMethods);
        $smarty->assign('shipmentMethods', ShopCore::$shipmentMethods);
        $addressList = json_decode(Core::getSettings('shop','map'),true);
        $smarty->assign('addressList', $addressList['items']);
        $smarty->assign('editor_enable', true);
        $smarty->assign('component', 'shop');
    }
}
