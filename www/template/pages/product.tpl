<section class="product-details">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="product clearfix">
                    <div class="product-img">
                        {if $product.new}
                            <div class="product-ribbon new">{$locale.new}</div>
                        {/if}

                        {if $product.sale}
                            <div class="product-ribbon sale">{$locale.sale}</div>
                        {/if}

                        {if $product.image}
                        <a class="cell fancybox-image" title="{$product.title}" href="/media/product/{$product.id}.{$product.image}">
                            <img src="/media/product/{$product.id}_medium.{$product.image}" alt="{$product.title}">
                        </a>
                        {else}
                            <div class="cell">
                                <img src="/template/images/placeholder_400.jpg" alt="{$product.title}">
                            </div>
                        {/if}
                    </div>
                    <div class="product-info">
                        <div class="product-name">
                            <h1>{$product.title}</h1>
                        </div>
                        {if $product.modifications}
                            {foreach $product.modifications as $i => $mod}
                                <div class="product-descr product-to-cash" item-id="{$mod.id}" {if $i > 0}style="display: none;"{/if}>
                                    <p>{$mod.title}</p>
                                </div>
                                <div class="product-availability product-to-cash
                                    {if (($mod.stock - $mod.reserved) > 0) || $mod.instock && $mod.active==1}available{/if}"
                                     item-id="{$mod.id}"
                                     {if $i > 0}style="display: none;"{/if}>
                                    {if (($mod.stock - $mod.reserved) > 0) || $mod.instock && $mod.active==1}{$locale.product_in_stock}{else}{$locale.waiting}{/if}
                                </div>
                            {/foreach}

                        {else}
                            <div class="product-availability {if (($product.stock - $product.reserved) > 0) || $product.instock && $product.active==1}available{/if}">
                                {if (($product.stock - $product.reserved) > 0) || $product.instock && $product.active==1}{$locale.product_in_stock}{else}{$locale.waiting}{/if}
                            </div>
                        {/if}


                        {*<div class="product-stars clearfix">*}
                            {*<div class="rating-stars">*}
                                {*<input type="radio" name="star" class="rating" value="1" />*}
                                {*<input type="radio" name="star" class="rating" value="2" />*}
                                {*<input type="radio" name="star" class="rating" value="3" />*}
                                {*<input type="radio" name="star" class="rating" value="4" />*}
                                {*<input type="radio" name="star" class="rating" value="5" />*}
                            {*</div>*}
                            {*<a href="#" class="review-count">135 відгуків</a>*}
                        {*</div>*}
                        {if $product.modifications}
                            <div class="product-param">
                                {foreach $product.modifications as $i => $mod}
                                    {assign var="first_option" value=reset($mod.options)}

                                    <a item-id="{$mod.id}"
                                        {if $i == 0}class="active"{/if}
                                    >

                                        {if $first_option.value}
                                            {$first_option.value}
                                        {elseif $mod.color}
                                            {$mod.title}
                                        {else}
                                            {$mod.title}
                                        {/if}

                                        {if $mod.color}
                                            <span class="color" style="background-color: #{$mod.color}"></span>
                                        {/if}
                                    </a>
                                {/foreach}
                            </div>

                            {foreach $product.modifications as $i => $mod}
                                <div item-id="{$mod.id}" class="product-to-cash" {if $i > 0}style="display: none;"{/if}>
                                    <div class="product-price">

                                        {if $mod.sale_price}
                                            <span class="old-price">{$mod.price|price} {$locale.uah}</span>
                                            <b>{$mod.sale_price|price} {$locale.uah}</b>
                                        {else}
                                            <b>{$mod.price|price} {$locale.uah}</b>
                                        {/if}
                                        {if $mod.color}
                                            <span class="product-color" style="background-color: #{$mod.color}"></span>
                                        {/if}
                                    </div>

                                    <div class="product-price">
                                        <input type="number" class="number" name="qty" min="1" max="{$mod.stock}" data-id="{$mod.id}" value="1"/>
                                    </div>

                                    <div class="product-buy">
                                        <button class="add-to-cart-trigger btn-full btn"
                                                {if (($mod.stock - $mod.reserved) <= 0)}disabled{/if}
                                                type="submit" data-type="form" data-id="{$mod.id}">
                                            <span>{if (($mod.stock - $mod.reserved) > 0)}{$locale.to_cart}{else}{$locale.waiting}{/if}</span>
                                        </button>
                                        {*<a href="#" class="btn to-favorite">*}
                                        {*<i class="fa fa-heart-o before" aria-hidden="true"></i>*}
                                        {*<i class="fa fa-heart after" aria-hidden="true"></i>*}
                                        {*</a>*}
                                    </div>


                                    {if (($mod.stock - intval($mod.reserved)) > 0)}
                                        <div class="product-oneclick">
                                            <a href="#buy-oneclick" data-type="form" class="btn-simple open-modal" data-id="{$mod.id}">{$locale.one_click}</a>
                                        </div>
                                    {/if}
                                </div>
                            {/foreach}

                        {else}

                            <div class="product-to-cash">
                                <div class="product-price">

                                    {if (count($product.modifications) && $product.modifications[0].sale_price) || $product.sale_price}
                                        <span class="old-price">{$product.price|price} {$locale.uah}</span>
                                        <b>{$product.sale_price|price} {$locale.uah}</b>
                                    {else}
                                        <b>{$product.price|price} {$locale.uah}</b>
                                    {/if}
                                </div>

                                <div class="product-price">
                                    <input type="number" class="number" name="qty" min="1" data-id="{$product.id}" value="1"/>
                                </div>


                                <div class="product-buy">
                                    <button class="{if $product.instock && $product.active==1}add-to-cart-trigger btn-full{else}contact-trigger btn-disabled{/if} btn " type="submit" data-type="form" data-id="{$product.id}"><span>{if $product.instock}{$locale.to_cart}{else}{$locale.waiting}{/if}</span></button>
                                </div>
                                {if $product.instock && $product.active==1}
                                    <div class="product-oneclick">
                                        <a href="#buy-oneclick" data-type="form" class="btn-simple open-modal" data-id="{$product.id}">{$locale.one_click}</a>
                                    </div>
                                {/if}
                            </div>

                        {/if}

                        {include file="blocks/social_panel_small.tpl"}

                    </div>
                    <div class="product-tabs">
                        <div class="product-tabs_links">
                            <ul class="clearfix">
                                <li><a href="#" class="tab-link">{$locale.product_description}<i class="icon-2-down"></i></a></li>
                                <li><a href="#" class="tab-link">{$locale.reviews}<i class="icon-2-down"></i></a></li>
                                <li><a href="#" class="tab-link">{$locale.product_specifications}<i class="icon-2-down"></i></a></li>
                            </ul>
                        </div>
                        <div class="product-tabs_content">
                            <div class="one-tab tab-descr">
                                <article>
                                    {$product.description}
                                </article>
                            </div>
                            <div class="one-tab tab-reviews">
                                <div id="disqus_thread"></div>
                                <script>

                                    /**
                                     *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                                     *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
                                    /*
                                     var disqus_config = function () {
                                     this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
                                     this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                                     };
                                     */
                                    (function() { // DON'T EDIT BELOW THIS LINE
                                        var d = document, s = d.createElement('script');
                                        s.src = '//imidg-1.disqus.com/embed.js';
                                        s.setAttribute('data-timestamp', +new Date());
                                        (d.head || d.body).appendChild(s);
                                    })();
                                </script>
                                <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

                            </div>
                            <div class="one-tab tab-options">
                                <div class="product-options">
                                    <table>
                                        {foreach from=$product.options item="option"}
                                            {if !$option.locked && $option.value}
                                                <tr>
                                                    <td><b>{$option.title}</b></td>
                                                    <td>{$option.value}</td>
                                                </tr>
                                            {/if}
                                        {/foreach}
                                    </table>
                                </div>
                            </div>

                            <!--<div class="one-tab tab-reviews">
                                <div class="box-c">
                                    <a href="#comment-popup" class="btn add-comment open-modal"><span>Залишити відгук</span></a>
                                </div>
                                <div class="product-reviews">
                                    <div class="one-review clearfix">
                                        <div class="one-review_left">
                                            <div class="one-review_name">Олена, Львів</div>
                                            <div class="one-review_date"> 06 Жовтень 2016</div>
                                        </div>
                                        <div class="one-review_right">
                                            <div class="one-review_descr">
                                                <p>Товар дуже якісний. Запах тримається довго. Навіть довше ніж очікувала) Дякую за швидку доставку.</p>
                                            </div>
                                            <div class="one-review_stars">
                                                <div class="rating-stars">
                                                    <input type="radio" name="star" class="rating" value="1" />
                                                    <input type="radio" name="star" class="rating" value="2" />
                                                    <input type="radio" name="star" class="rating" value="3" />
                                                    <input type="radio" name="star" class="rating" value="4" />
                                                    <input type="radio" name="star" class="rating" value="5" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="one-review clearfix">
                                        <div class="one-review_left">
                                            <div class="one-review_name">Олена, Львів</div>
                                            <div class="one-review_date"> 06 Жовтень 2016</div>
                                        </div>
                                        <div class="one-review_right">
                                            <div class="one-review_descr">
                                                <p>Товар дуже якісний. Запах тримається довго. Навіть довше ніж очікувала) Дякую за швидку доставку.</p>
                                            </div>
                                            <div class="one-review_stars">
                                                <div class="rating-stars">
                                                    <input type="radio" name="star" class="rating" value="1" />
                                                    <input type="radio" name="star" class="rating" value="2" />
                                                    <input type="radio" name="star" class="rating" value="3" />
                                                    <input type="radio" name="star" class="rating" value="4" />
                                                    <input type="radio" name="star" class="rating" value="5" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="one-review clearfix">
                                        <div class="one-review_left">
                                            <div class="one-review_name">Олена, Львів</div>
                                            <div class="one-review_date"> 06 Жовтень 2016</div>
                                        </div>
                                        <div class="one-review_right">
                                            <div class="one-review_descr">
                                                <p>Товар дуже якісний. Запах тримається довго. Навіть довше ніж очікувала) Дякую за швидку доставку.</p>
                                            </div>
                                            <div class="one-review_stars">
                                                <div class="rating-stars">
                                                    <input type="radio" name="star" class="rating" value="1" />
                                                    <input type="radio" name="star" class="rating" value="2" />
                                                    <input type="radio" name="star" class="rating" value="3" />
                                                    <input type="radio" name="star" class="rating" value="4" />
                                                    <input type="radio" name="star" class="rating" value="5" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="one-review clearfix">
                                        <div class="one-review_left">
                                            <div class="one-review_name">Олена, Львів</div>
                                            <div class="one-review_date"> 06 Жовтень 2016</div>
                                        </div>
                                        <div class="one-review_right">
                                            <div class="one-review_descr">
                                                <p>Товар дуже якісний. Запах тримається довго. Навіть довше ніж очікувала) Дякую за швидку доставку.</p>
                                            </div>
                                            <div class="one-review_stars">
                                                <div class="rating-stars">
                                                    <input type="radio" name="star" class="rating" value="1" />
                                                    <input type="radio" name="star" class="rating" value="2" />
                                                    <input type="radio" name="star" class="rating" value="3" />
                                                    <input type="radio" name="star" class="rating" value="4" />
                                                    <input type="radio" name="star" class="rating" value="5" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="one-review clearfix">
                                        <div class="one-review_left">
                                            <div class="one-review_name">Олена, Львів</div>
                                            <div class="one-review_date"> 06 Жовтень 2016</div>
                                        </div>
                                        <div class="one-review_right">
                                            <div class="one-review_descr">
                                                <p>Товар дуже якісний. Запах тримається довго. Навіть довше ніж очікувала) Дякую за швидку доставку.</p>
                                            </div>
                                            <div class="one-review_stars">
                                                <div class="rating-stars">
                                                    <input type="radio" name="star" class="rating" value="1" />
                                                    <input type="radio" name="star" class="rating" value="2" />
                                                    <input type="radio" name="star" class="rating" value="3" />
                                                    <input type="radio" name="star" class="rating" value="4" />
                                                    <input type="radio" name="star" class="rating" value="5" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pagination with-number">
                                    <ul>
                                        <li class="active"><b>1</b></li>
                                        <li><a href="#">2</a></li>
                                        <li><a href="#">3</a></li>
                                        <li><a href="#">4</a></li>
                                        <li>...</li>
                                        <li><a href="#">10</a></li>
                                    </ul>
                                    <a href="#" class="pagination_arrow left disabled">
                                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" class="pagination_arrow right">
                                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        {*<a href="#" class="product-nav icon-left-arrow left"></a>*}
        {*<a href="#" class="product-nav icon-left-arrow right"></a>*}
    </div>
