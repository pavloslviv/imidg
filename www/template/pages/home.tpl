<section class="main-slider">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="main-slider_wrapper">
                    {foreach from=$slides item="slide"}
                            <a href="{$slide.link}">
                                <img src="/media/blocks/{$slide.id}.jpg" alt="">
                            </a>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
</section>

{if count($product_new)}
    <section class="product-carousel new-products">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-carousel_title">
                        <h3>{$locale.new_and_sale}</h3>
                    </div>
                    <div class="product-carousel_wrapper">
                        {foreach from=$product_new item="p"}
                            <div class="one-good {if $p.sale}with-sale{/if}">

                                {if $p.sale}
                                    <div class="one-good_ribbon sale">{$locale.sale}</div>
                                {/if}
                                {if $p.new}
                                    <div class="one-good_ribbon new">{$locale.new}</div>
                                {/if}

                                <div class="one-good_img">
                                    <div class="cell">
                                        <a href="{$lang_suffix}/product/{$p.id}-{$p.sef}">
                                            {if $p.image}
                                                <img src="/media/product/{$p.id}_small.{$p.image}" alt="{$p.title}">
                                            {else}
                                                <img src="/template/images/placeholder_140.png" alt="{$p.title}">
                                            {/if}
                                        </a>
                                    </div>
                                </div>

                                <div class="one-good_name">
                                    <a href="{$lang_suffix}/product/{$p.id}-{$p.sef}">{$p.title}</a>
                                </div>

                                <!--<div class="one-good_stars">
                                    <input type="radio" name="star" class="rating" value="1" />
                                    <input type="radio" name="star" class="rating" value="2" />
                                    <input type="radio" name="star" class="rating" value="3" />
                                    <input type="radio" name="star" class="rating" value="4" />
                                    <input type="radio" name="star" class="rating" value="5" />
                                </div>-->

                                <div class="one-good_price">
                                    <b>{$p.min_price|price} {$locale.uah}</b>
                                </div>

                                <div class="one-good_footer">
                                    {if $p.instock}
                                        <button class="add-to-cart-trigger btn" data-type="standalone"
                                                data-id="{$p.id}">
                                            <span>{$locale.to_cart}</span>
                                        </button>
                                    {else}
                                        <button class="contact-trigger btn btn-disabled" data-id="{$p.id}"
                                                data-mode="product">
                                            <span>{$locale.waiting}</span>
                                        </button>
                                    {/if}
                                    {*<a href="#" class="btn to-favorite">*}
                                    {*<i class="fa fa-heart-o before" aria-hidden="true"></i>*}
                                    {*<i class="fa fa-heart after" aria-hidden="true"></i>*}
                                    {*</a>*}
                                </div>
                            </div>
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
    </section>
{/if}

{if count($product_hit)}
    <section class="product-carousel hit-products">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-carousel_title">
                        <h3>{$locale.hits}</h3>
                    </div>
                    <div class="product-carousel_wrapper">

                        {foreach from=$product_hit item="p"}
                            <div class="one-good {if $p.sale}with-sale{/if}">

                                {if $p.sale}
                                    <div class="one-good_ribbon sale">{$locale.sale}</div>
                                {/if}
                                {if $p.new}
                                    <div class="one-good_ribbon new">{$locale.new}</div>
                                {/if}

                                <div class="one-good_img">
                                    <div class="cell">
                                        <a href="{$lang_suffix}/product/{$p.id}-{$p.sef}">
                                            {if $p.image}
                                                <img src="/media/product/{$p.id}_small.{$p.image}" alt="{$p.title}">
                                            {else}
                                                <img src="/template/images/placeholder_140.png" alt="{$p.title}">
                                            {/if}
                                        </a>
                                    </div>
                                </div>

                                <div class="one-good_name">
                                    <a href="{$lang_suffix}/product/{$p.id}-{$p.sef}">{$p.title}</a>
                                </div>

                                <!--<div class="one-good_stars">
                                    <input type="radio" name="star" class="rating" value="1" />
                                    <input type="radio" name="star" class="rating" value="2" />
                                    <input type="radio" name="star" class="rating" value="3" />
                                    <input type="radio" name="star" class="rating" value="4" />
                                    <input type="radio" name="star" class="rating" value="5" />
                                </div>-->

                                <div class="one-good_price">
                                    <b>{$p.min_price|price} {$locale.uah}</b>
                                </div>

                                <div class="one-good_footer">
                                    {if $p.instock}
                                        <button class="add-to-cart-trigger btn" data-type="standalone"
                                                data-id="{$p.id}">
                                            <span>{$locale.to_cart}</span>
                                        </button>
                                    {else}
                                        <button class="contact-trigger btn btn-disabled" data-id="{$p.id}"
                                                data-mode="product">
                                            <span>{$locale.waiting}</span>
                                        </button>
                                    {/if}
                                    {*<a href="#" class="btn to-favorite">*}
                                    {*<i class="fa fa-heart-o before" aria-hidden="true"></i>*}
                                    {*<i class="fa fa-heart after" aria-hidden="true"></i>*}
                                    {*</a>*}
                                </div>
                            </div>
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
    </section>
{/if}


<section class="order-procedur">

    <div class="sect-title inner-color">
        <h3>{$locale.procedure_order}</h3>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-lg-offset-1 col-md-6 col-sm-6">
                <div class="order-procedur_item">
                    <span>1</span>
                    <p>{$locale.procedure_one}</p>
                </div>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-6">
                <div class="order-procedur_item">
                    <span>2</span>
                    <p>{$locale.procedure_two}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-lg-offset-1 col-md-6 col-sm-6">
                <div class="order-procedur_item">
                    <span>3</span>
                    <p>{$locale.procedure_tree}</p>
                </div>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-6">
                <div class="order-procedur_item">
                    <span>4</span>
                    <p>{$locale.procedure_four}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-lg-offset-1 col-md-6 col-sm-6">
                <div class="order-procedur_item inner-item">
                    <span class="icon-delivery"></span>
                    <b>{$locale.delivery}</b>
                    {$locale.shipping_content}
                </div>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-6">
                <div class="order-procedur_item inner-item">
                    <span class="icon-payment"></span>
                    <b>{$locale.payment}</b>
                    {$locale.payment_content}
                </div>
            </div>
        </div>
    </div>
</section>


{*<div id="home">*}
{*<script type="text/javascript">*}
{*$(SerenityShop.activateSlider);*}
{*</script>*}
{*<div class="product-list">{foreach from=$products item="p"}{include file="blocks/product_tile.tpl"}{/foreach}</div>*}
{*</div>*}