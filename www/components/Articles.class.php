<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
class Articles extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'show';
        $this->actions['show'] = 'show';
        $this->actions['list'] = 'listItems';
    }

    public function run($action = null)
    {
        if (!Core::$path[1] || preg_match('`page-\d+`', Core::$path[1])) $this->listItems();
        else $this->{$this->actions['default']}();
    }

    public function show()
    {
        $p = new DBObject('sr_articles');
        $p->fetch("`sef`='".Helpers::mysql_escape(Core::$path[1])."'");
        $page = $p->getAll();
        $smarty = Core::getSmarty();
        $smarty->assignByRef('page', $page);
        Core::$breadcrumbs['/articles']=Lang::$locale['articles'];
        Core::$breadcrumbs['/articles/'.$page['sef']]=$page['title'];
        $smarty->assign('meta_title', $page['meta_title'] ? $page['meta_title'] : $page['title']);
        $smarty->assign('meta_descr', $page['meta_descr']);
        $smarty->assign('meta_keyw', $page['meta_keyw']);
        $smarty->assign('social_data',array(
            'site_name'=>Lang::$locale['site_name'],
            'locale_name'=>Lang::$locale['locale_name'],
            'type'=>'article',
            'title'=>$page['title'],
            'description'=>strip_tags($page['brief']),
            'image'=>HTTP_ROOT.'/media/articles/'.$page['id'].'_medium.jpg',
            'url'=>'/articles/'.$page['sef']
        ));
        $smarty->assign('component', 'articles');
    }

    public function listItems()
    {
        //Core::debugDB(true);
        Core::$breadcrumbs['/articles']=Lang::$locale['articles'];
        $itemList = new DBCollection('sr_articles', array('id', 'date', 'title', 'sef', 'brief','image'));
        $itemList->fetchPage((int)str_replace('page-','',Core::$path[1]),null,'`date` desc',12);
        $smarty = Core::getSmarty();
        $smarty->assign('page_count', $itemList->pagesCount);
        $smarty->assign('page_current', $itemList->currentPage);
        $smarty->assignByRef('items', $itemList->data);
        $smarty->assign('component', 'articles_list');
    }
}
