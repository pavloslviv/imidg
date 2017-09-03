{literal}
<script type="text/javascript ">
    $('.fancy_frame').fancybox({type:'iframe'});
    function sendNews(e,id){
        if (confirm('Разослать новость подписчикам?')){
            $.post('index.php?com=news&action=send',{id : id},function(data){
                alert(data.message);
                if (data.result=='success'){
                    $(e.target).remove();
                }
            })
        }
    }
</script>
{/literal}
<div class="sixteen columns  panel">
    <div class="pull-right">
        <a href="index.php?com=news&action=edit&id=0" class="btn btn-sm btn-primary">
            <i class="glyphicon glyphicon-plus"></i> Добавить новость</a>
        {*<a href="index.php?com=news&action=subscr" class="button fancy_frame"><img
                src="{$HTTP_ROOT}/lib/images/users.png" alt="Subscribers"/> Подписчики</a>*}
    </div>
    <h4>Новости сайта</h4>

    <table class="table table-striped table-fixed">
        <tr>
            <th>Заголовок</th>
            <th>Дата</th>
            {*<th>&nbsp;</th>*}
            <th style="width: 100px;">Действия</th>
        </tr>
    {foreach from=$news_list item="news"}
        <tr>
            <td>{$news.title}</td>
            <td style="width: 100px; text-align: center">{$news.date|date_format:"%d.%m.%Y"}</td>
            {*<td style="text-align: center">
                {if !$news.sent}
                <a href="javascript:void(0)" title="Сделать рассылку" onclick="sendNews(event,{$news.id})">
                    <img src="{$HTTP_ROOT}/lib/images/mail.png" alt="Разослать"/>
                </a>
                {/if}
            </td>*}
            <td>
                <a class="btn btn-xs btn-success" href="index.php?com=news&action=edit&id={$news.id}">
                    <span class="glyphicon glyphicon-edit"></span>
                </a>
                <a class="btn btn-xs btn-danger" href="index.php?com=news&action=delete&id={$news.id}">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
            </td>
        </tr>
    {/foreach}
    </table>
</div>