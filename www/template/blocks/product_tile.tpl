{*<div class="item">*}
    {*<div class="image">*}
        {*<a href="{$lang_suffix}/product/{$p.id}-{$p.sef}">*}
            {*{if $p.image}*}
                {*<img src="/media/product/{$p.id}_small.{$p.image}" alt="{$p.title}">*}
            {*{else}*}
                {*<img src="/template/images/placeholder_140.png" alt="{$p.title}">*}
            {*{/if}*}
        {*</a>*}
    {*</div>*}
    {*{if $p.sale}*}
        {*<i class="icon icon-sale"></i>*}
    {*{/if}*}
    {*{if $p.new}*}
        {*<i class="icon icon-new"></i>*}
    {*{/if}*}
    {*{if $p.local_brand}*}
        {*<i class="icon icon-made-in-ukraine" title="{$locale.made_in_ukraine}"></i>*}
    {*{/if}*}
    {*<div class="title"><a href="{$lang_suffix}/product/{$p.id}-{$p.sef}">{$p.title}</a></div>*}
    {*<div class="price">*}
        {*{$p.min_price|price} {$locale.uah}*}
    {*</div>*}
    {*{if $p.instock}*}
        {*<button class="add-to-cart-trigger btn yellow" data-type="standalone" data-id="{$p.id}">{$locale.to_cart}</button>*}
    {*{else}*}
        {*<button class="contact-trigger btn gray" data-id="{$p.id}" data-mode="product">{$locale.waiting}</button>*}
    {*{/if}*}
{*</div>*}

<div class="col-sm-6 col-md-4 col-lg-3">
    <div class="one-good {if $p.sale}with-sale{/if} {if !$p.instock}unavailable{/if}">
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
        {*<div class="one-good_stars">*}
            {*<input type="radio" name="star" class="rating" value="1" />*}
            {*<input type="radio" name="star" class="rating" value="2" />*}
            {*<input type="radio" name="star" class="rating" value="3" />*}
            {*<input type="radio" name="star" class="rating" value="4" />*}
            {*<input type="radio" name="star" class="rating" value="5" />*}
        {*</div>*}
        <div class="one-good_price">
            <b>{$p.min_price|price} {$locale.uah}</b>
        </div>
        <div class="one-good_footer">
            {if $p.instock}
                <button class="add-to-cart-trigger btn" data-type="standalone" data-id="{$p.id}">
                    <span>{$locale.to_cart}</span>
                </button>
            {else}
                <button class="contact-trigger btn btn-disabled" disabled data-id="{$p.id}" data-mode="product">
                    <span>{$locale.waiting}</span>
                </button>
            {/if}
        </div>
    </div>
</div>