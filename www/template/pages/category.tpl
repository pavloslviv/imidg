{*<div class="filters">*}
    {*<div id="brand-alphabet" class="filter">*}
        {*<strong class="title">{$locale.brand_by_letter}:</strong>*}
        {*<div class="options"></div>*}
    {*</div>*}
    {*{foreach from=$filters item="filter" key="filterId"}*}
        {*<div id="filter_{$filterId}" class="filter">*}
            {*<strong class="title">{$section.options[$filterId].title}:</strong>*}
            {*<div class="options">*}
            {*{foreach from=$filter item="o"}*}
                {*<a href="{$lang_suffix}{$o.link}" class="option {if $o.active} active{/if}">{$o.value}<span> {$o.cnt}</span></a>*}
            {*{/foreach}*}
            {*</div>*}
        {*</div>*}
    {*{/foreach}*}
    {*<div id="price-filter" class="filter">*}
        {*<strong class="title">{$locale.price}:</strong>*}
        {*<div class="options">*}
            {*<div class="option"><div id="price-range" data-from="{$priceFilter.from}" data-to="{$priceFilter.to}" data-start="{$priceFilter.start}" data-end="{$priceFilter.end}"></div></div>*}
            {*<div id="current-price">*}
                {*{$locale.from} <input type="number" min="{$priceFilter.from}" class="from" value="{$priceFilter.start}" /> {$locale.uah} &mdash;*}
                {*{$locale.to} <input type="number" max="{$priceFilter.to}" class="to" value="{$priceFilter.end}" /> {$locale.uah}*}
                {*<button id="apply-price-filter" type="button" class="btn btn-red">{$locale.show}</button>*}
            {*</div>*}
        {*</div>*}
    {*</div>*}
{*</div>*}


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="sect-title">
                <h3>{$b_title}</h3>
            </div>
        </div>
        <div class="col-md-3 filter-append">
            <a href="#open-filter" class="btn show-filter open-modal"><span>{$locale.filter}</span></a>
            <div class="catalog-filter">
                <div class="simple-modal_title">{$locale.filter}</div>
                <!--<div class="catalog-filter_item">
                    <div class="catalog-filter_title">сортувати:</div>
                    <div class="catalog-filter_box">
                        <div class="dropdown sort-dropdown">
                            <select name="sorting-select" id="sorting-select">
                                <option value="">По найменуванню: A-Z</option>
                                <option value="">По найменуванню: Z-A</option>
                                <option value="">По ціні: від дешевих до дорогих</option>
                                <option value="">По ціні: від дорогих до дешевих</option>
                                <option value="">По популярності: спочатку популярні</option>
                                <option value="">По популярності: спочатку не популярні</option>
                                <option value="">По даті додавання: від нових до старих</option>
                                <option value="">По даті додавання: від старих до нових</option>
                            </select>
                        </div>
                    </div>
                </div>-->


                <div class="catalog-filter_item" id="brand-alphabet">
                    <div class="catalog-filter_title">{$locale.brand_by_letter}:</div>
                    <div class="catalog-filter_box">
                        <div class="letter-sorting"></div>
                    </div>
                </div>


                {foreach from=$filters item="filter" key="filterId"}

                <div class="catalog-filter_item" id="filter_{$filterId}">
                    <div class="catalog-filter_title">{$section.options[$filterId].title}:</div>
                    <div class="catalog-filter_box">
                        <div class="filter-list with-scroll">
                            <ul>
                                {foreach from=$filter item="o"}
                                    <li>
                                        <a href="{$lang_suffix}{$o.link}" class="checkbox {if $o.active} checked{/if}">{$o.value}
                                            <span>({$o.cnt})</span></a>
                                    </li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                </div>
                {/foreach}

                <div class="catalog-filter_item" id="price-filter">
                    <div class="catalog-filter_title">{$locale.price}:</div>
                    <div class="catalog-filter_box">
                        <div class="filter-price">
                            <div class="filter-price_slider" id="price-range" data-from="{$priceFilter.from}" data-to="{$priceFilter.to}" data-start="{$priceFilter.start}" data-end="{$priceFilter.end}"></div>
                            <div class="filter-price_inputs" id="current-price">
                                <span>{$locale.from}</span>
                                <input type="number" id="minCost" class="from" min="{$priceFilter.from}" value="{$priceFilter.start}">
                                <span>{$locale.to}</span>
                                <input type="number" id="maxCost" class="to" max="{$priceFilter.to}" value="{$priceFilter.end}">
                                <span>{$locale.uah}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-l">
                    <button type="button" class="btn filter-skip" id="apply-price-filter"><span>{$locale.show}</span></button>
                </div>

                {*<div id="price-filter" class="filter">*}
                    {*<strong class="title">{$locale.price}:</strong>*}
                    {*<div class="options">*}
                        {*<div class="option"><div id="price-range" data-from="{$priceFilter.from}" data-to="{$priceFilter.to}" data-start="{$priceFilter.start}" data-end="{$priceFilter.end}"></div></div>*}
                        {*<div id="current-price">*}
                            {*{$locale.from} <input type="number" min="{$priceFilter.from}" class="from" value="{$priceFilter.start}" /> {$locale.uah} &mdash;*}
                            {*{$locale.to} <input type="number" max="{$priceFilter.to}" class="to" value="{$priceFilter.end}" /> {$locale.uah}*}
                            {*<button id="apply-price-filter" type="button" class="btn btn-red">{$locale.show}</button>*}
                        {*</div>*}
                    {*</div>*}
                {*</div>*}

                {*<div class="box-l">*}
                    {*<a href="#" class="btn filter-skip"><span>{$locale.show}</span></a>*}
                {*</div>*}


            </div>
        </div>

        <div class="col-md-9">
            <div class="catalog-wrapper clearfix">
                <div class="row">
                    {if $products}
                        {foreach from=$products item="p"}
                            {include file="blocks/product_tile.tpl"}
                        {/foreach}
                    {else}
                        <div class="box-c cms-content">
                            <h1>{$locale.product_not_in_category}</h1>
                        </div>
                    {/if}
                </div>
            </div>
        </div>


        {if $page_count>1}
            <div class="col-md-12">
                <div class="pagination with-number">
                    <ul>
                        {section loop=$page_count name="p"}
                            {if $smarty.section.p.iteration!=$page_current}

                                <li><a href="{$lang_suffix}/category/{$section.sef}{if $smarty.section.p.iteration>1}/page-{$smarty.section.p.iteration}{/if}{$current_filters}">{$smarty.section.p.iteration}</a></li>
                            {else}
                                <li class="active"><b>{$smarty.section.p.iteration}</b></li>
                            {/if}
                        {/section}
                    </ul>

                    <a {if $page_current != 1}href="{$lang_suffix}/category/{$section.sef}{if $page_current>2}/page-{$page_current-1}{/if}{$current_filters}"{/if} class="pagination_arrow left {if $page_current == 1}disabled{/if}">
                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                    </a>

                    <a {if $page_current!=$page_count}href="{$lang_suffix}/category/{$section.sef}{if $page_current>0}/page-{$page_current+1}{/if}{$current_filters}"{/if} class="pagination_arrow right {if $page_current==$page_count}disabled{/if}">
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        {/if}
    </div>
</div>


<script type="text/javascript">

    setTimeout(function(){
        SerenityShop.initBrandAlphabet();
//        SerenityShop.initBrandsMore();
    },1000)

</script>