</section>


<section class="section-subscribe">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="subscribe-wrapper">
                    <div class="subscribe-title">{$locale.first_news_shares}</div>
                    <div class="subscribe-form">
                        <form action="" class="subscibe_form">
                            <input type="hidden" name="mode" value="subscribe" />

                            <input type="email" name="mail" placeholder="Ваш e-mail" required>
                            <button type="submit">{$locale.send}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




{*<div class="product-container">*}
    {*<div class="image">*}
        {*{if $product.sale}*}
            {*<i class="icon icon-sale"></i>*}
        {*{/if}*}
        {*{if $product.new}*}
            {*<i class="icon icon-new"></i>*}
        {*{/if}*}
        {*{if $product.local_brand}*}
            {*<i class="icon icon-made-in-ukraine" title="{$locale.made_in_ukraine}"></i>*}
        {*{/if}*}
        {*{if $product.image}*}
            {*<a class="fancy-image" href="/media/product/{$product.id}.{$product.image}">*}
            {*<img src="/media/product/{$product.id}_medium.{$product.image}" alt="{$product.title}">*}
            {*</a>*}
        {*{else}*}
            {*<img src="/template/images/placeholder_400.jpg" alt="{$product.title}">*}
        {*{/if}*}
    {*</div>*}
    {*<div class="info">*}
        {*<h1 class="title">{$product.title}</h1>*}
        {*{if $product.offer neq 0}*}
            {*<h2>{$locale.offer} -{$product.offer}%</h2>*}
        {*{/if}*}
        {*{foreach from=$product.options item="option"}*}
            {*{if !$option.locked && $option.value}*}
                {*<div class="option"><strong>{$option.title}</strong> - {$option.value}</div>*}
            {*{/if}*}
        {*{/foreach}*}
        {*<div class="description">{$product.description}</div>*}
        {*{if $product.dayTime}*}
            {*<div class="day-time"><span><i class="icon icon-aroma-{$product.dayTime.value}"></i></span> {$product.dayTime.text} {$locale.aroma}</div>*}
        {*{/if}*}
        {*{if $product.season}*}
            {*<div class="season"><span><i class="icon icon-aroma-{$product.season.value}"></i></span> {$loacale.best_season} {$product.season.text}</div>*}
        {*{/if}*}
        {*{include file="blocks/social_panel.tpl"}*}
        {*{if !$product.modifications}*}
            {*<div id="product-order-form">*}
                {*<div id="product-price">*}
                    {*<span class="regular {if (count($product.modifications) && $product.modifications[0].sale_price) || $product.sale_price}strike{/if}">*}
                        {*{if count($product.modifications)}*}
                            {*{$product.modifications[0].price|price} {$locale.uah}*}
                        {*{else}*}
                            {*{$product.price|price} {$locale.uah}*}
                        {*{/if}*}
                    {*</span>*}
                    {*<span class="sale">*}
                        {*{if count($product.modifications) && $product.modifications[0].sale_price}*}
                            {*{$product.modifications[0].sale_price|price} {$locale.uah}*}
                        {*{elseif $product.sale_price}*}
                            {*{$product.sale_price|price} {$locale.uah}*}
                        {*{/if}*}
                    {*</span>*}
                {*</div>*}
                {*{if $product.stock && $product.active==1}*}
                    {*<div id="product-controls" class="order-item">*}
                        {*<span class="product-qty"><input type="number" name="qty" min="1" value="1"/> шт.</span>*}
                        {*<button class="add-to-cart-trigger btn yellow" type="submit" data-type="form" data-id="{$product.id}">{$locale.to_cart}</button>*}
                        {*<button class="one-click-trigger btn red" type="submit" data-type="form" data-id="{$product.id}">{$locale.one_click}</button>*}
                    {*</div>*}
                {*{/if}*}
            {*</div>*}
        {*{/if}*}
    {*</div>*}
    {*{if $product.modifications}*}
        {*<div id="product-order-form" class="modifications" action="">*}
            {*<table id="product-modifications" name="modification">*}
                {*{foreach from=$product.modifications item="mod" key="index"}*}
                    {*<tr data-id="{$mod.id}" class="order-item">*}
                        {*{if $mod.color}*}
                            {*<td class="modification-title color-sample">*}
                                {*<div class="option-color" style="background-color: #{$mod.color}"></div>*}
                                {*{$mod.title}*}
                            {*</td>*}
                        {*{else}*}
                            {*<td class="modification-title">{$mod.title}</td>*}
                        {*{/if}*}
                        {*<td>*}
                            {*<span class="regular{if $mod.sale_price} strike{/if}">{$mod.price|price} {$locale.uah}</span>*}
                        {*</td>*}
                        {*<td>*}
                            {*{if $mod.sale_price}*}
                                {*<span class="sale">{$mod.sale_price|price} {$locale.uah}</span>*}
                            {*{else}*}
                                {*&nbsp;*}
                            {*{/if}*}
                        {*</td>*}
                        {*<td><span class="product-qty"><input type="number" name="qty" min="1" value="1"/> шт.</span></td>*}
                        {*<td><button class="add-to-cart-trigger btn yellow" type="submit" data-type="form" data-id="{$mod.id}">{$locale.to_cart}</button></td>*}
                        {*<td><button class="one-click-trigger btn red" type="submit" data-type="form" data-id="{$mod.id}">{$locale.one_click}</button></td>*}
                    {*</tr>*}
                {*{/foreach}*}
            {*</table>*}
        {*</div>*}
    {*{/if}*}
{*</div>*}



