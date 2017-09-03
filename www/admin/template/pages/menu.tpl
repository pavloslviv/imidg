<script type="text/javascript" src="/lib/js/fancybox/jquery.fancybox.pack.js"></script>
<link type="text/css" rel="stylesheet" href="/lib/js/fancybox/jquery.fancybox.css"/>
<div class="sixteen columns  panel">
{literal}
    <script type="text/javascript">

        $('.fancy_ajax').fancybox({type:'ajax'});

        function confirmDelete(event) {
            temp = window.confirm('Удалить пункт меню?');
            if (!temp) event.preventDefault();
        }
        function formLoad(action, id) {
            $('#modalform').load('/admin/menuaction.php?action=' + action + '&id=' + id, '', function () {
                $('#modalform').fadeIn();
            });
        }
        function formUnLoad() {
            $('#modalform').fadeOut(function () {
                $('#modalform').empty();
            });
        }
    </script>
{/literal}
    <table class="table table-striped table-fixed">
        <tr>
            <th>Название</th>
            <th>Действия</th>
        </tr>
    {foreach from=$items item="item"}
        {if $item.cat_level==1}
            <tr class="section">
                <td>{$item.title}</td>
                <td style="text-align: left">
                    <a class="fancy_ajax" href="index.php?com=menu&action=new&id={$item.id}"><img src="{$HTTP_ROOT}/lib/images/add.png" alt="Add"/></a>
                </td>
            </tr>
            {elseif $item.cat_level>1}
            <tr>
                <td style="padding-left: {$item.cat_level*2}0px">{$item.title}</td>
                <td style="text-align: left">
                    <a class="fancy_ajax" href="index.php?com=menu&action=new&id={$item.id}"><img src="{$HTTP_ROOT}/lib/images/add.png" alt="Add"/></a>
                    <a class="fancy_ajax" href="index.php?com=menu&action=edit&id={$item.id}"><img src="{$HTTP_ROOT}/lib/images/edit.png" alt="Add"/></a>
                    <a href="index.php?com=menu&action=del&id={$item.id}"><img src="{$HTTP_ROOT}/lib/images/delete.png"
                                                                 alt="Add"/></a>
                </td>
            </tr>
        {/if}
    {/foreach}
    </table>
</div>