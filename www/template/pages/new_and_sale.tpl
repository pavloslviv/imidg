

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="sect-title">
                <h3>{$locale.new_and_sale}</h3>
            </div>
        </div>
        <div class="col-md-12">
            <div class="catalog-wrapper clearfix">
                <div class="row">
                    {foreach from=$products item="p"}
                        {include file="blocks/product_tile.tpl"}
                    {/foreach}
                </div>
            </div>
        </div>
        {if $page_count>1}
            {include file="blocks/pagination.tpl" baseURL='new_and_sale'}
        {/if}
    </div>
</div>

