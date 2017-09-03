<!doctype html>
<html lang="{$lang_suffix}" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>{$meta_title|htmlspecialchars}</title>
    <meta name="description" content="{$meta_descr|htmlspecialchars}">
    <meta name="keywords" content="{$meta_keyw|htmlspecialchars}">

    <link rel="stylesheet" href="/template/assets/css/libs.min.css">
    <link rel="stylesheet" href="/template/assets/css/style.css">

    <meta name="viewport" content="initial-scale=1.0, width=device-width"/>
    <meta name='yandex-verification' content='79d2cc4128be455b'/>
    {if $social_data}{include file="blocks/social_tags.tpl"}{/if}
    <link rel="icon" type="image/ico" href="/favicon.ico"/>

    <!--[if lt IE 9]>
    <script src="/template/js/html5shiv.min.js"></script>
    <![endif]-->
    <meta name="google-site-verification" content="NMvu8Tsn2NLzsHsx17Lr7lsrDOT-_qNGeH2Hzi1Z3Zo"/>
    {if $canonical}
        <link rel="canonical" href="{$canonical}"/>
    {/if}
{literal}
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PJS67KB');</script>
<!-- End Google Tag Manager -->
{/literal}
</head>
<body class="{$item}">
{literal}
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PJS67KB"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
{/literal}

<div id="preloader">
    <img src="/assets/img/load.png" alt="">
</div>

