<?php
defined('RUN_CMS') or die('Restricted access');
/**
 * Created by JetBrains PhpStorm.
 * User: Serg
 * Date: 03.01.12
 * Time: 7:28
 * To change this template use File | Settings | File Templates.
 */
class Product extends Component
{
    protected function registerActions()
    {
        $this->actions['default'] = 'get';
        $this->actions['get'] = 'get';
    }

    public function get()
    {
        if($_GET['json']){
            $this->getSimple();
            return;
        }

        $productId = (int)array_shift(explode('-',Core::$path[1]));
        $productRow = new DBObject('sr_shop_product',$productId);
        //$productRow->fetch('`sef`="'.Helpers::mysql_escape(Core::$path[1]).'"');
        if(!$productRow->id){
            //TODO: Send error 404
            exit();
        }
        //Get data as array
        $product = $productRow->getAll();
        $this->setTypes($product);
        //Fetch section data
        $product['section']=$this->getSection($productRow->get('section_id'));
        $product['top_section']=$this->getTopSection($product['section']);
        //If section have options fetch product options
        if(count($product['section']['options'])){
            $product['options']=$this->fillOptions($product['top_section']['options'],$productRow->id);
            $this->setSpecialOptions($product);
        }
        //Get modifications
        $this->getModifications($product);
        //Breadcrumbs
        if(!$this->parseReferer($product['section'])){
            Core::$breadcrumbs['/category/'.$product['section']['sef']]=$product['section']['title'];
        }

        Core::$breadcrumbs['/product/'.$product['id'].'-'.$product['sef']]=$product['title'];

        //Assign data to smarty
        $smarty = Core::getSmarty();
        $title = $product['meta_title'];
        if(!$title && Lang::$locale['product_title_tpl_'.$product['section']['id']]){
            $title = str_replace('{title}',$product['title'],Lang::$locale['product_title_tpl_'.$product['section']['id']]);
        } else {
            $title = $product['title'];
        }
        if(!$product['meta_descr'] && Lang::$locale['product_descr_tpl_'.$product['section']['id']]){
            $product['meta_descr'] = str_replace('{title}',$product['title'],Lang::$locale['product_descr_tpl_'.$product['section']['id']]);
        }
        $smarty->assign('meta_title', $title);
        $smarty->assign('meta_descr', $product['meta_descr']);
        $smarty->assign('meta_keyw', $product['meta_keyw']);
        $smarty->assign('social_data',array(
            'site_name'=>Lang::$locale['site_name'],
            'locale_name'=>Lang::$locale['locale_name'],
            'type'=>'product',
            'title'=>$product['title'],
            'description'=>strip_tags($product['description']),
            'image'=>HTTP_ROOT.'/media/product/'.$product['id'].'_medium.'.$product['image'],
            'url'=>'/product/'.$product['id'].'-'.$product['sef']
        ));
        $smarty->assign('product', $product);
        $smarty->assign('component', 'product');
    }

    public function parseReferer($section){
        if(!$_SERVER['HTTP_REFERER']) return false;
        $currentHost = parse_url(HTTP_ROOT,PHP_URL_HOST);
        $urlArray = parse_url($_SERVER['HTTP_REFERER']);

        if(!$urlArray || $currentHost!=$urlArray['host']) return false;

        $url = $urlArray['path'].($urlArray['query'] ? '?'.$urlArray['query'] : '');
        if($urlArray['path']=='/' || !$urlArray['path']){
            $title = Lang::$locale['home'];
        } else {
            $path = explode('/',$urlArray['path']);
            switch($path[1]){
                case 'search':
                    $title=Lang::$locale['search'];
                    break;
                case 'category':
                    $title=$section['title'];
                    break;
                case 'hits':
                    $title=Lang::$locale['hits'];
                    break;
                case 'new_and_sale':
                    $title=Lang::$locale['new_and_sale'];
                    break;
                default:
                    $title=$section['title'];
                    $url='/category/'.$section['sef'];
                    break;
            }
        }
        Core::$breadcrumbs[$url]=$title;
        return true;
    }

    public function getSimple()
    {
        $db = Core::getDB();
        $productId = (int)$_GET['id'];
        $product = $db->selectRow('SELECT id,parent_id,'.Lang::queryField('title','sr_shop_product').' as `title`,price,sale_price,image FROM sr_shop_product WHERE `id`=?',$productId);
        $this->setTypes($product);
        if(!$product['id']) $this->sendJSON(array('result'=>'error','message'=>Lang::$locale['product_not_found']));

        $productRow = new DBObject('sr_shop_product',$productId);

        //Get data as array
        $product = $productRow->getAll();
        $this->setTypes($product);
        //Fetch section data
        $product['section']=$this->getSection($productRow->get('section_id'));
        $product['top_section']=$this->getTopSection($product['section']);
        //If section have options fetch product options
        if(count($product['section']['options'])){
            $product['options']=$this->fillOptions($product['top_section']['options'],$productRow->id);
            $this->setSpecialOptions($product);
        }
        //Get modifications
        $this->getModifications($product);

        $this->sendJSON(array('result'=>'success','data'=>$product));
    }

