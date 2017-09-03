<form action="index.php?com=articles&action=save&id={$articles->id}" method="post"
      enctype="multipart/form-data">
    <h4>{if !$articles->id}Создание статьи{else}Редактирование статьи: &laquo;{$articles->get('title')}&raquo;{/if}</h4>

    <div class="row">
        <div class="col-md-4">
            <label class="col-md-12">Название:{$lang_label}<br/>
                <input type="text" name="attributes[title]" class="form-control translated-field" value="{$articles->get('title')}"/>
            </label>
            <label class="col-md-12">Название(полное):{$lang_label}<br/>
                <input type="text" name="attributes[title_full]" class="form-control translated-field" value="{$articles->get('title_full')}"/>
            </label>
            <label class="col-md-12">Дата:<br/>
                <input type="text" name="attributes[date]" class="form-control"
                       value="{$articles->get('date')|date_format:"%d.%m.%Y"}"/>
            </label>
            <label class="col-md-12">SEF URL:<br/>
                <input type="text" name="attributes[sef]" class="form-control" value="{$articles->get('sef')}"/>
            </label>
        </div>
        <div class="col-md-4">
            <div class="four columns">Изображение:<br> <input type="file" name="file"/></div>
            <div class="four columns">
                {if $articles->get('image')}
                    <img src="{$HTTP_ROOT}/media/articles/{$articles->id}_thumb.jpg" alt="Image">
                {/if}
            </div>
        </div>
        <div class="col-md-4">
            <label class="col-md-12" title="Скрыть картинку на странице статьи">Скрыть картинку в статье:
                <input type="checkbox" name="attributes[hide_image]" value="1" {if $articles->get('hide_image')==1} checked="checked" {/if}/>
            </label>
        </div>
    </div>
    <div class="row">
        <label class="col-md-4">Meta title{$lang_label}<br/>
            <textarea type="text" name="attributes[meta_title]"
                      class="form-control translated-field">{$articles->get('meta_title')}</textarea>
        </label>
        <label class="col-md-4">Meta description{$lang_label}<br/>
            <textarea type="text" name="attributes[meta_descr]"
                      class="form-control translated-field">{$articles->get('meta_descr')}</textarea>
        </label>

        <label class="col-md-4">Meta keywords{$lang_label}<br/>
            <textarea type="text" name="attributes[meta_keyw]"
                      class="form-control translated-field">{$articles->get('meta_keyw')}</textarea>
        </label>
    </div>
    <div class="row">
        <label class="col-md-6">Короткий текст{$lang_label}<br/>
            <textarea type="text" name="attributes[brief]" class="form-control translated-field">{$articles->get('brief')}</textarea>
        </label>
    </div>
    <div>
        <label class="fifteen columns" for="article-text">Текст{$lang_label}</label>
        <textarea id="article-text" type="text" name="attributes[text]"
                  class="editor">{$articles->get('text')}</textarea>

    </div>
    <div></div>
    <button type="submit">Сохранить</button>
    <a class="button" href="index.php?com=articles&action=list">Отмена</a></form>
</form>