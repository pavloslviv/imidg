<div class="sixteen columns  panel">
    <div class="pull-right">
        <a href="index.php?com=guestbook&action=edit&id=0" class="btn btn-sm btn-primary">
            <i class="glyphicon glyphicon-plus"></i> Добавить отзыв
        </a>
    </div>
    <h4>Отзывы клиентов</h4>

    <table class="table table-striped table-fixed">
        <tr>
            <th>Имя</th>
            <th>E-mail</th>
            <th>Дата</th>
            <th style="width: 100px;">Действия</th>
        </tr>
    {foreach from=$guestbook_list item="guestbook"}
        <tr>
            <td>{$guestbook.client_name}</td>
            <td>{$guestbook.client_mail}</td>
            <td style="width: 100px; text-align: center">{$guestbook.client_date|date_format:"%d.%m.%Y"}</td>
            <td>
                <a class="btn btn-xs btn-success" href="index.php?com=guestbook&action=edit&id={$guestbook.id}">
                    <span class="glyphicon glyphicon-edit"></span>
                </a>
                <a class="btn btn-xs btn-danger" href="index.php?com=guestbook&action=delete&id={$guestbook.id}">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
            </td>
        </tr>
    {/foreach}
    </table>
</div>