{literal}
    <script type="text/javascript ">
        $(function () {
            var $container = $('.container');
            $container
                    .on('click', '.save-card-trigger',function (e) {
                        e.preventDefault();
                        var $row = $(e.currentTarget).closest('tr'),
                                $inputs = $row.find('input'),
                                data = {
                                    id: $row.data('id')
                                };
                        $inputs.each(function () {
                            var $input = $(this);
                            data[$input.attr('name')] = $input.val();
                        });
                        $.post('index.php?com=discount&action=save', data, function (r) {
                            if (!r.success) {
                                alert('Ошибка сохранения!');
                                return;
                            }
                            $inputs.each(function () {
                                var $input = $(this);
                                $input.val(r.data[$input.attr('name')]);
                            })
                        })
                    })
                    .on('click', '.remove-card-trigger', function (e) {
                        e.preventDefault();
                        var $row = $(e.currentTarget).closest('tr'),
                            data = {
                                id: $row.data('id')
                            };
                        if(!confirm('Удалить эту карту?')) return;
                        $.post('index.php?com=discount&action=delete', data, function (r) {
                            if (!r.success) {
                                alert('Ошибка сохранения!');
                                return;
                            }
                            window.location.reload();
                        })
                    });
        });
    </script>
{/literal}
<div>
    <h2>Дисконтные карты</h2>
    {*id, customer_id, code, type, discount, amount, customer_code, customer_name*}
    <form action="index.php?com=discount&action=list" method="get" class="clearfix">
        <input type="hidden" value="discount" name="com"/>
        <input type="hidden" value="list" name="action"/>

        <div class="form-group col-md-4">
            <label class="control-label" for="nameFilter">Имя</label>
            <input name="name" value="{$name}" type="text" class="form-control" id="nameFilter"
                   placeholder="Имя клиента"/>
        </div>
        <div class="form-group col-md-4">
            <label class="control-label" for="codeFilter">Код карты</label>
            <input name="code" value="{$code}" type="text" class="form-control" id="codeFilter"
                   placeholder="Код карты"/>
        </div>
        <div class="form-group col-md-2">
            <label class="control-label" for="dFilter1">Скидка от</label>
            <input name="d_from" value="{$d_from}" type="text" class="form-control" id="dFilter1" placeholder="0"/>
        </div>
        <div class="form-group col-md-2">
            <label class="control-label" for="dFilter2">Скидка до</label>
            <input name="d_to" value="{$d_to}" type="text" class="form-control" id="dFilter2" placeholder="100"/>
        </div>
        <div class="form-group col-md-12">
            <button type="submit" class="pull-right btn btn-primary">Найти</button>
        </div>
    </form>
    <table class="table table-striped table-fixed" style="margin-top: 20px;">
        <thead>
        <tr>
            <th style="width: 120px;">Код</th>
            <th style="width: 170px;">Тип</th>
            <th style="width: 120px;">Сумма</th>
            <th style="width: 80px;">Скидка</th>
            <th style="width: 120px;">Код клиента</th>
            <th>Имя карты</th>
            <th style="width: 100px;">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$card_list item="i"}
            <tr data-id="{$i.id}">
                <td>
                    <input type="text" name="code" value="{$i.code|htmlspecialchars}" class="form-control"/>
                </td>
                <td>
                    <select name="type" class="form-control">
                        <option value="cumulative" {if $i.type=='cumulative'} selected{/if}>Накопительная</option>
                        <option value="fixed" {if $i.type=='fixed'} selected{/if}>Фиксированная</option>
                    </select>
                </td>
                <td><input type="text" name="amount" class="form-control" value="{$i.amount|htmlspecialchars}"/></td>
                <td><input type="text" name="discount" class="form-control" value="{$i.discount|htmlspecialchars}"/>
                </td>
                <td><input type="text" name="customer_code" class="form-control"
                           value="{$i.customer_code|htmlspecialchars}"/></td>
                <td><input type="text" name="customer_name" class="form-control"
                           value="{$i.customer_name|htmlspecialchars}"/></td>
                <td>
                    <a class="btn btn-xs btn-success save-card-trigger"
                       href="#">
                        <span class="glyphicon glyphicon-floppy-save"></span>
                    </a>
                    {if $i.customer_id}
                    <a class="btn btn-xs btn-primary"
                       href="http://imidg.ls/admin/index.php?com=customers&action=edit&id={$i.customer_id}">
                        <span class="glyphicon glyphicon-user"></span>
                    </a>
                    {else}
                        <span class="disabled btn btn-xs btn-default"><span class="glyphicon glyphicon-user"></span></span>
                    {/if}
                    <a class="btn btn-xs btn-danger remove-card-trigger" href="#">
                        <span class="glyphicon glyphicon-remove"></span>
                    </a>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>