    public function getSection($sectionId){
        $section = new DBObject('sr_shop_section',(int)$sectionId);
        $availableOptions = new DBCollection('sr_shop_option');
        $availableOptions->fetch('`section_id`='.(int)$section->id);
        $result = $section->getAll();
        $result['options'] = $availableOptions->data;
        return $result;
    }

    public function getTopSection($section){
        if($section['cat_level']!=1){
            $topSection = new DBObject('sr_shop_section');
            $topSection->fetch('`cat_left`<'.(int)$section['cat_left'].' and `cat_right`>'.(int)$section['cat_right'].' and `cat_level`=1');
            $availableOptions = new DBCollection('sr_shop_option');
            $availableOptions->fetch('`section_id`='.(int)$topSection->id);
            $result = $topSection->getAll();
            $result['options'] = $availableOptions->data;
            return $result;
        } else {
            return $section;
        }
    }


    private function setSpecialOptions(&$product){
        if(!$product['options']['32'] && !$product['options']['38'] && !$product['options']['49'] ) return;
        if($product['options']['32']){
            $product['dayTime']=$product['options']['32'];
        } elseif ($product['options']['38']){
            $product['dayTime']=$product['options']['38'];
        } elseif ($product['options']['49']){
            $product['dayTime']=$product['options']['49'];
        }
        if($product['dayTime']){
            $product['dayTime']['text']= Lang::$locale['lockedOptions'][$product['dayTime']['option_id']][$product['dayTime']['value']];
        }
        if($product['options']['33']){
            $product['season']=$product['options']['33'];
        } elseif ($product['options']['39']){
            $product['season']=$product['options']['39'];
        } elseif ($product['options']['50']){
            $product['season']=$product['options']['50'];
        }
        if($product['season']){
            $product['season']['text']= Lang::$locale['lockedOptions'][$product['season']['option_id']][$product['season']['value']];
        }

    }

    private function getModifications(&$product){
        $inStock = false;
        $modificationsList = new DBCollection('sr_shop_product');
        $modificationsList->fetch('`parent_id`='.(int)$product['id']);
        if(count($modificationsList->data)){
            $product['modifications']=array();
            foreach($modificationsList->data as $m){
                $this->setTypes($m);
                $m['instock']=!!((int)$m['stock']-(int)$m['reserved']);
                if($m['stock']) {
                    $inStock=true;
                    $m['options'] = $this->fillOptions($product['top_section']['options'],$m['id']);
                    //TODO: implement more elegant solution for displaying some specific types of options
                    foreach($m['options'] as $o){
                        if($o['title']=='Колір' || $o['title']=='Цвет'){
                            $m['color']=$o['value'];
                            break;
                        }
                    }

                    array_push($product['modifications'],$m);
                }
            }
            $product['instock']=$inStock;
            function cmp( $el1, $el2) {
                if($el1['order']==$el2['order']){
                    $par1 = $el1['options'][ShopCore::$modifications[$el1['section_id']]]['value'];
                    $par2 = $el2['options'][ShopCore::$modifications[$el2['section_id']]]['value'];
                    return strnatcmp( $par1, $par2);
                } else {
                    return ($el1['order']*1 < $el2['order']*1) ? -1 : 1;
                }

            }
            usort( $product['modifications'],'cmp');
        }
    }

    private function getModificationsSimple(&$product){
        $inStock = false;
        $modificationsList = new DBCollection('sr_shop_product',array('id','parent_id','order','title','price','sale_price','stock','reserved'));
        $modificationsList->fetch('`parent_id`='.(int)$product['id']);
        if(count($modificationsList->data)){
            $product['modifications']=array();
            foreach($modificationsList->data as $m){
                $this->setTypes($m);
                $m['stock']=!!((int)$m['stock']-(int)$m['reserved']);
                if($m['stock']) {
                    $inStock=true;
                    array_push($product['modifications'],$m);
                }
            }
            $product['stock']=$inStock;
            function cmp( $el1, $el2) {
                if($el1['order']==$el2['order']){
                    $par1 = $el1['title'];
                    $par2 = $el2['title'];
                    return strnatcmp( $par1, $par2);
                } else {
                    return ($el1['order']*1 < $el2['order']*1) ? -1 : 1;
                }
            }
            usort( $product['modifications'],'cmp');
        }
    }

    private function setTypes(&$product){
        $intProp = array('id','section_id','parent_id','order','stock','active','home','new');
        $floatProp = array('price','sale_price');
        foreach($intProp as $name){
            $product[$name]=(int)$product[$name];
        }
        foreach($floatProp as $name){
            $product[$name]=(float)$product[$name];
        }
    }

    public function fillOptions($availableOptions,$productId){
        $optionIds = Helpers::array_pluck($availableOptions,'id');
        $productOptions = new DBCollection('sr_shop_option_value');
        $productOptions->fetch('option_id in ('.implode(',',$optionIds).') and product_id='.(int)$productId);
        $options = $availableOptions;
        foreach($options as &$option){
            $option['option_id']=$option['id'];
            unset($option['id']);
        }
        foreach($productOptions->data as $value){
            $value['value_id']=$value['id'];
            unset($value['id']);
            $options[$value['option_id']] = array_merge($options[$value['option_id']],$value);
        }
        return $options;
    }

}
