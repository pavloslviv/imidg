<form action="index.php?com=page&action=save&id={$page->id}" class="sixteen columns  panel" method="post">
    <h4>{if !$page->id}Создание страницы{else}Редактирование страницы: &laquo;{$page->get('title')}&raquo;{/if}</h4>
    <label class="eight columns">Название:{$lang_label}<br/>
        <input type="text" name="attributes[title]" class="seven columns translated-field" value="{$page->get('title')}"/>
    </label>
    <label class="seven columns">SEF URL:<br/>
        <input type="text" name="attributes[sef]" class="six columns" value="{$page->get('sef')}"/>
    </label>
    <label class="five columns">Meta title{$lang_label}<br/>
        <textarea type="text" name="attributes[meta_title]" class="four columns translated-field">{$page->get('meta_title')}</textarea>
    </label>
    <label class="five columns">Meta description{$lang_label}<br/>
        <textarea type="text" name="attributes[meta_descr]" class="four columns translated-field">{$page->get('meta_descr')}</textarea>
    </label>
    <label class="five columns">Meta keywords{$lang_label}<br/>
        <textarea type="text" name="attributes[meta_keyw]" class="four columns translated-field">{$page->get('meta_keyw')}</textarea>
    </label>
    <label class="fifteen columns">Текст{$lang_label}<br/>
        <textarea type="text" name="attributes[text]" class="editor translated-field">{$page->get('text')}</textarea>
    </label>
    <div></div><button type="submit">Сохранить</button><a class="button" href="index.php?com=page&action=list">Отмена</a></form>
</form>