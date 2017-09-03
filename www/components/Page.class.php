<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
class Page extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'show';
        $this->actions['show'] = 'show';
    }

    public function show()
    {
        $p = new DBObject('sr_page');
        $p->fetch("`sef`='".Helpers::mysql_escape(Core::$path[1])."'");
        $page = $p->getAll();
        $smarty = Core::getSmarty();
        $smarty->assignByRef('page', $page);
        Core::$breadcrumbs['/page/'+$page['sef']]=$page['title'];
        $smarty->assign('meta_title',$page['meta_title']?$page['meta_title']:$page['title']);
        $smarty->assign('meta_descr',$page['meta_descr']);
        $smarty->assign('meta_keyw',$page['meta_keyw']);
        $smarty->assign('page_id',$page['id']);
        $smarty->assign('component', 'page');
        if($page['id']==25) {
            $map = new DBObject('sr_settings');
            $map->fetch("section='shop' and name='map'");
            $smarty = Core::getSmarty();
            $smarty->assignByRef('map', $map->getAll());
        }
    }
}