<div class="main-wrapper">

    <header class="header-wrapper">
        <div class="top-line">
            <div class="container">
                <nav class="top-nav">

                    <ul>
                        {foreach from=$menus[2] item="item"}
                            <li>
                                <a {if $page_id==$item.page_id || $item.active} class="active"{/if} {if $item.url}href="{$item.url}"{/if}>{$item.title}</a>
                            </li>
                        {/foreach}
                    </ul>
                </nav>
                <div class="header-top_right">
                    <div class="header-lang">

                        {if $lang_code=='uk'}
                            <a href="#" class="ua" data-lang="">
                                <span></span>
                                Українська
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </a>
                            <div class="header-lang_dropdown">
                                <a href="#" class="ru" data-lang="ru">
                                    <span></span>Русский
                                </a>
                            </div>
                        {else}
                            <a href="#" class="ru" data-lang="ru">
                                <span></span>
                                Русский
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </a>
                            <div class="header-lang_dropdown">
                                <a href="#" class="ua" data-lang="">
                                    <span></span>
                                    Українська
                                </a>
                            </div>
                        {/if}

                    </div>

                    {if $currentUser}
                        <div class="header-profile">
                            <a href="#">{$currentUser.name}
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </a>
                            <nav class="profile-menu">
                                <ul>
                                    <li><a href="{$lang_suffix}/customer">{$locale.user_data} <i
                                                    class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                                    <!--<li><a href="#">Список бажаного<i class="fa fa-angle-right" aria-hidden="true"></i></a></li>-->
                                    <li><a href="{$lang_suffix}/cart">{$locale.cart}<i class="fa fa-angle-right"
                                                                                       aria-hidden="true"></i></a></li>
                                    <!--<li><a href="#">Мої відгуки<i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                                    <li><a href="#">Переглянуті товари<i class="fa fa-angle-right" aria-hidden="true"></i></a></li>-->
                                    <li><a id="logout" href="#" class="exit">{$locale.exit}</a></li>
                                </ul>
                            </nav>
                        </div>
                    {else}
                        <div class="header-auth">
                            <a href="#">{$locale.enter} / {$locale.sign_up}
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </a>
                        </div>
                    {/if}
                </div>
            </div>
        </div>

        <div class="header-main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-2">
                        <div class="logo-wrapper">
                            <a href="{$lang_suffix}/" class="logo">
                                <img src="/assets/img/logo-big.png" alt="Експерти іміджу">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-10">
                        <div class="header-contacts clearfix">
                            <div class="header-contacts_tel">
                                <i class="icon-phone-call-2"></i>
                                <a href="tel:{$settings.phone}">{$settings.phone}</a>,
                                <a href="tel:{$settings.phone2}">{$settings.phone2}</a>
                            </div>
                            <div class="header-contacts_shedule">
                                <i class="icon-clock"></i>
                                <span>{$locale.header_hours}</span>
                            </div>
                            <div class="header-contacts_feedback">
                                <i></i>
                                <a href="#call-me-popup" class="open-header_feedback open-modal">{$locale.call_me}</a>
                            </div>
                        </div>
                        <div class="header-search">
                            <form id="aside-search" action="{$lang_suffix}/search">
                                <input type="search" name="query" class="search-input"
                                       placeholder="{$locale.search_placeholder}">
                                <button type="submit" class="search-btn">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                        <div class="header-favorite">
                            <a href="#">
                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                <span>Список бажаного</span>
                            </a>
                        </div>
                        <div class="header-basket">
                            <a href="#open-basket"
                               class="open-basket">
                                        <span class="header-basket_icon">
                                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                            {if $cartSummary && $cartSummary.count}
                                                <span>{$cartSummary.count}</span>
                                            {/if}
                                        </span>
                                {if $cartSummary && $cartSummary.count}
                                    <b class="header-basket_price"><b>{$cartSummary.total|price}</b><span>&nbsp;{$locale.uah}</span></b>
                                {else}
                                    <b class="header-basket_price">{$locale.cart}</b>
                                {/if}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {*Category navigation*}
        <div class="header-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-nav_wrapper">

                            <nav class="main-nav cd-dropdown">
                                <a href="#0" class="cd-close">
                                    <span></span>
                                    <span></span>
                                </a>

                                <ul>


                                    {foreach from=$menus[95] item="item"}

                                        <li {if $item.subitems}class="has-children"{/if}>
                                            <a {if $page_id==$item.page_id || $item.active}class="active"{/if}
                                                    {if $item.url}href="{$item.url}"{/if}
                                            >
                                                {$item.title}
                                                {if $item.subitems}<i class="icon-2-down"></i>{/if}
                                            </a>
                                            {if $item.subitems}
                                                <ul class="submenu cd-secondary-dropdown is-hidden">
                                                    <li class="go-back">
                                                        <a href="#0">Каталог<i class="icon-2-down"></i></a>
                                                    </li>
                                                    {foreach from=$item.subitems item="subitem"}
                                                        <li {if $subitem.subitems}class="has-children"{/if}>
                                                            <a{if $section && $section.id==$subitem.id} class="active"{/if}
                                                                    {if $subitem.url}href="{$subitem.url}"{/if}>
                                                                {$subitem.title}
                                                                {if $subitem.subitems}<i class="icon-2-down"></i>{/if}
                                                            </a>
                                                            {if $subitem.subitems}
                                                                <ul class="submenu cd-secondary-dropdown is-hidden">
                                                                    <li class="go-back">
                                                                        <a href="#0">{$subitem.title}<i class="icon-2-down"></i></a>
                                                                    </li>
                                                                    {foreach from=$subitem.subitems item="subsubitem"}
                                                                        <li>
                                                                            <a{if $section && $section.id==$subitem.id} class="active"{/if}
                                                                                    {if $subsubitem.url}href="{$subsubitem.url}"{/if}>{$subsubitem.title}</a>
                                                                        </li>
                                                                    {/foreach}

                                                                </ul>
                                                            {/if}
                                                        </li>
                                                    {/foreach}
                                                </ul>
                                            {/if}
                                        </li>
                                    {/foreach}

                                    {*{foreach from=$sections item="item"}*}
                                        {*<li {if $item.children}class="has-children"{/if}>*}
                                            {*<a{if $section && $section.id==$item.id} class="active"{/if}*}
                                                    {*href="{$lang_suffix}/category/{$item.sef}">{$item.title}{if $item.children}*}
                                                    {*<i class="icon-2-down"></i>*}
                                                {*{/if}</a>*}

                                            {*{if $item.children}*}
                                                {*<ul class="submenu">*}
                                                    {*<li class="go-back"><a href="#0">Каталог<i class="icon-2-down"></i></a>*}
                                                    {*</li>*}
                                                    {*{foreach from=$item.children item="subitem"}*}
                                                        {*<li>*}
                                                            {*<a{if $section && $section.id==$subitem.id} class="active"{/if}*}
                                                                    {*href="{$lang_suffix}/category/{$subitem.sef}">{$subitem.title}</a>*}
                                                        {*</li>*}
                                                    {*{/foreach}*}
                                                {*</ul>*}
                                            {*{/if}*}
                                        {*</li>*}
                                    {*{/foreach}*}
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="header-mobile">
            <div class="container">
                <a href="#" class="mobile-menu_btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
                <a href="#" class="cd-dropdown-trigger">каталог</a>
                <div class="mobile-menu"></div>
            </div>
        </div>
    </header>

    <main class="main-content">
        {if $component!='home'}
            <section class="breadcrumb">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <ul>
                                <li class="breadcrumb-item">
                                    <a href="{$lang_suffix}/">{$locale.home}</a>
                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                                </li>
                                {assign var="counter" value=1}
                                {assign var="breadcrumbsCount" value=count($breadcrumbs)}
                                {foreach from=$breadcrumbs item="b_title" key="b_link"}
                                    {if ($counter != $breadcrumbsCount)}
                                        <li class="breadcrumb-item">
                                            <a href="{$lang_suffix}{$b_link}">{$b_title}</a>
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                        </li>
                                    {else}
                                        <li class="breadcrumb-item active">
                                            <b>{$b_title}</b>
                                        </li>
                                    {/if}
                                    {assign var="counter" value=$counter+1}
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        {/if}
        {if $component}{include file="pages/$component.tpl"}{/if}


    </main>

