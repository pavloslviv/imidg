<div id="main-content" class="col-md-10 col-md-offset-1">
    <div class="pull-right">
        <button id="save-trigger" class="btn btn-primary" type="button">Сохранить</button>
        <button id="reset-trigger" class="btn btn-default" type="button">Отменить</button>
    </div>
    <h2>Представительства</h2>
    <div id="map_canvas" style="width: 700px; height: 400px; margin: 0 auto;"></div>
    <div style="text-align: center; margin: 20px 0;">
        <div class="btn-group" style="display: inline-block;">
            <button id="fix-map-trigger" class="btn btn-default" type="button">Фиксировать карту</button>
            <button id="reset-map-trigger" class="btn btn-default" type="button">Сбросить карту</button>
        </div>
    </div>

    <table class="table table-striped table-fixed">
        <thead>
        <tr>
            <th style="width:50px;">№</th>
            <th style="width: 120px;">Город</th>
            <th>Адрес</th>
            <th style="width: 150px;">Телефон</th>
            <th style="width: 100px;">Фото</th>
            <th style="width: 100px;">&nbsp;</th>
        </tr>
        </thead>
        <tbody id="address-list">

        </tbody>
    </table>
    <h3>Добавление маркера</h3>
    <table class="table table-fixed">
        <tbody id="new-item">
            <tr>
                <td colspan="4">
                    <input id="new-search-address" class="form-control input-sm" type="text" placeholder="Введите адрес маркера на карте"/>
                </td>
                <td>
                    <button id="find-address-trigger" class="btn btn-xs btn-info" type="button" title="Найти на карте">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </td>
            </tr>
            <tr>
                <td><input id="new-order" class="form-control input-sm" type="text" placeholder="№"/></td>
                <td><input id="new-city" class="form-control input-sm" type="text" placeholder="Город"/></td>
                <td><input id="new-address" class="form-control input-sm" type="text" placeholder="Адрес"/></td>
                <td><input id="new-phone" class="form-control input-sm" type="text" placeholder="Телефон"/></td>
                <td style="vertical-align: middle">
                    <button id="save-address-trigger" class="btn btn-xs btn-success" type="button" title="Добавить в список">
                        <span class="glyphicon glyphicon-floppy-save"></span>
                    </button>
                    <button id="reset-address-trigger" class="btn btn-xs btn-warning" type="button" title="Очистить форму">
                        <span class="glyphicon glyphicon-trash"></span>
                    </button>
                </td>
            </tr>

        </tbody>
    </table>

</div>
<script type="text/html" id="tmpl_address_row">
    <tr data-index="<%= index %>">
        <td><input class="form-control input-sm" type="text" name="order" value="<%- item.order %>"/></td>
        <td><%- item.city %></td>
        <td><%- item.address %></td>
        <td><input class="form-control input-sm" type="text" name="phone" value="<%- item.phone %>"/></td>
        <td style="vertical-align: middle; text-align: center">
            <% if(item.image){ %>
            <div class="thumbnail">
                <img src="<%= item.image %>" style="max-width: 100%">
            </div>
            <% } else { %>
            &nbsp;
            <% } %>
        </td>
        <td style="vertical-align: middle">
            <button class="add-image-trigger btn btn-xs btn-default" type="button">
                <span class="glyphicon glyphicon-picture"></span>
            </button>
            <button class="remove-item-trigger btn btn-xs btn-danger" type="button">
                <span class="glyphicon glyphicon-remove"></span>
            </button>
        </td>
    </tr>
</script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBn0RFYZYZVjKdOBKad-bpJsWzuQxae1aA&sensor=false"></script>
<script type="text/javascript" src="/admin/template/js/map.js"></script>
<script type="text/javascript">
    ShopMap.data = {$map.value};
</script>