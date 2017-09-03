<div class="col-md-12">
    <div class="pagination with-number">
        <ul>
            {section loop=$page_count name="p"}
                {if $smarty.section.p.iteration!=$page_current}

                    <li><a href="{$lang_suffix}/{$baseURL}{if $smarty.section.p.iteration>1}/page-{$smarty.section.p.iteration}{/if}">{$smarty.section.p.iteration}</a></li>
                    {else}
                    <li class="active"><b>{$smarty.section.p.iteration}</b></li>
                {/if}
            {/section}
        </ul>

        <a {if $page_current != 1}href="{$lang_suffix}/{$baseURL}{if $page_current>2}/page-{$page_current-1}{/if}"{/if} class="pagination_arrow left {if $page_current == 1}disabled{/if}">
            <i class="fa fa-angle-left" aria-hidden="true"></i>
        </a>

        <a {if $page_current!=$page_count}href="{$lang_suffix}/{$baseURL}{if $page_current>0}/page-{$page_current+1}{/if}"{/if} class="pagination_arrow right {if $page_current==$page_count}disabled{/if}">
            <i class="fa fa-angle-right" aria-hidden="true"></i>
        </a>
    </div>
</div>