</div>

<footer class="footer-wrapper">

    <section class="brands-slider">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="brands-slider_wrapper">
                        {foreach from=$brand item="slide"}
                            <div class="brands-slider_item">
                                <div class="cell">
                                    <a href="{$slide.link}"><img src="/media/brand/{$slide.id}.jpg" alt="alt"></a>
                                </div>
                            </div>
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {if $custom_page_text || $component == 'home'}
        <section class="main-article">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <div class="article-logo">
                            <img src="/assets/img/logo-big.png" alt="alt">
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-9">
                        <div class="custom-page-text">

                            {if $custom_page_text}
                                {$custom_page_text}
                            {/if}

                            {if $component == 'home'}
                                {$page->get('text')}
                            {/if}
                        </div>

                        <a class="read-more">{$locale.more}...</a>
                    </div>
                </div>
            </div>
        </section>
    {/if}

    <div class="footer-main">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="footer-col">
                        <div class="footer-title">{$locale.contact}<i class="fa fa-angle-down" aria-hidden="true"></i>
                        </div>
                        <div class="footer-contacts">
                            <div class="footer-contacts_item tel">
                                <i class="icon-white-phone"></i>
                                <a href="tel:{$settings.phone}">{$settings.phone}</a>,
                                <a href="tel:{$settings.phone2}">{$settings.phone2}</a>
                            </div>
                            <div class="footer-contacts_item mail">
                                <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                <a href="#callback-popup" class="open-modal">shop@imidg.com.ua</a>
                            </div>
                            <div class="footer-contacts_item shedule">
                                <i class="icon-clock"></i>
                                <span>{$locale.header_hours}</span>
                            </div>
                        </div>
                        <div class="footer-social clearfix">
                            <span>{$locale.join}:</span>
                            <a href="https://www.facebook.com/imidg.com.ua" class="footer-fb" target="_blank"></a>

                        </div>
                    </div>
                </div>
                {if $currentUser}
                    <div class="col-md-2">
                        <div class="footer-col">
                            <div class="footer-title">{$locale.my_account}<i class="fa fa-angle-down"
                                                                             aria-hidden="true"></i></div>
                            <nav class="footer-nav">
                                <ul>
                                    <li><a href="{$lang_suffix}/customer">{$locale.my_cabinet}</a></li>
                                    <li><a href="{$lang_suffix}/cart">{$locale.cart}</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                {/if}

                <div class="{if $currentUser}col-md-2{else}col-md-3{/if}">
                    <div class="footer-col">
                        <div class="footer-title">{$locale.category_product}<i class="fa fa-angle-down"
                                                                               aria-hidden="true"></i></div>
                        <nav class="footer-nav">
                            <ul>
                                {*<li><a href="#">Каталог</a></li>*}
                                <li><a href="{$lang_suffix}/new_and_sale">{$locale.new_and_sale}</a></li>
                                <li><a href="{$lang_suffix}/hits">{$locale.hits}</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="{if $currentUser}col-md-2{else}col-md-3{/if}">
                    <div class="footer-col">
                        <div class="footer-title">{$locale.info}<i class="fa fa-angle-down" aria-hidden="true"></i>
                        </div>
                        <nav class="footer-nav">
                            <ul>
                                <li><a href="{$lang_suffix}/page/pro_kompanju">{$locale.about_us}</a></li>
                                <li><a href="{$lang_suffix}/articles">{$locale.articles_events}</a></li>
                                <li><a href="{$lang_suffix}/guestbook">{$locale.guest_book}</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="footer-col">
                        <div class="footer-title">{$locale.subscribe}<i class="fa fa-angle-down" aria-hidden="true"></i>
                        </div>
                        <div class="footer-subscribe">
                            <p>{$locale.footer_subscribe_info}</p>
                            <div class="footer-subscribe_form">
                                <form action="" class="subscibe_form">
                                    <input type="hidden" name="mode" value="subscribe"/>

                                    <input id="contact-mail" name="mail" type="email" placeholder="Ваш e-mail" required>
                                    <button type="submit">{$locale.send}</button>
                                </form>
                            </div>
                        </div>
                        <div class="footer-bonus">
                            <a href="#bonus-plus-modal" class="open-modal">Бонус плюс</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="copyright">&copy; {$locale.copyright}</div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="development">
                        <span>Сайт розроблено в </span>
                        <a>Otakoyi<span class="otakoyi-logo"></span></a>
						<span>Просування та оптимізація</span>
                        <a href="https://icyeast.org">Panem<span class="logo"></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>



