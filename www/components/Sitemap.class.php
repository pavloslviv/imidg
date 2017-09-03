<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 23.12.11
 * Time: 2:46
 * To change this template use File | Settings | File Templates.
 */
class Sitemap extends Component
{
    protected $excludeProducts = array();
    protected function registerActions()
    {
        $this->actions['default'] = 'show';
        $this->actions['show'] = 'show';
    }

    public function show()
    {
        // Sent the correct header so browsers display properly, with or without XSL.
        header( 'Content-Type: application/xml' );
        echo '<?xml version="1.0" encoding="UTF-8"?>
                <?xml-stylesheet type="text/xsl" href="/template/xml-sitemap.xsl"?>
                <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
                ';
            echo $this->urlTpl('');
            echo $this->getPages();
            echo $this->urlTpl('/new_and_sale');
            echo $this->urlTpl('/hits');
            echo $this->urlTpl('/guestbook');
            echo $this->urlTpl('/articles');
            echo $this->getArticles();
            echo $this->getSections();
            echo $this->getProducts();

        echo '
                </urlset>';
        exit();
    }

    private function getPages(){
        $result ='';
        $pageList = new DBCollection('sr_page', array('id', 'sef'));
        $pageList->fetch('id>1');
        if(!count($pageList->data)) return '';
        foreach($pageList->data as $item){
            $result.=$this->urlTpl('/page/'.$item['sef']);
        }
        return $result;
    }

    private function getArticles(){
        $result ='';
        $pageList = new DBCollection('sr_articles', array('id', 'sef'));
        $pageList->fetch();
        if(!count($pageList->data)) return '';
        foreach($pageList->data as $item){
            $result.=$this->urlTpl('/article/'.$item['sef']);
        }
        return $result;
    }

    private function getSections(){
        $result ='';
        $pageList = new DBCollection('sr_shop_section', array('id', 'sef'));
        $pageList->fetch('`cat_level`>0', 'cat_left asc');
        if(!count($pageList->data)) return '';
        foreach($pageList->data as $item){
            $result.=$this->urlTpl('/category/'.$item['sef']);
        }
        return $result;
    }

    private function getProducts(){
        $result ='';
        $pageList = new DBCollection('sr_shop_product', array('id', 'sef','image'));
        $pageList->fetch('`parent_id`=0 and `active`=1 and `instock`=1');
        if(!count($pageList->data)) return '';
        foreach($pageList->data as $item){
            $result.=$this->urlTpl('/product/'.$item['id'].'-'.$item['sef'], $item['image'] ? ('/media/product/'.$item['id'].'_small.'.$item['image']) : null);
        }
        return $result;
    }
    public function urlTpl($url,$image=null,$lang=false){
        $result= '<url>
                      <loc>'.HTTP_ROOT.$url.'</loc>
                      <lastmod>'.date('Y-m-d').'</lastmod>
                      <changefreq>monthly</changefreq>
                      <priority>0.8</priority>';
        if($image){
            $result.='
            <image:image>
                 <image:loc>'.HTTP_ROOT.$image.'</image:loc>
            </image:image>';
        }
        $result.='
            </url>
                ';
        if(!$lang){
            $result.=$this->urlTpl('/ru'.$url,$image,true);
        }

        return $result;
    }
}
