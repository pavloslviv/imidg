<section>
    <div class="container search-container">
        <div class="row">
            <div class="col-md-12">
                <div class="sect-title">
                    <h3>{$locale.search}</h3>
                </div>
            </div>
            <div class="col-md-12">
                <div class="header-search">
                    <form id="main-search" action="{$lang_suffix}/search">
                        <input type="search" name="query" class="search-input"value="{$query}" placeholder="{$locale.search_placeholder}">
                        <button type="submit" class="search-btn">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-md-12 box-c">
                {if $products}

                    {foreach from=$products item="p"}
                        {include file="blocks/product_tile.tpl"}
                    {/foreach}

                {elseif mb_strlen($query)<3}
                    <p class="search-error">{$locale.search_min_length}</p>
                {else}
                    <p class="search-error">{$locale.search_request} <span>"{$query}"</span> {$locale.search_nothing_found}</p>
                {/if}
            </div>
            {if $page_count > 1}
            <div class="col-md-12">
                <div class="pagination with-number">

                    <ul>
                        {section loop=$page_count name="p"}
                            {if $smarty.section.p.iteration!=$page_current}
                                <li>
                                    <a href="{$lang_suffix}/search{if $smarty.section.p.iteration>1}/page-{$smarty.section.p.iteration}{/if}{$current_filters}">{$smarty.section.p.iteration}</a>
                                </li>

                            {else}
                                <li class="active">
                                    <b>{$smarty.section.p.iteration}</b>
                                </li>
                            {/if}
                        {/section}
                    </ul>

                    <a {if $page_current != 1}href="{$lang_suffix}/search{if $page_current>2}/page-{$page_current-1}{/if}{$current_filters}"{/if} class="pagination_arrow left {if $page_current == 1}disabled{/if}">
                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                    </a>

                    <a {if $page_current!=$page_count}href="{$lang_suffix}/search{if $page_current>0}/page-{$page_current+1}{/if}{$current_filters}"{/if} class="pagination_arrow right {if $page_current==$page_count}disabled{/if}">
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            {/if}
        </div>
    </div>
</section>