<script id="tmpl-add-to-cart-form" type="text/html">

    <div class="product-popup">

        <div class="basket-popup_title"><%= product.title %></div>

        <div class="basket-item clearfix">
            <div class="col-xs-12 col-md-3">
                <div class="cell">
                    <% if(product.image){ %>
                    <img src="/media/product/<%= product.id %>_small.<%= product.image %>" alt="">
                    <% } %>
                </div>
            </div>

            <div class="col-xs-12 col-md-9">
                <table name="modification">


                    <% if (product.modifications){ %>
                    <% _.each(product.modifications,function(mod,index){
                    if(!mod.instock) return;
                    %>
                    <tr data-id="<%= mod.id %>" class="order-item product-to-cash">
                        <td class="item-title">
                            <% if(mod.options[63]){ %>
                            <span class="color" style="background-color: #<%= mod.options[63].value %>"></span>
                            <% } %>
                            <span class="title"><%= mod.title %></span>
                        </td>
                        <td class="item-price">
                            <span class="regular <%= mod.sale_price ? 'strike' : '' %>"> <%= SerenityShop.formatPrice(mod.price) %> {$locale.uah}</span>
                            <% if(mod.sale_price){ %>
                            <span class="regular"><%= SerenityShop.formatPrice(mod.sale_price) %> {$locale.uah}</span>
                            <% } %>
                            <input type="number" name="qty" class="number" min="1" max="<%= mod.stock %>" value="1"/>
                        </td>
                        {*<td class="item-qty"><span class="product-qty"></span></td>*}
                        <td class="item-buy-button">
                            <button class="add-to-cart-trigger btn" type="button" data-type="form"
                                    data-id="<%= mod.id %>"><span>{$locale.to_cart}</span></button>
                        </td>
                    </tr>

                    <% }); %>
                    <% } else { %>

                    <tr data-id="<%= product.id %>" class="order-item product-to-cash">
                        <td class="item-price">
                            <span class="regular <%= product.sale_price ? 'strike' : '' %>"> <%= SerenityShop.formatPrice(product.price) %> {$locale.uah}</span>
                            <% if(product.sale_price){ %>
                            <span class="regular"><%= SerenityShop.formatPrice(product.sale_price) %> {$locale.uah}</span>
                            <% } %>

                        </td>
                        <td class="item-qty">
                            <input type="number" name="qty" class="number" min="1" value="1"/>
                        </td>
                        <td class="item-buy-button">
                            <button class="add-to-cart-trigger btn" type="button" data-type="form"
                                    data-id="<%= product.id %>"><span>{$locale.to_cart}</span></button>
                        </td>
                    </tr>

                    <% } %>
                </table>

            </div>
        </div>
    </div>

</script>


<script id="tmpl-custom-alert" type="text/html">
    <div class="popup-overlay custom-alert">
        <div class="popup">
            <a class="popup-close icon-close" href="#"></a>
            <div class="h1"><%= title %></div>
            <div class="content"><%= text %></div>
            <div class="button">
                <button class="btn yellow" type="button">OK</button>
            </div>
        </div>
    </div>
