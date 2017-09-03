<div class="center">
    <ul class="pagination">
        {if $page_current>1}
            <li><a href="/{$baseURL}{if $page_current>2}/page-{$page_current-1}{/if}" class="step">&laquo;</a></li>
        {/if}
        {section loop=$page_count name="p"}
            {if $smarty.section.p.iteration!=$page_current}
                <li><a href="/{$baseURL}{if $smarty.section.p.iteration>1}&page={$smarty.section.p.iteration}{/if}">{$smarty.section.p.iteration}</a></li>
            {else}
                <li class="active"><span>{$smarty.section.p.iteration}</span></li>
            {/if}
        {/section}
        {if $page_current<$page_count}
            <li><a href="/{$baseURL}{if $page_current>0}/page-{$page_current+1}{/if}" class="step">&raquo;</a></li>
        {/if}
    </ul>
</div>
