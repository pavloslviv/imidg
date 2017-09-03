<div id="main-content" class="col-md-10 col-md-offset-1">
    <div class="pull-right">
        <button id="save-trigger" class="btn btn-primary" type="button">Сохранить</button>
        <button id="reset-trigger" class="btn btn-default" type="button">Отменить</button>
    </div>
    <h2>Сетка дисконтов</h2>
    <table class="table table-striped table-fixed">
        <thead>
        <tr>
            <th style="width:50px;">Сумма</th>
            <th style="width: 120px;">Скидка, %</th>
            <th style="width: 100px;">&nbsp;</th>
        </tr>
        </thead>
        <tbody id="item-list">

        </tbody>
    </table>
    <h3>Добавление элемента</h3>
    <table class="table table-fixed">
        <tbody id="new-item">
            <tr>
                <td>
                    <div class="input-group">
                        <input id="new-amount" class="form-control input-sm" type="number" step="1" placeholder="Сумма"/>
                        <span class="input-group-addon">грн.</span>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <input id="new-percent" class="form-control input-sm" type="number" step="0.1" placeholder="Скидка"/>
                        <span class="input-group-addon">%</span>
                    </div>
                </td>
                <td style="vertical-align: middle">
                    <button id="save-item-trigger" class="btn btn-xs btn-success" type="button" title="Добавить в список">
                        <span class="glyphicon glyphicon-floppy-save"></span>
                    </button>
                    <button id="reset-item-trigger" class="btn btn-xs btn-warning" type="button" title="Очистить форму">
                        <span class="glyphicon glyphicon-trash"></span>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

</div>
<script type="text/html" id="tmpl_address_row">
    <tr data-index="<%= index %>">
        <td><input class="form-control input-sm" type="number" name="amount" value="<%- item.amount %>" step="1"/></td>
        <td><input class="form-control input-sm" type="number" name="percent" value="<%- item.percent %>" step="0.1"/></td>
        <td style="vertical-align: middle">
            <button class="remove-item-trigger btn btn-xs btn-danger" type="button">
                <span class="glyphicon glyphicon-remove"></span>
            </button>
        </td>
    </tr>
</script>
<script type="text/javascript" src="/admin/template/js/discounts.js"></script>
<script type="text/javascript">
    ShopMap.data = {$discounts.value};
</script>