</script>
<!-- Start SiteHeart code -->

<script>

    if (window.innerWidth > 760) {
        (function () {ldelim}
            var widget_id = 821990;
            _shcp = [{ldelim}widget_id: widget_id{rdelim}];
            //var lang =(navigator.language || navigator.systemLanguage
            //|| navigator.userLanguage ||"en")
            //.substr(0,2).toLowerCase();
            var lang = ('{$lang_suffix}'.substr(1, 2).toLowerCase() || "uk");
            var url = "widget.siteheart.com/widget/sh/" + widget_id + "/" + lang + "/widget.js";
            var hcc = document.createElement("script");
            hcc.type = "text/javascript";
            hcc.async = true;
            hcc.src = ("https:" == document.location.protocol ? "https" : "http")
                + "://" + url;
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hcc, s.nextSibling);
            {rdelim})();
        (function () {ldelim}
            var widget_id = 826545;
            _shcp = [{ldelim}widget_id: widget_id{rdelim}];
            var lang = ('{$lang_suffix}'.substr(1, 2).toLowerCase() || "uk");
            var url = "widget.siteheart.com/widget/sh/" + widget_id + "/" + lang + "/widget.js";
            var hcc = document.createElement("script");
            hcc.type = "text/javascript";
            hcc.async = true;
            hcc.src = ("https:" == document.location.protocol ? "https" : "http")
                + "://" + url;
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hcc, s.nextSibling);
            {rdelim})();
    }
</script>

<!-- End SiteHeart code -->


<!--Registration and authorization forms-->
<div class="auth-form enter-form">
    <div class="auth-form_title">{$locale.enter}</div>
    <div class="form-wrapper">
        <form action="" id="login-form">
            <fieldset class="input-item">
                <label for="login-mail">{$locale.email} (e-mail) <sup>*</sup></label>
                <input id="login-mail" type="text" name="login">
            </fieldset>
            <fieldset class="input-item">
                <label for="login-pass">{$locale.pass} <sup>*</sup></label>
                <input id="login-pass" type="password" name="pass">
            </fieldset>
            <div class="input-item_title"><sup>*</sup> - {$locale.required_field}</div>
            <div class="forget-pass">
                <a href="#pass-forget-form" class="open-modal">{$locale.fgt_pass}</a>
            </div>
            <div class="box-c">
                <button type="submit" class="btn btn-full">
                    <span>{$locale.sign_in}</span>
                </button>
            </div>
        </form>
    </div>
    <div class="auth-form_footer">
        <p>{$locale.dont_have_acc}</p>
        <div class="box-c">
            <a href="#" class="btn-simple open-registr_form">{$locale.submit_signup}</a>
        </div>
    </div>
</div>


<div class="auth-form registration-form">
    <div class="auth-form_title">{$locale.sign_up}</div>
    <div class="form-wrapper">
        <form id="signup-form">
            <fieldset class="input-item">
                <label for="signup-name">{$locale.pib} <sup>*</sup></label>
                <input id="signup-name" type="text" name="name">
            </fieldset>
            <fieldset class="input-item">
                <label for="signup-mail">{$locale.email} (e-mail) <sup>*</sup></label>
                <input id="signup-mail" name="mail" type="text">
            </fieldset>
            <fieldset class="input-item">
                <label for="signup-pass">{$locale.pass} <sup>*</sup></label>
                <input id="signup-pass" type="password" name="pass">
            </fieldset>
            <fieldset class="input-item">
                <label for="signup-pass-confirm">{$locale.confirm_pass} <sup>*</sup></label>
                <input id="signup-pass-confirm" type="password" name="pass-confirm">
            </fieldset>
            <fieldset class="input-item">
                <label for="signup-phone">{$locale.phone} <sup>*</sup></label>
                <input id="signup-phone" type="tel" name="phone">
            </fieldset>
            <fieldset class="input-item">
                <label for="registration-form_card">{$locale.have_discount}</label>
                <input id="registration-form_card" pattern="\d+" type="text" name="discount-code"
                       placeholder="{$locale.card_no}">
            </fieldset>
            <div class="input-item_title"><sup>*</sup> - {$locale.required_field}</div>
            <div class="box-c">
                <button type="submit" class="btn btn-full">
                    <span>{$locale.submit_signup}</span>
                </button>
            </div>
        </form>
    </div>
    {*<div class="auth-form_social">*}
    {*<span>або</span>*}
    {*<p>Увійти через соціальні мережі:</p>*}
    {*<div class="social-menu">*}
    {*<a href="#" class="twitter-icon">*}
    {*<i class="fa fa-twitter" aria-hidden="true"></i>*}
    {*</a>*}
    {*<a href="#" class="vk-icon">*}
    {*<i class="fa fa-vk" aria-hidden="true"></i>*}
    {*</a>*}
    {*<a href="#" class="facebook-icon">*}
    {*<i class="fa fa-facebook" aria-hidden="true"></i>*}
    {*</a>*}
    {*<a href="#" class="google-icon">*}
    {*<i class="fa fa-google-plus" aria-hidden="true"></i>*}
    {*</a>*}
    {*</div>*}
    {*</div>*}
    <div class="auth-form_footer">
        <p>{$locale.you_register}</p>
        <div class="box-c">
            <a href="#" class="btn-simple open-enter_form">{$locale.sign_in}</a>
        </div>
    </div>
