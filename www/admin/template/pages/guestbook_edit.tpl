<form action="index.php?com=guestbook&action=save&id={$guestbook.id}" method="post"
      enctype="multipart/form-data">
    <h4>{if !$guestbook.id}Создание отзыва{else}Редактирование отзыва: {$guestbook.client_name}{/if}</h4>

    <div class="row">
        <label class="col-md-5">Имя:<br/>
            <input type="text" name="attributes[client_name]" class="form-control" value="{$guestbook.client_name}"/>
        </label>
        <label class="col-md-5">e-mail:<br/>
            <input type="text" name="attributes[client_mail]" class="form-control" value="{$guestbook.client_mail}"/>
        </label>
        <label class="col-md-2">Дата:<br/>
            <input type="text" name="attributes[client_date]" class="form-control" value="{$guestbook.client_date|date_format:"%d.%m.%Y"}"/>
        </label>
        <label class="col-md-12">Текст отзыва<br/>
            <textarea type="text" name="attributes[text]" class="form-control" rows="3">{$guestbook.text}</textarea>
        </label>
    </div>
    <div class="row">
        <label class="col-md-3">Дата ответа:<br/>
            <input type="text" name="attributes[response_date]" class="form-control" value="{$guestbook.client_date|date_format:"%d.%m.%Y"}"/>
        </label>
        <label class="col-md-3">
            Активен: <input type="checkbox" name="attributes[active]" value="1" {if $guestbook.active==1}checked{/if}/>
        </label>
        <label class="col-md-12">Текст ответа<br/>
            <textarea type="text" name="attributes[response_text]" class="form-control" rows="3">{$guestbook.response_text}</textarea>
        </label>
    </div>
    <button class="btn btn-primary" type="submit">Сохранить</button>
    <a class="btn btn-default" class="button" href="index.php?com=guestbook&action=list">Отмена</a></form>
</form>