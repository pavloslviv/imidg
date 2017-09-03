<div>
    <div class="pull-right">
        <a href="index.php?com=articles&action=edit&id=0" class="btn btn-sm btn-primary">
            <i class="glyphicon glyphicon-plus"></i> Добавить статью</a>
    </div>
    <h4>Сатьи</h4>

    <table class="table table-striped table-fixed">
        <tr>
            <th>Заголовок</th>
            <th style="width: 150px;" class="center">Дата</th>
            <th style="width: 100px;">Действия</th>
        </tr>
    {foreach from=$articles_list item="articles"}
        <tr>
            <td>{$articles.title}</td>
            <td  class="center">{$articles.date|date_format:"%d.%m.%Y"}</td>
            <td>
                <a class="btn btn-xs btn-success" href="index.php?com=articles&action=edit&id={$articles.id}">
                    <span class="glyphicon glyphicon-edit"></span>
                </a>
                <a class="btn btn-xs btn-danger" href="index.php?com=articles&action=delete&id={$articles.id}">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
            </td>
        </tr>
    {/foreach}
    </table>
    {if $page_count>0}
        {include file="blocks/pagination.tpl" baseURL='admin/index.php?com=articles'}
    {/if}
</div>