</div>

<!--All Popup-->

<!--Call me Popup-->
<div id="call-me-popup" class="callback-popup simple-modal">
    <div class="simple-modal_title">{$locale.call_me}</div>
    <div class="callback-popup_wrapper row">
        <form action="" id="call-me-form">
            <fieldset class="input-item">
                <label for="call-me-name" class="col-md-5">{$locale.pib}<sup>*</sup>:</label>
                <div class="col-md-7">
                    <input id="call-me-name" type="text" name="name">
                </div>
            </fieldset>
            <fieldset class="input-item">
                <label for="call-me-phone" class="col-md-5">{$locale.phone}<sup>*</sup>:</label>
                <div class="col-md-7">
                    <input id="call-me-phone" type="tel" name="phone" placeholder="(000) 000-00-00">
                </div>
            </fieldset>
            <div class="input-item_title"><sup>*</sup> - {$locale.required_field}</div>
            <div class="box-c">
                <button type="submit" class="btn btn-full"><span>{$locale.send}</span></button>
            </div>
        </form>
    </div>
</div>

<!--Callback Popup-->
<div id="callback-popup" class="callback-popup middle-modal">
    <div class="middle-modal_title">{$locale.write_to_us}</div>
    <div class="callback-popup_wrapper row">
        <form action="" id="callback-form">
            <input type="hidden" name="mode" value="callback"/>
            <fieldset class="input-item">
                <label for="contact-name" class="col-md-5">{$locale.pib}<sup>*</sup>:</label>
                <div class="col-md-7">
                    <input id="contact-name" type="text" name="name">
                </div>
            </fieldset>
            <fieldset class="input-item">
                <label for="contact-phone" class="col-md-5">{$locale.phone}<sup>*</sup>:</label>
                <div class="col-md-7">
                    <input id="contact-phone" type="tel" name="phone" placeholder="(000) 000-00-00" maxlength="15">
                </div>
            </fieldset>
            <fieldset class="input-item">
                <label for="contact-mail" class="col-md-5">E-mail:</label>
                <div class="col-md-7">
                    <input id="contact-mail" type="email" name="mail">
                </div>
            </fieldset>
            <fieldset class="input-item">
                <label for="contact-message" class="col-md-5">{$locale.mail_text}<sup>*</sup>:</label>
                <div class="col-md-7">
                        <textarea id="contact-message" class="text" name="message"
                                  style="width: 100%; height: 100px;"></textarea>
                </div>
            </fieldset>
            <div class="input-item_title"><sup>*</sup> - {$locale.required_field}</div>
            <div class="box-c">
                <button type="submit" class="btn btn-full"><span>{$locale.send}</span></button>
            </div>
        </form>
    </div>
</div>