{*<script type="text/javascript">*}
    {*SerenityShop.data.productModifications = {$product.modifications|json_encode};*}
{*</script>*}
{*<script type="text/javascript" src="/lib/js/fancybox/jquery.fancybox.pack.js"></script>*}
{*<script type="text/javascript" src="//static.addtoany.com/menu/page.js"></script>*}
{*<link rel="stylesheet" href="/lib/js/fancybox/jquery.fancybox.css"/>*}
{*{literal}*}
{*<script type="text/javascript">*}
    {*$(function(){*}
        {*$('.fancy-image').fancybox({*}
            {*openEffect	: 'elastic',*}
            {*closeEffect	: 'elastic',*}
            {*padding: 0,*}
            {*helpers : {*}
                {*title : {*}
                    {*type : 'inside'*}
                {*}*}
            {*}*}
        {*});*}
    {*});*}
{*</script>*}
{*{/literal}*}

<!--Popup buy in one click-->
<div id="buy-oneclick" class="simple-modal buy-oneclick">
    <div class="simple-modal_title">{$locale.one_click}</div>
    <div class="oneclick-form">
        <form action="" id="one-click-form">
            <fieldset>
                <label for="one-click-phone">{$locale.phone}:</label>
                {if $smarty.session.customer.phone|count_characters > 1}
                    <input class="text" id="one-click-phone" type="tel" name="phone" value="{$smarty.session.customer.phone}" required/>
                {else}
                    <input class="text" id="one-click-phone" type="tel" name="phone" required/>
                {/if}
            </fieldset>
            <fieldset>
                <label for="one-click-name">{$locale.your_name}</label>
                <input class="text" id="one-click-name" type="text" name="name" required/>
            </fieldset>
            <input type="hidden" id="one-click-product-id" name="id">
            <div class="box-c">
                <button type="submit" class="btn btn-full"><span>{$locale.confirm}</span></button>
            </div>
        </form>
    </div>
