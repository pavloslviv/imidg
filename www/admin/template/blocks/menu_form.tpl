<form action="index.php?com=menu&action={if !$item.id}add{else}save{/if}" method="post" enctype="multipart/form-data" style="margin: 0">
    <h4 align="center">{if !$item}Добавление{else}Редактирование{/if} пункта меню</h4>
    <label>Название<br/>
        <input size="56" type="text" name="title" value="{$item.title}"></label>
    <label>Страница<br/>
        <select name="page_id" style="width: 360px;"
                onchange="if (this.value!=0) $('#url').slideUp(); else $('#url').slideDown()">

            <option value="0"{if $item.pageid==0} selected="selected"{/if}>Внешний URL</option>
            <optgroup label="Страницы">
            {foreach from=$pages item="page"}
                <option value="{$page.id}"{if $item.page_id==$page.id} selected="selected"{/if}>{$page.title}</option>
            {/foreach}
            </optgroup>
        </select>
    </label>

    <label id="url" {if $item.page_id!=0}style="display: none"{/if}>Внешний URL<br/>
        <input type="text" name="url" size="56" value="{$item.url}"/></label>

    <input type="hidden" name="id" value="{$item.id}">
{if $item.parent_id}<input type="hidden" name="parent_id" value="{$item.parent_id}">{/if}

    <p><input type="submit" value="Сохранить"/></p>
</form>