<script id="tmpl-get-basket-items" type="text/html">
    <% if (products){ %>

    <div class="basket-popup_title">{$locale.cart}</div>

    <% _.each(products,function(p,index){ %>
        <div class="basket-item clearfix">
            <div class="basket-item_img">
                <div class="cell">
                    <a href="{$lang_suffix}/product/<% if (p.parent_id != 0) { %><%= p.parent_id %><% } else { %><%= index %><% } %>">
                        <% if(p.image){ %>
                            <img src="/media/product/<% if (p.parent_id != 0) { %><%= p.parent_id %><% } else { %><%= index %><% } %>_small.<%= p.image %>"
                             alt="{$p.title}">
                        <% } else { %>
                            <img src="/template/images/placeholder_140.png" alt="<%= p.title %>">
                        <% } %>
                    </a>
                </div>
            </div>
            <div class="basket-item_info">
                <div class="basket-item_name">
                    <a href="{$lang_suffix}/product/<% if (p.parent_id != 0) { %><%= p.parent_id %><% } else { %><%= index %><% } %>"><% if (p.parent_id != 0) { %><%= p.parent_title %><% } else { %><%= p.title %><% } %></a>
                </div>
                <div class="basket-item_descr">
                    <% if (p.parent_id != 0) { %>
                        <p><%= p.title %></p>
                    <% } %>

                    <% if(p.color) { %>
                        <span class="color" style="background-color: #<%= p.color %>}"></span>
                    <% } %>
                </div>
                <div class="basket-item_amount">
                            <span>{$locale.quantity}:<input data-id="<%= index %>" min="1" max="<%= p.stock %>" type="number"
                                                            class="number" value="<%= p.qty %>"></span>
                </div>
            </div>
            <div class="basket-item_price product-price clearfix">
                <% if (p.sale_price > 0) { %>
                    <span class="old-price"><%= p.price %> {$locale.uah}</span>
                    <b><%= p.sale_price %> {$locale.uah}</b>
                <% } else { %>
                    <% if (p.qty > 1) { %>
                        <span><%= p.price %> {$locale.uah}</span>
                    <% } else { %>
                        <b><%= p.price %> {$locale.uah}</b>
                    <% } %>
                <% } %>
                <div class="product-price">
                    <% if (p.qty > 1) { %>
                    <span>{$locale.sum}
                            : <b><% if (p.sale_price > 0) { %><%= p.qty * p.sale_price %><% } else { %><%= p.qty * p.price %><% } %></b></span>
                    <% } %>
                </div>
                <a data-id="<%= index %>" class="basket-item_del">{$locale.delete}</a>
            </div>
        </div>
    <% }); %>

    <div class="basket-popup_footer">
        <% if(total.totalDiscount) { %>
            <div class="basket-popup_sales clearfix">
                <p>{$locale.discount_club}: <b>(<%= total.discount * 100 %>%)</b></p>
                <span>-<b><%= total.totalDiscount %></b> {$locale.uah}</span>
            </div>
        <% } %>
        <div class="basket-popup_total clearfix">
            <b>{$locale.total}:</b>
            <span><b><%= total.price %></b> <strong>{$locale.uah}</strong></span>
        </div>
        <div class="box-c clearfix">
            <a href="{$lang_suffix}/cart" class="btn btn-full ftr"><span>{$locale.go_to_cart}</span></a>
            <a href="javascript:$.fancybox.close();" class="btn-simple bascket-back ftl">{$locale.continue_shopping}</a>
        </div>
    </div>

    <% } else { %>
        <div class="basket-popup_title">{$locale.cart_is_empty}</div>
    <% } %>

</script>

