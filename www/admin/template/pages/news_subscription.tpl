<h4>Подписчики</h4>
<form action="index.php?com=news&action=subscr" method="post">
    <table class="itemlist">
        <tr>
            <th>Имя</th>
            <th>e-mail</th>
            <th>Действия</th>
        </tr>
    {foreach from=$items item="item"}
        <tr>
            <td>{$item.name}</td>
            <td>{$item.mail}</td>
            <td>
                <a href="index.php?com=news&action=subscr&do=delete&id={$item.id}">
                    <img src="{$HTTP_ROOT}/lib/images/delete.png" alt="Delete"/>
                </a>
            </td>
        </tr>
    {/foreach}
        <tr>
            <td><input type="text" name="new_name" style="width: 100%"></td>
            <td><input type="text" name="new_mail" style="width: 100%"></td>
            <td>
                <a href="javascript:void(0)" onclick="$('form').submit()"><img src="{$HTTP_ROOT}/lib/images/add.png"
                                                                               alt="Add"/></a>
            </td>
        </tr>
    </table>
</form>