<script type="text/javascript" src="template/js/messages.js"></script>

<script type="text/javascript">
    window.agentId = {$agent->id};
</script>
<button class="btn btn-primary edit" data-id="{$agent->id}" style="float: right;"><i class="icon-envelope"></i> Отправить сообщение</button>
<h2>Сообщения пользователя: {$agent->get('surname')} {$agent->get('name')} {$agent->get('patronymic')}</h2>
<table class="table table-striped table-condensed table-bordered">
    <tr id="item_list">
        <th>Дата</th>
        <th>Тема</th>
        <th>Текст</th>
        <th style="width: 160px;">Действия</th>
    </tr>
{foreach from=$items item="item"}
    <tr>
        <td style="white-space: nowrap;">{$item.date|date_format:"%d.%m.%Y %H:%M"} {if $item.new}<span class="label label-success">новое</span>{/if}</td>
        <td>{$item.title}</td>
        <td>{$item.text}</td>
        <td class="center">
            <a class="delete btn btn-mini btn-danger" href="index.php?com=agents&action=message_delete&id={$item.id}">
                <i class="icon-trash"></i> Удалить
            </a>
        </td>
    </tr>
{/foreach}
</table>
<script id="user_edit_template" type="text/template">
    <form class="form-horizontal modal">
        <input type="hidden" name="agent_id" value="<%= agentId %>"/>
        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h3>Сообщение</h3>
        </div>
        <fieldset class="modal-body">
            <div class="control-group">
                <label class="control-label" for="title">Тема</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" id="title" name="title" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="text">Текст</label>
                <div class="controls">
                    <textarea id="text" rows="5" cols="10" class="input-xlarge" name="text"></textarea>
                </div>
            </div>
        </fieldset>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Отправить</button>
        </div>
    </form>
</script>
<script id="item_row_template" type="text/template">
    <tr data-id="<%= item.id %>">
        <% console.log(item); %>
        <td>
            <%= item.date.getDate() %>.<%= item.date.getMonth()+1 %>.<%= item.date.getFullYear() %>
            <%= item.date.getHours() %>:<%= item.date.getMinutes()<10? '0'+item.date.getMinutes() : item.date.getMinutes() %>
            <% if (item.new){ %><span class="label label-success">новое</span><% } %>
        </td>
        <td><%= item.title %></td>
        <td><%= item.text %></td>
        <td>
            <a class="delete btn btn-mini btn-danger"
               href="index.php?com=agents&action=message_delete&id=<%= item.id %>">
                <i class="icon-trash"></i> Удалить
            </a>
        </td>
    </tr>
</script>