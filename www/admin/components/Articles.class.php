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
    public function __construct()
    {
        parent::__construct();
        include_once(ROOT . '/lib/VO/ArticleImage.class.php');
    }

    protected function registerActions()
    {
        $this->actions['default'] = 'listItems';
        $this->actions['list'] = 'listItems';
        $this->actions['edit'] = 'edit';
        $this->actions['save'] = 'save';
        $this->actions['delete'] = 'delete';
    }

    public function listItems()
    {
        $articlesList = new DBCollection('sr_articles', array('id', 'title', 'sef', 'date'));
        $articlesList->fetchPage((int)$_GET['page'],null,'`date` desc',12);

        $smarty = Core::getSmarty();
        $smarty->assign('page_count', $articlesList->pagesCount);
        $smarty->assign('page_current', $articlesList->currentPage);
        $smarty->assignByRef('articles_list', $articlesList->data);
        $smarty->assign('component', 'articles_list');
    }

    public function edit()
    {
        $articles = new DBObject('sr_articles', (int)$_GET['id']);
        $smarty = Core::getSmarty();
        $smarty->assignByRef('articles', $articles);
        $smarty->assign('editor_enable', true);
        $smarty->assign('component', 'articles_edit');
    }

    public function save()
    {
        if (!$_POST['attributes']) {
            $this->listItems();
            return;
        }
        $article = new DBObject('sr_articles', (int)$_GET['id']);
        if (!$_POST['attributes']['date']) $_POST['attributes']['date'] = time();
        else $_POST['attributes']['date'] = strtotime($_POST['attributes']['date']);
        $_POST['attributes']['sef'] = Helpers::TranslitToURL($_POST['attributes']['sef'] ? $_POST['attributes']['sef'] : $_POST['attributes']['title']);
        $_POST['attributes']['hide_image']=(int)$_POST['attributes']['hide_image'];
        $article->set($_POST['attributes']);
        $article->save();
        if ($article->get('sef') == '') {
            $article->set(array('sef' => $article->id));
            $article->save();
        }
        $image = new ArticleImage($article->id);
        $image->upload('file');
        $image->makeThumbs();
        $this->listItems();
    }

    public function delete()
    {
        if (!$_GET['id']) return;
        $article = new DBObject('sr_articles', (int)$_GET['id']);
        $image = new ArticleImage($article->id);
        $image->delete();
        $article->delete();
        $this->listItems();
    }
}
