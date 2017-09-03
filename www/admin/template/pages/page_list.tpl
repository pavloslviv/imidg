<div class="sixteen columns  panel">
    <a href="index.php?com=page&action=edit&id=0" class="btn btn-sm btn-primary pull-right"><i class="glyphicon glyphicon-plus"></i> Создать</a>
    <h4>Страницы сайта</h4>

    <table class="table table-striped table-fixed">
        <tr>
            <th>Название</th>
            <th style="width: 100px;">Действия</th>
        </tr>
    {foreach from=$pages item="page"}
        <tr>
            <td>{$page.title}</td>
            <td>
                <a class="btn btn-xs btn-success" href="index.php?com=page&action=edit&id={$page.id}">
                    <span class="glyphicon glyphicon-edit"></span></a>
                {if $page.id!=1 && $page.id!=25}
                <a class="btn btn-xs btn-danger" href="index.php?com=page&action=delete&id={$page.id}"><span class="glyphicon glyphicon-remove"></span></a>
                {/if}
            </td>
        </tr>
    {/foreach}
    </table>
</div>