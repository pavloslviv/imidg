    <div class="pull-right">
        <a href="index.php?com=ads&action=edit&id=0" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Новый блок</a>
    </div>
    <h4>Рекламные блоки</h4>

    <table class="table table-stripped">
        <tr>
            <th>Название</th>
           {* <th>Текст</th>*}
            <th class="center">Ссылка</th>
            <th class="center">Изображение</th>
            <th class="center">Действия</th>
        </tr>
    {foreach from=$items item="item"}
        <tr>
            <td>{$item.title}</td>
            {*<td>{$item.text}</td>*}
            <td class="center">{$item.link}</td> 
            <td class="center">{if $item.img}<img src="{$HTTP_ROOT}/lib/images/ok.png"/>{/if}</td>
            <td class="center">
                <a class="btn btn-xs btn-success" href="index.php?com=ads&action=edit&id={$item.id}">
                    <span class="glyphicon glyphicon-edit"></span>
                </a>
                <a class="btn btn-xs btn-danger" href="index.php?com=ads&action=delete&id={$item.id}">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
            </td>
        </tr>
    {/foreach}
    </table>