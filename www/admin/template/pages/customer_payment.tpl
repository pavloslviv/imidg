<script type="text/javascript" src="template/js/payment.js"></script>

<script type="text/javascript">
    window.agentId = {$agent->id};
</script>
<button class="btn btn-primary edit" data-id="{$agent->id}" style="float: right;"><i class="icon-envelope"></i> Создать платеж</button>
<h2>Платежи пользователя: {$agent->get('surname')} {$agent->get('name')} {$agent->get('patronymic')}</h2>
<table class="table table-striped table-condensed table-bordered">
    <tr id="item_list">
        <th>Дата</th>
        <th>Тип</th>
        <th>Сумма</th>
        <th>Комментарий</th>
        <th style="width: 160px;">Действия</th>
    </tr>
{foreach from=$items item="item"}
    <tr>
        <td style="white-space: nowrap;">{$item.date|date_format:"%d.%m.%Y %H:%M"}</td>
        <td>{if $item.type=='in'}Начисление{elseif $item.type=='out'}Выплата{/if}</td>
        <td>{if $item.type=='out'}-{else}&nbsp{/if}{$item.summ}</td>
        <td>{$item.comment}</td>
        <td class="center">
            <a class="delete btn btn-mini btn-danger" href="index.php?com=agents&action=payment_delete&agent_id={$agent->id}&id={$item.id}">
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
            <h3>Платеж</h3>
        </div>
        <fieldset class="modal-body">
            <div class="control-group">
                <label class="control-label" for="type">Тип</label>
                <div class="controls">
                    <select class="input-xlarge" id="type" name="type">
                        <option value="in">Начисление</option>
                        <option value="out">Выплата</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="summ">Сумма</label>
                <div class="controls">
                    <div class="input-append">
                    <input type="text" class="span1" id="summ" name="summ" /><span class="add-on">грн</span>
                       </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="text">Комментарий</label>
                <div class="controls">
                    <textarea id="comment" rows="5" cols="10" class="input-xlarge" name="comment"></textarea>
                </div>
            </div>
        </fieldset>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Сохранить</button>
        </div>
    </form>
</script>
<script id="item_row_template" type="text/template">
    <tr data-id="<%= item.id %>">
        <td>
            <%= item.date.getDate() %>.<%= item.date.getMonth()+1 %>.<%= item.date.getFullYear() %>
            <%= item.date.getHours() %>:<%= item.date.getMinutes()<10? '0'+item.date.getMinutes() : item.date.getMinutes() %>
            <% if (item.new){ %><span class="label label-success">новое</span><% } %>
        </td>
        <td><%= item.type=='in'? 'Начисление' : 'Выплата' %></td>
        <td><%= item.type=='out'? '-' : ' '%><%= item.summ %></td>
        <td><%= item.comment %></td>
        <td>
            <a class="delete btn btn-mini btn-danger"
               href="index.php?com=agents&action=payment_delete?agent_id={$agent->id}&id=<%= item.id %>">
                <i class="icon-trash"></i> Удалить
            </a>
        </td>
    </tr>
</script>