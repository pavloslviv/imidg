<script type="text/javascript" src="template/js/order_edit.js"></script>
<form action="index.php?com=orders&action=save" class="form-horizontal container" method="post">
    <input id="order_id" type="hidden" name="id" value="{$item.id}">
    <div class="row">
        <fieldset class="span6">
            <legend>Данные клиента</legend>
            <div class="control-group">
                <label for="name" class="control-label">Имя</label>

                <div class="controls">
                    <input type="text" id="name" class="input-xlarge" name="name" value="{$item.name}">
                </div>
            </div>
            <div class="control-group">
                <label for="phone" class="control-label">Телефон</label>

                <div class="controls">
                    <input type="text" id="phone" name="phone" class="input-xlarge" value="{$item.phone}">
                </div>
            </div>
            <div class="control-group">
                <label for="skype" class="control-label">Skype</label>

                <div class="controls">
                    <input type="text" id="skype" name="skype" class="input-xlarge" value="{$item.skype}">
                </div>
            </div>
            <div class="control-group">
                <label for="mail" class="control-label">E-mail</label>

                <div class="controls">
                    <input name="mail" type="text" id="mail" class="input-xlarge" value="{$item.mail}">
                </div>
            </div>
            <div class="control-group">
                <label for="comment" class="control-label">Комментарий</label>

                <div class="controls">
                    <textarea name="comment" id="comment" class="input-xlarge">{$item.comment}</textarea>
                </div>
            </div>
        </fieldset>
        <fieldset class="span6">
            <legend>Стоимость</legend>
            <div class="control-group">
                <label for="total" class="control-label">Сумма</label>

                <div class="controls">
                    <div class="input-append">
                    <input type="text" id="total" class="span1" value="{$item.total}" readonly="true"><span class="add-on"> руб</span>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label for="discount" class="control-label">Скидка</label>

                <div class="controls">
                    <div class="input-append">
                    <input type="text" id="discount" class="span1" value="{$item.discount}" readonly="true"><span class="add-on"> %</span>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label for="price" class="control-label">К оплате</label>

                <div class="controls">
                    <div class="input-append">
                    <input type="text" id="price" class="span1" value="{$item.price}" readonly="true"><span class="add-on"> руб</span>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label for="paid" class="control-label">Оплачено</label>

                <div class="controls">
                    <div class="input-append">
                    <input type="text" id="paid" class="span1" value="{$item.paid}" readonly="true"><span class="add-on"> руб</span>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="row">
        <fieldset class="span4">
            <legend style="margin-bottom: 0">Заказанное время</legend>
            <table id="period_list" class="table table-striped table-condensed" style="width: auto;">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Имя эксперта</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$item.periods item="period"}
                <tr id="period_{$period.id}">
                    <td>{$period.start|date_format:"%d.%m.%Y %H:%M"}</td>
                    <td>{$period.full_name}</td>
                    <td><button type="button" data-id="{$period.id}" class="delete btn btn-mini btn-danger"><i class="icon-trash"></i></button></td>
                </tr>
                {/foreach}
                </tbody>
            </table>
        </fieldset>
        <div class="span8">
            <iframe id="calendar" src="index.php?com=orders&action=calendar" style="width: 100%; height: 300px" frameborder="0"></iframe>
        </div>
    </div>
    <div class="row">
        <fieldset class="span12">
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Сохранить</button>
                <a class="btn" href="index.php?com=orders&action=list">Отменить</a>
            </div>
        </fieldset>
    </div>
</form>
<script id="select_expert_tpl" type="text/template">
    <div class="modal expert_modal">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h3><%= date %></h3>
        </div>
        <div class="modal-body">
            <%= experts %>
        </div>
        <div class="modal-footer">
            <button class="addPeriod btn btn-primary">Выбрать</button>
        </div>
    </div>
</script>
<script id="period_row" type="text/template">
    <tr id="period_<%= data.period_id %>">
        <td><%= data.date %></td>
        <td><%= data.expert %></td>
        <td><button type="button" data-id="<%= data.period_id %>" class="delete btn btn-mini btn-danger"><i class="icon-trash"></i></button></td>
    </tr>
</script>