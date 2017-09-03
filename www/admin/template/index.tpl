<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/lib/bootstrap/css/bootstrap.min.css"/>
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/lib/bootstrap/css/bootstrap-theme.min.css"/>
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/admin/template/css/template.css"/>
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/admin/template/shop/css/index.css"/>
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/lib/jquery/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css"/>
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/lib/jquery/fancybox/jquery.fancybox.css"/>
    <script type="text/javascript" src="{$HTTP_ROOT}/lib/jquery/jquery.js"></script>
    <script type="text/javascript" src="{$HTTP_ROOT}/lib/jquery/jquery-ui/js/jquery-ui-1.10.3.custom.min.js"></script>
    {*<script type="text/javascript" src="{$HTTP_ROOT}/lib/jquery/fancybox/jquery.fancybox.pack.js"></script>*}
    <script type="text/javascript" src="{$HTTP_ROOT}/lib/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="{$HTTP_ROOT}/lib/js/underscore.js"></script>
    <script type="text/javascript" src="{$HTTP_ROOT}/lib/js/helpers.js"></script>
    <script type="text/javascript" src="{$HTTP_ROOT}/admin/template/js/autotranslator.js"></script>
    <script type="text/javascript">
        AutoTranslator.currentLang='{$smarty.session.lang}';
    </script>
    {if $editor_enable}{include file="blocks/head_editor.tpl"}{/if}
    {literal}
        <script type="text/javascript">
            $(function(){
                $('.lang_selector a.lang-switch').on('click',function(e){
                    e.preventDefault();
                    $.get('index.php',{set_lang: $(e.currentTarget).data('lang')},function(){
                        window.location.reload();
                    });
                });
            });
        </script>
        <!--Google API client-->
        <script type="text/javascript">
            window.googleApiClientReady = function (){
                gapi.client.setApiKey('AIzaSyA7xphChZ-OtnTvvmHyceh0x4Pz7rDku7E');
            }
        </script>
        <script type="text/javascript" src="https://apis.google.com/js/client.js?onload=googleApiClientReady"></script>

    {/literal}
</head>
<body>
{*id="admin_title"*}
<nav class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Панель управления сайтом</a>
    </div>
        {if $USER}
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                {if $USER.level=='admin'}
                    <li><a href="index.php?com=shop#orders">Заказы</a></li>
                    <li><a href="index.php?com=shop#products">Товары</a></li>
                    <li><a href="index.php?com=shop#products/0">Необработанные товары</a></li>
                    <li><a href="index.php?com=shop#products/-1">Товары из удаленных категорий</a></li>
                    <li><a href="index.php?com=shop#callbacks">Перезвоните мне</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Клиенты<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li {if $com_id=='Customers'} class="active"{/if}><a href="index.php?com=shop#customers">Аккаунты</a></li>
                            <li {if $com_id=='Mailman'} class="active"{/if}><a href="index.php?com=mailman">Рассылка</a></li>
                            <li {if $com_id=='Discount'} class="active"{/if}><a href="index.php?com=discount&action=list">Дисконты</a></li>
                            <li><a href="index.php?com=settings&action=discounts">Сетка дисконтов</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="index.php?com=shop" class="dropdown-toggle" data-toggle="dropdown">Дополнительно<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li {if $com_id=='Page'} class="active"{/if}><a href="index.php?com=page">Страницы</a></li>
                            <li {if $com_id=='Articles'} class="active"{/if}><a href="index.php?com=articles">Статьи</a></li>
                            <li {if $com_id=='Guestbook'} class="active"{/if}><a href="index.php?com=guestbook">Отзывы</a></li>
                            <li {if $com_id=='Ads'} class="active"{/if}><a href="index.php?com=ads">Рекламные блоки</a></li>
                            <li {if $com_id=='Brand'} class="active"{/if}><a href="index.php?com=brand">Брендові блоки</a></li>
                            <li><a href="index.php?com=settings&action=map">Карта представительств</a></li>
                            <li {if $com_id=='MetaTags'} class="active"{/if}><a href="index.php?com=meta_tags">Мета-теги</a></li>
                            <li {if $com_id=='ShopSections'} class="active"{/if}><a href="index.php?com=shop_sections">Разделы каталога</a></li>
                            <li {if $com_id=='Menu'} class="active"{/if}><a href="index.php?com=menu">Меню</a></li>
                        </ul>
                    </li>
                {/if}
                </ul>
                <ul class="nav navbar-nav navbar-right">
                {if $USER.level=='admin'}
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Редактируемый язык">
                            {if $current_lang=='uk'}UK <img src="/lib/images/uk.png" alt="UK"/>{/if}
                            {if $current_lang=='ru'}RU <img src="/lib/images/ru.png" alt="RU"/>{/if}
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu lang_selector">
                            <li {if $current_lang=='uk'} class="active"{/if}>
                                <a href="#" class="lang-switch" data-lang="uk"><img class="pull-right" src="/lib/images/uk.png" alt="UK"/> Українська </a>
                            </li>
                            <li {if $current_lang=='ru'} class="active"{/if}>
                                <a href="#" class="lang-switch" data-lang="ru"><img class="pull-right" src="/lib/images/ru.png" alt="RU"/> Русский</a>
                            </li>
                        </ul>
                    </li>
                    <li {if $com_id=='Settings'} class="active"{/if}>
                        <a href="index.php?com=settings" title="Настройки">
                            <span class="glyphicon glyphicon-cog"></span>
                        </a>
                    </li>
                    <li {if $com_id=='Users'} class="active"{/if}>
                        <a href="index.php?com=users" title="Пользователи">
                            <span class="glyphicon glyphicon-user"></span>
                        </a>
                    </li>
                {/if}
                    <li>
                        <a href="index.php?do=logout" title="Выход">
                            <span class="glyphicon glyphicon-log-out"></span>
                        </a>
                    </li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        {/if}
</nav>
<div class="container">
{if $component}{include file="pages/$component.tpl"}{/if}
</div>
{*<footer></footer>*}
</body>
</html>