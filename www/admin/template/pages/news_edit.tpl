<form action="index.php?com=news&action=save&id={$news->id}" class="sixteen columns  panel" method="post">
    <h4>{if !$news->id}Создание страницы{else}Редактирование новости: &laquo;{$news->get('title')}&raquo;{/if}</h4>
    <label class="seven columns">Название:<br/>
        <input type="text" name="attributes[title]" class="six columns" value="{$news->get('title')}"/>
    </label>
    <label class="two columns">Дата:<br/>
        <input type="text" name="attributes[date]" class="column" value="{$news->get('date')|date_format:"%d.%m.%Y"}"/>
    </label>
    <label class="six columns">SEF URL:<br/>
        <input type="text" name="attributes[sef]" class="five columns" value="{$news->get('sef')}"/>
    </label>
    <label class="five columns">Meta title<br/>
        <textarea type="text" name="attributes[meta_title]" class="four columns">{$news->get('meta_title')}</textarea>
    </label>
    <label class="five columns">Meta description<br/>
        <textarea type="text" name="attributes[meta_descr]" class="four columns">{$news->get('meta_descr')}</textarea>
    </label>
    <label class="five columns">Meta keywords<br/>
        <textarea type="text" name="attributes[meta_keyw]" class="four columns">{$news->get('meta_keyw')}</textarea>
    </label>
    <label class="fifteen columns">Короткий текст<br/>
        <textarea type="text" name="attributes[brief]" class="six columns">{$news->get('brief')}</textarea>
    </label>
    <label class="fifteen columns">Текст<br/>
        <textarea type="text" name="attributes[text]" class="editor">{$news->get('text')}</textarea>
    </label>
    <div></div><button type="submit">Сохранить</button><a class="button" href="index.php?com=news&action=list">Отмена</a></form>
</form>