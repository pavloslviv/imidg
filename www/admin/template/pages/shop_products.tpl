<div id="app-container" class="sixteen columns  panel">
    <table class="table table-striped">
        {foreach from=$items item="item"}
            <tr class="section">
                <td>
                    {if $item.cat_level>0}
                        {for $var=1 to $item.cat_level}&nbsp;&nbsp;&nbsp;&nbsp;{/for}
                        &boxur;&boxh;
                    {/if}
                    {$item.title}
                </td>
                <td style="text-align: left">
                    {if $item.cat_level>0}
                        <a class="btn btn-xs btn-success" href="index.php?com=shop_sections&action=edit&id={$item.id}">
                            <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a class="add-section-trigger btn btn-xs btn-primary" href="#" data-parent="{$item.id}">
                            <span class="glyphicon glyphicon-plus-sign"></span>
                        </a>
                        <a class="btn btn-xs btn-danger" href="#">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    {else}
                        <span class="btn btn-xs btn-default disabled">
                            <span class="glyphicon glyphicon-edit"></span>
                        </span>
                        <a class="add-section-trigger btn btn-xs btn-primary" href="#" data-parent="{$item.id}">
                            <span class="glyphicon glyphicon-plus-sign"></span>
                        </a>
                        <span class="btn btn-xs btn-default disabled">
                            <span class="glyphicon glyphicon-remove"></span>
                        </span>
                    {/if}
                </td>
            </tr>
        {/foreach}
    </table>
</div>

<script type="text/template" id="tmpl_product_list">
    {include file="pages/shop_products.ejs"}
</script>