</div>

<div id="comment-popup" class="comment-popup">
    <div class="comment-popup_form clearfix">
        <form action="">
            <div class="comment-popup_left">
                <fieldset class="input-item">
                    <label for="comment-name">Ваше ім’я</label>
                    <input id="comment-name" type="text">
                </fieldset>
                <fieldset class="input-item">
                    <label for="comment-email">Ваш E-mail</label>
                    <input id="comment-email" type="email">
                </fieldset>
                <fieldset class="input-item">
                    <label for="comment-city">Ваше місто</label>
                    <input id="comment-city" type="text">
                </fieldset>
            </div>
            <div class="comment-popup_right">
                <fieldset class="input-item">
                    <label for="comment-text">Повідомлення</label>
                    <textarea id="comment-text"></textarea>
                </fieldset>
            </div>
            <div class="comment-popup_footer">
                <div class="comment-popup_stars">
                    <span>Ваша оцінка</span>
                    <div class="rating-stars">
                        <input type="radio" name="star" class="rating" value="1" />
                        <input type="radio" name="star" class="rating" value="2" />
                        <input type="radio" name="star" class="rating" value="3" />
                        <input type="radio" name="star" class="rating" value="4" />
                        <input type="radio" name="star" class="rating" value="5" />
                    </div>
                </div>
                <div class="comment-popup_btn">
                    <button type="submit" class="btn btn-full"><span>Надіслати відгук</span></button>
                </div>
            </div>
        </form>
    </div>
</div>

<script id="dsq-count-scr" src="//imidg-1.disqus.com/count.js" async></script>