<div id="open-basket" class="basket-popup">

    {if $smarty.session.cart.items && $cartSummary.count > 0}
        <div class="basket-popup_title">{$locale.cart}</div>
        {foreach from=$smarty.session.cart.items item="p" key='k'}
            <div class="basket-item clearfix">
                <div class="basket-item_img">
                    <div class="cell">
                        <a href="{$lang_suffix}/product/{if $p.parent_id != 0}{$p.parent_id}{else}{$k}{/if}">
                            {if $p.image}
                                <img src="/media/product/{if $p.parent_id != 0}{$p.parent_id}{else}{$k}{/if}_small.{$p.image}"
                                     alt="{$p.title}">
                            {else}
                                <img src="/template/images/placeholder_140.png" alt="{$p.title}">
                            {/if}
                        </a>
                    </div>
                </div>
                <div class="basket-item_info">
                    <div class="basket-item_name">
                        <a href="{$lang_suffix}/product/{if $p.parent_id != 0}{$p.parent_id}{else}{$k}{/if}-{$p.sef}">{if $p.parent_id != 0}{$p.parent_title}{else}{$p.title}{/if}</a>
                    </div>
                    <div class="basket-item_descr">
                        {if $p.parent_id != 0}
                            <p>{$p.title}</p>
                        {/if}

                        {if $p.color}
                            <span class="color" style="background-color: #{$p.color}"></span>
                        {/if}
                    </div>
                    <div class="basket-item_amount">
                        <span>{$locale.quantity}:<input data-id="{$k}" min="1" max="{$p.stock}" type="number"
                                                        class="number" value="{$p.qty}"></span>
                    </div>
                </div>
                <div class="basket-item_price product-price clearfix">
                    {if $p.sale_price > 0}
                        <span class="old-price">{$p.price} {$locale.uah}</span>
                        <b>{$p.sale_price} {$locale.uah}</b>
                    {else}
                        {if $p.qty > 1}
                            <span>{$p.price} {$locale.uah}</span>
                        {else}
                            <b>{$p.price} {$locale.uah}</b>
                        {/if}
                    {/if}
                    <div class="product-price">
                        {if $p.qty > 1}
                            <span>{$locale.sum}
                                : <b>{if $p.sale_price > 0}{$p.qty*$p.sale_price}{else}{$p.qty*$p.price}{/if}</b></span>
                        {/if}
                    </div>
                    <a data-id="{$k}" class="basket-item_del">{$locale.delete}</a>
                </div>
            </div>
        {/foreach}
        <div class="basket-popup_footer">
            {if $smarty.session.cart.totalDiscount}
                <div class="basket-popup_sales clearfix">
                    <p>{$locale.discount_club}: <b>({$smarty.session.cart.discount*100|price}%)</b></p>
                    <span>-<b>{$smarty.session.cart.totalDiscount|price}</b> {$locale.uah}</span>
                </div>
            {/if}
            <div class="basket-popup_total clearfix">
                <b>{$locale.total}:</b>
                <span><b>{$cartSummary.total}</b> <strong>{$locale.uah}</strong></span>
            </div>
            <div class="box-c clearfix">
                <a href="{$lang_suffix}/cart" class="btn btn-full ftr"><span>{$locale.go_to_cart}</span></a>
                <a href="#" class="btn-simple bascket-back ftl">{$locale.continue_shopping}</a>
            </div>
        </div>
    {else}
        <div class="basket-popup_title">{$locale.cart_is_empty}</div>
    {/if}


</div>


<div id="succes-form" class="simple-modal succes-form">
    <div class="simple-modal_title">Дякуємо!</div>
    <div class="simple-modal_descr">
        <p>Найблищим часом наш менеджер звяжеться з Вами для підтвердження замовлення</p>
    </div>
    <div class="box-c">
        <a href="" class="btn btn-full"><span>Продовжити</span></a>
    </div>
</div>

<div id="bonus-plus-modal" class="simple-modal bonus-plus-modal">
    <div class="simple-modal_title"><span class="bonus-plus_ic"></span>бонус плюс</div>
    <div class="simple-modal_descr">
        <p>В нашому інтернет-магазині Ви можете розрахуватись бонусами за програмою Приват Банку</p>
    </div>
</div>

<div id="pass-forget-form" class="simple-modal pass-forget-form">
    <div class="simple-modal_title">{$locale.get_new_pass}</div>
    <div class="pass-forget-form_wrapper">
        <form action="" id="pass-recovery">
            <fieldset class="input-item">
                <label for="pass-forget_email">Ваш email:</label>
                <input id="pass-forget_email" type="email" name="mail">
            </fieldset>
            <div class="box-c">
                <button type="submit" class="btn btn-full"><span>{$locale.send}</span></button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="/template/assets/js/libs.min.js"></script>
<script type="text/javascript" src="/template/assets/js/common.js"></script>
{*<script type="text/javascript" src="/lib/js/underscore.js"></script>*}
{*<script type="text/javascript" src="/template/js/jquery.nouislider.js"></script>*}

<script type="text/javascript" src="/template/js/index.js"></script>

<script src="/template/js/cart.js" type="text/javascript"></script>
<script src="/template/js/profile.js" type="text/javascript"></script>
<script src="/template/js/map.js" type="text/javascript"></script>

<script type="text/javascript" src="/template/lang/{$lang_code}.js"></script>


<script>
    window.onload = function () {
        var preloader = document.getElementById("preloader");
        preloader.className += 'load';
        document.body.classList.remove('menu-open');
        setTimeout(function () {
            document.body.removeChild(preloader);
        },500);
    }
</script>


</body>
</html>