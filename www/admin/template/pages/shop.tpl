
<div class="container">
    <div id="shop-container"></div>
</div>
{literal}
    <!-- Concatenated templates -->
    <script type="text/html" id="tmpl_payment_form"><% if(method=='privat24' && status != 'paid'){ %>
    <% console.log(status)%>
        <div class="form-group col-md-6">
        <button id="send-payment-trigger" class="btn btn-success"><%= data.last_request ? 'Повторить' : 'Послать' %> запрос оплаты</button>
    </div>
    <% if(data.last_request){ %>
    <div class="form-group col-md-6">
        Последний запрос: <%= moment(data.last_request,'X').format('DD.MM.YYYY HH:mm') %><br>
        <a href="http://<%= window.location.hostname+'/payment/pay/'+uniquid %>" target="_blank">
            Ссылка на оплату
        </a>
    </div>
    <% if(data.payments){ %>
    <div class="form-group col-md-12">
        <label class="control-label">История платежей</label>
        <table class="table table-striped table-condensed">
        <% _.each(data.payments, function(p){ %>
            <tr>
                <td><%= p.sender_phone %></td>
                <td><%= p.amt %> <%= p.ccy %></td>
                <td><%= moment(p.date,'X').format('DD.MM.YYYY HH:mm') %></td>
                <td><%= p.state %></td>
            </tr>
        <% }); %>
        </table>
    </div>
    <% } %>
<% }%>


<div></div>
<% } else if (method=='liqpay') { %>
        <% console.log(data)%>
        <div class="form-group col-md-12">
            <label class="control-label">Данные платежа</label>
            <table class="table table-striped table-condensed">
                <tr>
                    <td>ID платежа</td>
                    <td>Тип оплаты</td>
                    <td>Статус</td>
                </tr>
                <tr>
                    <td><%= data.payment_id %></td>
                    <td>
                        <% if(data.paytype == 'card'){ %>
                            Карта
                        <% } else if(data.paytype == 'cash'){ %>
                            Наличными
                        <% } else if(data.paytype == 'privat24'){ %>
                            Приват 24
                        <% } %>
                    </td>
                    <td>
                        <% if(data.status == 'sandbox'){ %>
                            тестовый платеж
                        <% } else if(data.status == 'success'){ %>
                            успешный платеж
                        <% } else if(data.status == 'error'){ %>
                            Неуспешный платеж. Некорректно заполнены данные
                        <% } else if(data.status == 'failure'){ %>
                            Неуспешный платеж. Некорректно заполнены данные
                        <% } else if(data.status == 'cash_wait'){ %>
                            Ожидается оплата наличными в ТСО.
                        <% } %>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><a href="https://www.liqpay.com/ru/doc/callback" target="_blank">Подробнее о статусах платежей</a></td>
                </tr>
            </table>
        </div>
        <div></div>
<% } %></script>
<script type="text/html" id="tmpl_shipment_form"><% if(method=='pickup'){ %>
<div class="form-group">
    <label for="inputOffice" class="control-label">Офіс</label>
    <input id="inputOffice" name="office" class="form-control" type="text" value="<%- data.office %>"/>
</div>
<% } else if(method=='new_post') { %>
<div class="form-group">
    <label for="inputCity" class="control-label">Город</label>
    <input id="inputCity" class="form-control" name="city" type="text" placeholder="Місто" value="<%- data.city %>" />
</div>
<div class="form-group">
    <label for="inputWarehouse" class="control-label">Склад №</label>
    <input id="inputWarehouse" class="form-control" name="warehouse" type="text" placeholder="Склад №" value="<%- data.warehouse %>"/>
</div>
<% } else if(method=='post'){ %>
<div class="form-group">
    <label for="inputAddress" class="control-label">Адрес</label>
    <textarea id="inputAddress" class="form-control" name="address" cols="30" rows="5" placeholder="індекс, область, місто, вулиця, будинок (номер, корпус), № квартири"><%- data.address %></textarea>
</div>
<% } else if(method=='courier'){ %>
<div class="form-group">
    <label for="inputAddress" class="control-label">Адрес</label>
    <textarea id="inputAddress" class="form-control" name="courier_address" cols="30" rows="5" placeholder="вулиця, будинок (номер, корпус), № квартири"><%- data.courier_address %></textarea>
</div>
<% } %></script>
<script type="text/html" id="tmpl_color_selector"><div id="color-selector-modal" class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Выбор цвета</h4>
        </div>
        <div class="modal-body">
            <div class="btn-file btn btn-primary">
                <span class="glyphicon glyphicon-upload"></span>
                Выбрать образец
                <input type="file" name="image" class="color-sample-input" accept="image/*"/>
            </div>
            <% if(!noSample) { %>
                <canvas></canvas>
                <div id="color-preview"></div>
            <% }%>
        </div>
        <div class="modal-footer">
            <button  type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
        </div>
    </div>
</div></script>
<script type="text/html" id="tmpl_product_selector"><!-- Modal -->
<div id="product-selector-modal" class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Выбор товара</h4>
        </div>
        <div class="modal-body">
            <form id="product-selector-search" class="form-inline" role="form">
                <div class="form-group">
                    <label class="sr-only" for="productTitleInput">Название товара</label>
                    <input type="text" class="form-control" id="productTitleInput" placeholder="Название товара" value="<%= query %>">
                </div>
                <button type="submit" class="btn btn-default">Искать</button>
            </form>
            <table class="table table-condensed table-striped table-fixed">
                <thead>
                <tr>
                    <th>Название</th>
                    <th width="120" class="center">На складе</th>
                    <th width="120">Цена</th>
                </tr>
                </thead>
            </table>
            <div class="scroll-container">
            <table class="table table-condensed table-striped table-fixed">
                <tbody>
                <% list.each(function(i){ %>
                <tr>
                    <td>
                        <% if(!i.get('modifications') || !i.get('modifications').length){ %>
                        <a class="select-product-trigger" href="#" data-prod="<%= i.id %>"><%- i.get('title') %></a>
                        <% } else { %>
                        <%- i.get('title') %>
                        <% } %>
                    </td>
                    <td width="120" class="center"><%- i.get('stock') %></td>
                    <td width="120"><%- i.get('price') ? i.get('price').replace('.',',') : '' %></td>
                </tr>
                <% i.get('modifications').each(function(m){%>
                <tr>
                    <td>&boxur;&boxh; <a class="select-product-trigger" href="#" data-mod="<%= m.id %>" data-prod="<%= m.get('parent_id') %>"><%- m.get('title') %></a></td>
                    <td width="120" class="center"><%= m.get('stock') %></td>
                    <td width="120"><%- m.get('price') ? m.get('price').replace('.',',') : '' %></td>
                </tr>
                <%}); %>
                <% }); %>
                </tbody>
            </table>
            </div>
        </div>
        <div class="modal-footer">
            <button  type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog --></script>
<script type="text/html" id="tmpl_shop_customer_details"><div id="order-detail-form">
    <a href="javascript:history.go(-1)" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Назад</a>
    <h3>Редактирование аккаунта килента</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="main-form" class="row">
                            <div class="col-md-6" method="post">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Имя</label>
                                    <input id="inputName" name="name" type="text" class="form-control product-property"
                                           value="<%- c.get('name') %>">
                                </div>
                                <div class="form-group">
                                    <label for="inputPhone" class="control-label">Телефон</label>
                                    <input id="inputPhone" name="phone" type="text" class="form-control product-property"
                                           value="<%- c.get('phone') %>">
                                </div>
                                <div class="form-group">
                                    <label for="inputDiscount" class="control-label">Номер картки <% if(c.get('discount_discount')){ %>(<%- c.get('discount_discount') %>%)<% } %></label>
                                    <input id="inputDiscount" name="discount" type="text" class="form-control product-property"
                                           value="<%- c.get('discount_code') %>" placeholder="Введіть номер картки">
                                </div>
                            </div>
                            <div class="col-md-6" role="form">
                                <div class="form-group">
                                    <label for="inputMail" class="control-label">E-mail</label>
                                    <input id="inputMail" name="mail" type="text" class="form-control product-property"
                                           value="<%- c.get('mail') %>">
                                </div>
                                <div class="form-group">
                                    <label for="inputPass" class="control-label">Пароль</label>
                                    <input id="inputPass" name="pass" type="text" class="form-control product-property"
                                           value="" placeholder="Введите новый пароль">
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <button id="save-order-trigger" class="btn btn-primary" type="button">Сохранить</button>
                                    <button id="reset-order-trigger" class="btn btn-default" type="button">Отменить изменения</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Заказы</h3>
                    </div>
                    <div class="panel-body">
                        <div id="order-list-container"></div>

                    </div>
                </div>
            </div>
        </div>
</div></script>
<script type="text/html" id="tmpl_shop_customer_details_items"><table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Дата</th>
            <th>Сумма</th>
            <th>Статус</th>
        </tr>
    </thead>
    <tbody>
    <% items.each(function(i){ %>
    <tr>
        <td><%= i.id %></td>
        <td><a href="#order/<%= i.id %>"><%= i.get('date').format('L HH:mm') %></a></td>
        <td><%= i.get('total').replace('.',',') %></td>
        <td><%= SerenityShop.data.orderStatuses[i.get('status')] %></td>
    </tr>
    <% }); %>
    </tbody>
</table></script>
<script type="text/html" id="tmpl_shop_customer_list"><div class="row">
    <div class="pull-right">
        <a href="#customer/0" class="btn btn-success"><i class="icon-user"></i> Создать аккаунт</a>
        <div class="btn-file btn btn-primary">
            <span class="glyphicon glyphicon-upload"></span>
            Загрузить список карт
            <input type="file" name="image" id="card-list-file-input" accept="text/xml"/>
        </div>
        <a href="http://imidg.com.ua/admin/index.php?com=customers&action=export" target="_blank" class="btn btn-primary">
        <span class="glyphicon glyphicon-download"></span> Скачать в СSV
        </a>
    </div>
</div>

<table class="table">
    <thead>
    <tr>
        <th>Имя</th>
        <th>E-mail</th>
        <th>Телефон</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody id="customer-list">

    </tbody>
</table></script>
<script type="text/html" id="tmpl_shop_customer_list_row"><tr>
    <td><%- c.get('name') %></td>
    <td><%- c.get('mail') %></td>
    <td><%- c.get('phone') %></td>
    <td>
        <a class="btn btn-xs btn-success" href="#customer/<%= c.id%>">
            <span class="glyphicon glyphicon-edit"></span>
        </a>
    </td>
</tr></script>
    <script type="text/html" id="tmpl_shop_callback_details"><div id="order-detail-form">
        <a href="javascript:window.history.back()" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Назад</a>
        <div class="pull-right">
            Статус заказа:
            <select name="inputStatus" id="inputStatus" class="form-control" style="display: inline-block; width: auto;">
                <% _.each(SerenityShop.data.callbackStatuses, function(label,name){
                %>
                <option value="<%= name %>" <%= o.attributes.status == name ? 'selected' : '' %>><%= label %></option>
                <% }); %>
            </select>
        </div>
        <h3>Редактирование обращения</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Клиент</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div id="main-form" class="col-md-6" method="post">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Имя</label>
                                    <input id="inputName" name="customer_name" type="text" class="form-control product-property" value="<%=  o.attributes.customer_name %>">
                                </div>
                                <div class="form-group">
                                    <label for="inputPhone" class="control-label">Телефон</label>
                                    <input id="inputPhone" name="customer_phone" type="text" class="form-control product-property" value="<%= o.attributes.customer_phone %>">
                                </div>
                                <div class="form-group">
                                    <label for="inputMail" class="control-label">Дата</label>
                                    <input id="inputMail" name="customer_mail" type="text" class="form-control product-property" value="<%= o.attributes.date._i %>" readonly="true">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</script>
    <script type="text/html" id="tmpl_shop_callback_details_items"><table class="table table-striped">
        <thead>
        <th>Код</th>
        <th>Название</th>
        <th style="text-align: center">Количество</th>
        <th style="text-align: center">Цена</th>
        <th>Комментарий</th>
        <th>&nbsp;</th>
        </thead>
        <tbody>
        <% items.each(function(i){ %>
        <tr data-id="<%= i.id %>">
            <td><%- i.get('code') %></td>
            <td><%- i.get('title') %></td>
            <td style="width: 70px; text-align: center">
                <input class="form-control" type="number" name="qty" value="<%- i.get('qty') %>" min="1" />
            </td>
            <td style="width: 100px; text-align: center">
                <input class="form-control" type="number" name="price" value="<%- i.get('price') %>" step="0.01" min="0" />
            </td>
            <td style="width: 240px;"><%- i.get('comment') %></td>
            <td style="width: 80px; text-align: center">
                <% if(order.get('editable')){ %>
                <a class="save-item-trigger btn btn-xs btn-success disabled" href="#" title="Сохранить изменения">
                    <span class="glyphicon glyphicon-floppy-saved"></span>
                </a>
                <a class="delete-item-trigger btn btn-xs btn-danger" href="#" title="Удалить товар из заказа">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
                <% } else {%>
                &nbsp;
                <% } %>
            </td>
        </tr>
        <% }); %>
        </tbody>
        </table></script>
<script type="text/html" id="tmpl_shop_order_details"><div id="order-detail-form">
    <a href="javascript:window.history.back()" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Назад</a>
    <div class="pull-right">
        Статус заказа:
        <select name="inputStatus" id="inputStatus" class="form-control" style="display: inline-block; width: auto;">
            <% _.each(SerenityShop.data.orderStatuses, function(label,name){
                if(o.get('status')=='cancelled' && (name=='done' || name=='shipped')) return;
            %>
            <option value="<%= name %>" <%= o.get('status')==name ? 'selected' : '' %>><%= label %></option>
            <% }); %>
        </select>
    </div>
    <h3>Редактирование заказа</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Клиент</h3>
                    </div>
                    <% var customer = o.get('customer') %>
                    <div class="panel-body">
                        <div class="row">
                            <div id="main-form" class="col-md-6" method="post">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Имя</label>
                                    <input id="inputName" name="customer_name" type="text" class="form-control product-property"
                                           value="<%- o.get('customer_name') %>">
                                </div>
                                <div class="form-group">
                                    <label for="inputPhone" class="control-label">Телефон</label>
                                    <input id="inputPhone" name="customer_phone" type="text" class="form-control product-property"
                                           value="<%- o.get('customer_phone') %>">
                                </div>
                                <div class="form-group">
                                    <label for="inputMail" class="control-label">E-mail</label>
                                    <input id="inputMail" name="customer_mail" type="text" class="form-control product-property"
                                           value="<%- o.get('customer_mail') %>">
                                </div>
                                <% if(!o.get('customer').id){ %>
                                <div class="form-group">
                                    <label for="inputCustomerAccount" class="control-label">Привязать к профилю</label>
                                    <form id="linkOrderToCustomer">
                                        <div class="row form-group">
                                            <div class="col-sm-12">
                                                <input id="inputCustomerAccount" name="inputCustomerAccount" type="text" class="form-control"
                                                       placeholder="Введите имя, e-mail или телефон клиента">
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div id="selectedAcountInfo" class="col-sm-8">Не выбран клиент</div>
                                            <div class="col-sm-4"><button class="btn btn-info" type="submit" disabled>Привязать</button></div>
                                        </div>
                                    </form>

                                </div>
                                <% } %>
                            </div>
                            <div class="col-md-6" role="form">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputPayment" class="control-label">Оплата</label>
                                        <select name="payment_method" id="inputPayment" class="form-control"  style="max-width: 200px">
                                            <% _.each(SerenityShop.data.paymentMethods,function(label,name){ %>
                                            <option value="<%= name %>" <%= o.get('payment')==name ? 'selected="selected"' : '' %>><%= label %></option>
                                            <% }); %>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Сумма</label>
                                        <div id="orderTotal" class="form-control"><%= SerenityShop.helpers.formatPrice(o.get('total')) %> грн</div>
                                    </div>
                                    <div id="payment-container">
                                        <div class="form-group col-md-12">
                                            <label class="control-label">История платежей</label>
                                            <table class="table table-striped table-condensed">

                                                <tbody><tr>
                                                    <td> 380677662069</td>
                                                    <td>239 UAH</td>
                                                    <td>31.01.2014 16:46</td>
                                                    <td>test</td>
                                                </tr>

                                                </tbody></table>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputShipment" class="control-label">Доставка</label>
                                    <select name="shipment_method" id="inputShipment" class="form-control" style="max-width: 200px">
                                        <% _.each(SerenityShop.data.shipmentMethods,function(label,name){ %>
                                        <option value="<%= name %>" <%= o.get('shipment')==name ? 'selected="selected"' : '' %>><%= label %></option>
                                        <% }); %>
                                    </select>

                                </div>
                                <div id="shipment-container">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <button id="save-order-trigger" class="btn btn-primary" type="button">Сохранить</button>
                                    <button id="reset-order-trigger" class="btn btn-default" type="button">Отменить изменения</button>
                                </div>
                                <% if(o.get('customer').id){ %>
                                    <a class="btn btn-default" target="_blank" href="/admin/index.php?com=shop#customer/<%= o.get('customer').id %>">
                                        <i class="glyphicon glyphicon-user"></i> Профиль клиента
                                    </a>
                                <% } %>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Товары</h3>
                    </div>
                    <div class="panel-body">
                        <% if(o.get('editable')){ %>
                        <div class="pull-right">
                            <button id="add-product-popup-trigger" class="btn btn-sm btn-primary" type="button">
                                <i class="glyphicon glyphicon-plus"></i>
                                Добавить товар
                            </button>
                        </div>
                        <% } %>
                        <div id="order-items-container"></div>

                    </div>
                </div>
            </div>
        </div>
</div></script>
<script type="text/html" id="tmpl_shop_order_details_items"><table class="table table-striped">
    <thead>
        <th>Код</th>
        <th>Название</th>
        <th style="text-align: center">Количество</th>
        <th style="text-align: center">Цена</th>
        <th>Комментарий</th>
        <th>&nbsp;</th>
    </thead>
    <tbody>
    <% items.each(function(i){ %>
    <tr data-id="<%= i.id %>">
    <td><%- i.get('code') %></td>
    <td><%- i.get('title') %></td>
    <td style="width: 70px; text-align: center">
        <input class="form-control" type="number" name="qty" value="<%- i.get('qty') %>" min="1" />
    </td>
    <td style="width: 100px; text-align: center">
        <input class="form-control" type="number" name="price" value="<%- i.get('price') %>" step="0.01" min="0" />
    </td>
    <td style="width: 240px;"><%- i.get('comment') %></td>
    <td style="width: 80px; text-align: center">
        <% if(order.get('editable')){ %>
        <a class="save-item-trigger btn btn-xs btn-success disabled" href="#" title="Сохранить изменения">
            <span class="glyphicon glyphicon-floppy-saved"></span>
        </a>
        <a class="delete-item-trigger btn btn-xs btn-danger" href="#" title="Удалить товар из заказа">
            <span class="glyphicon glyphicon-remove"></span>
        </a>
        <% } else {%>
        &nbsp;
        <% } %>
    </td>
    </tr>
    <% }); %>
    </tbody>
</table></script>
<script type="text/html" id="tmpl_shop_product_modification"><form class="product-modification-form panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><%- p.get('title') %>&nbsp;</h3>
    </div>
    <div class="panel-body">
        <div class="">

            <div class="row">
                <div class="col-md-9">
                    <label for="inputName" class="control-label">Название<%= SerenityShop.data.langLabel %></label>
                    <input name="title" type="text" class="form-control product-property translated-field" id="inputName"
                           value="<%- p.get('title') %>">
                </div>
                <div class="col-md-3">
                    <label for="inputOrder" class="control-label">Порядок</label>
                    <input name="order" type="text" class="form-control product-property" id="inputOrder"
                           value="<%- p.get('order') %>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="inputPrice" class="control-label">Цена</label>

                        <input name="price" type="text" class="form-control product-property" id="inputPrice"
                               value="<%= p.get('price') ? p.get('price').replace('.', ',') : '' %>" placeholder="0,00"
                               pattern="\d+(,\d{2})?">
                </div>
                <div class="col-md-6">
                    <label for="inputSalePrice" class="control-label">Акционная цена</label>

                        <input name="sale_price" type="text" class="form-control product-property" id="inputSalePrice"
                               value="<%= p.get('sale_price') ? p.get('sale_price').replace('.', ',') : '' %>" placeholder="0,00"
                               pattern="\d+(,\d{2})?">
                </div>
                <div class="col-md-6">
                    <label for="inputStock" class="control-label">Склад</label>

                        <input name="stock" type="number" class="form-control product-property" id="inputStock"
                               value="<%= p.get('stock') %>" placeholder="0" pattern="\d+">
                </div>
                <div class="col-md-6">
                    <label for="inputCode" class="control-label">Код 1С</label>
                    <input name="code" type="text" class="form-control product-property"  id="inputCode"
                           value="<%- p.get('code') %>">
                </div>
            </div>
            <label class="control-label">Характеристики</label>
            <table id="option-list" class="table table-striped table-fixed">
                <tbody>
                <% _.each(p.get('options'),function(o){
                    if(!_.contains(SerenityShop.data.modificatorOptions,parseInt(o.option_id))) return;
                %>
                <%= optionTmpl({o: o}) %>
                <% }); %>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <button class="btn btn-primary" type="submit">Сохранить</button>
                        <button class="btn btn-default" type="reset">Отменить изменения</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form></script>
<script type="text/html" id="tmpl_shop_order_list"><div class="clearfix">
    <div class="pull-right">
        <select name="status" id="status-filter">
            <option value="new" <%= filter.status=='new' ? 'selected="selected"' : '' %>>Новый</option>
            <option value="processing" <%= filter.status=='processing' ? 'selected="selected"' : '' %>>Обрабатывается</option>
            <option value="shipped" <%= filter.status=='shipped' ? 'selected="selected"' : '' %>>Отправлен</option>
            <option value="cancelled" <%= filter.status=='cancelled' ? 'selected="selected"' : '' %>>Отменен</option>
            <option value="done" <%= filter.status=='done' ? 'selected="selected"' : '' %>>Выполнен</option>
            <option value="oneClick" <%= filter.status=='oneClick' ? 'selected="selected"' : '' %>>Один клик</option>
        </select>
        <button id="add-order-trigger" class="btn btn-sm btn-primary" type="button">
            <i class="glyphicon glyphicon-plus"></i>
            Добавить новый заказ
        </button>
    </div>
</div>
<div>Страницы: <ul class="list-pagination pagination clearfix" style="vertical-align: middle"></ul></div>
<table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Дата</th>
        <th>Клиент</th>
        <th>Сумма</th>
        <th>Статус</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody id="order-list">

    </tbody>
</table>
<div>Страницы: <ul class="list-pagination pagination clearfix" style="vertical-align: middle"></ul></div></script>
<script type="text/html" id="tmpl_shop_callback_list"><div class="clearfix">
        <div class="pull-right">
            <select name="status" id="status-filter">
                <option value="1" <%= filter.status=='1' ? 'selected="selected"' : '' %>>Новый</option>
                <option value="2" <%= filter.status=='2' ? 'selected="selected"' : '' %>>Выполнен</option>
                <option value="3" <%= filter.status=='3' ? 'selected="selected"' : '' %>>Отменен</option>
            </select>
        </div>
        </div>
        <div>Страницы: <ul class="list-pagination pagination clearfix" style="vertical-align: middle"></ul></div>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Дата</th>
                <th>Клиент</th>
                <th>Статус</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody id="callback-list">

            </tbody>
        </table>
        <div>Страницы: <ul class="list-pagination pagination clearfix" style="vertical-align: middle"></ul></div></script>
<script type="text/html" id="tmpl_shop_callback_list_row"><tr>
            <td><%= o.id %></td>
            <td><%= o.attributes.date._i %></td>
            <td><%= o.get('customer_name') %></td>
            <td><%= SerenityShop.data.callbackStatuses[o.get('status')]  %></td>
            <td>
                <a class="btn btn-xs btn-success" href="#callback/<%= o.id%>">
                    <span class="glyphicon glyphicon-edit"></span>
                </a>
            </td>
            </tr></script>
<script type="text/html" id="tmpl_shop_products"><ol class="breadcrumb">
    <li class="active">Товары</li>
</ol>
<div class="row">
    <div class="col-md-3">
        <ul id="section-nav" class="nav nav-pills nav-stacked">
            <% sections.each(function(s){ %>
                <li>
                    <a href="#products/<%= s.id %>/1" data-id="<%= s.id %>">
                        <% if(s.get('cat_level')>1){ %>
                        <span style="display: inline-block;margin-left: <%= (s.get('cat_level')-1)*10 %>px">&boxur;&boxh;</span>
                        <% } %>
                        <%= s.get('title') %>
                    </a>
                </li>
            <% }); %>
            <li><a href="#products/0">Необработанные товары</a></li>
            <li><a href="#products/-1">Товары из удаленных категорий</a></li>
        </ul>
    </div>
    <div class="col-md-9">
        <div class="pull-right">
            <button id="add-product-trigger" class="btn btn-sm btn-primary" type="button">
                <i class="glyphicon glyphicon-plus"></i>
                Добавить товар
            </button>
            <button class="toggle-sorting-trigger btn btn-sm btn-success" type="button">
                <i class="glyphicon glyphicon-sort"></i>
                Режим сортировки
            </button>
        </div>
        <div>Страницы: <ul class="list-pagination pagination clearfix" style="vertical-align: middle"></ul></div>
        <table class="table">
            <thead>
            <tr style="vertical-align: top;">
                <th style="width: 80px;" class="center">
                    №
                    <a id="save-order-trigger" class="btn btn-xs btn-success" href="#">
                        <span class="glyphicon glyphicon-floppy-disk"></span>
                    </a>
                </th>
                <th style="vertical-align: top;">Название</th>
                <th style="vertical-align: top;">Склад</th>
                <th style="vertical-align: top;">Цена</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody id="product-list">

            </tbody>
        </table>
        <div>Страницы: <ul class="list-pagination pagination clearfix" style="vertical-align: middle"></ul></div>
        </div>
    </div>
</div>
</script>
    <script type="text/html" id="tmpl_shop_order_list_row"><tr>
            <td><%= o.id %></td>
            <td><%= o.get('date').format('L HH:mm') %></td>
            <td>
                <% if(o.get('customer_id')){ %>
                <a href="index.php?com=customers&action=edit&id=<%= o.get('customer_id') %>"><%= o.get('customer_name') %></a>
                <% } else {%>
                <%= o.get('customer_name') %>
                <% } %>
            </td>
            <td><%= o.get('total').replace('.',',') %></td>
            <td><%= SerenityShop.data.orderStatuses[o.get('status')] %></td>
            <td>
                <a class="btn btn-xs btn-success" href="#order/<%= o.id%>">
                    <span class="glyphicon glyphicon-edit"></span>
                </a>
            </td>
            </tr></script>
<script type="text/html" id="tmpl_shop_products_row"><tr data-id="<%= p.id%>">
    <td class="center"><input type="text" value="<%= p.get('order') %>" class="form-control input-sm" style="width: 50px; display: inline-block;" <%= sortMode ? 'readonly' : ''%> /></td>
    <td><%= p.get('title') %></td>
    <td><%= p.get('stock') %></td>
    <td><%= p.get('price') %></td>
    <td>
        <% if (!sortMode){ %>
        <a class="btn btn-xs btn-success" href="#product/<%= p.id%>">
            <span class="glyphicon glyphicon-edit"></span>
        </a>
        <a class="delete-trigger btn btn-xs btn-danger" href="#">
            <span class="glyphicon glyphicon-remove"></span>
        </a>
        <% } else { %>
        &nbsp;
        <% }%>
    </td>
</tr>
<% p.get('modifications').each(function(m){%>
<tr data-modification="<%= m.id%>" data-id="<%= m.get('parent_id') %>">
    <td class="center">&nbsp;</td>
    <td>&boxur;&boxh; <%= m.get('title') %></td>
    <td><%= m.get('stock') %></td>
    <td><%= m.get('price') %></td>
    <td>
        <a class="delete-trigger btn btn-xs btn-danger" href="#">
            <span class="glyphicon glyphicon-remove"></span>
        </a>
    </td>
</tr>
<%}); %></script>
<script type="text/html" id="tmpl_shop_product_details_no_section"><ol class="breadcrumb">
    <li><a href="#products/0">Необработанные товары</a></li>
    <li class="active"><%- p.get('title') %></li>
</ol>
<div class="row">
    <form id="move-product-to-section" class="form-inline col-md-offset-3 col-md-6">
        <h2><%- p.get('title') %></h2>
        <div class="alert alert-info"><strong>Выберите раздел</strong>, чтобы приступить к редактированию товара</div>
        <div class="form-group col-md-9">
            <select id="inputSection" name="section_id" class="form-control">
                <% sections.each(function(s){ %>
                <option value="<%= s.id %>"><%- s.get('title') %></option>
                <% }); %>
            </select>
        </div>
        <div class="form-group col-md-3">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success">Сохранить</button>
            </div>
        </div>
    </form>
</div>
</script>
<script type="text/html" id="tmpl_shop_product_option"><tr>
    <td style="width: 50%; vertical-align: middle;"><%= o.title %><%= SerenityShop.data.langLabel %></td>
    <td>
        <% var variants =  SerenityShop.data.lockedOptions[o.option_id];
        if(!variants){ %>
            <% if(o.title=='Колір' || o.title=='Цвет'){ %>
            <div class="color-picker-container product-option-<%= o.option_id %> input-group">
                <input type="text"
                       class="form-control product-option product-option-<%= o.option_id %> colorInput"
                       data-option_id="<%= o.option_id %>"
                       data-value_id="<%= o.value_id %>"
                       value="<%= o.value %>" />
                <div class="input-group-btn">
                    <button class="btn btn-default color-select-trigger" type="button"><span style="background-color: #<%= o.value || '000000' %>"></span></button>
                </div>
            </div>
            <% } else { %>
            <input type="text" class="form-control product-option product-option-<%= o.option_id %> translated-field"
                   data-option_id="<%= o.option_id %>"
                   data-value_id="<%= o.value_id %>"
                   value="<%= o.value %>"/>
            <% } %>
        <% } else { %>
        <select type="text" class="form-control product-option" data-option_id="<%= o.option_id %>" data-value_id="<%= o.value_id %>">
            <% _.each(variants, function(name,value){ %>
            <option value="<%= value %>" <%= o.value==value ? 'selected="selected"' : '' %>><%= name %></option>
            <% }); %>
        </select>
        <% } %>
    </td>
</tr></script>
<script type="text/html" id="tmpl_shop_product_details"><ol class="breadcrumb">
    <% if(p.get('active')==-1 || !p.get('section').id){ %>
    <li><a href="#products/0">Необработанные товары</a></li>
    <% } else { %>
    <li><a href="#products/<%= p.get('section').id %>"><%- p.get('section').get('title') %></a></li>
    <% } %>
    <li class="active"><%- p.get('title') %></li>
</ol>
<ul class="nav nav-tabs" style="margin-bottom: 20px;">
    <li class="active"><a href="#tab-product-main" data-toggle="tab">Основные</a></li>
    <li><a href="#tab-product-modifications" data-toggle="tab">Модификации товара</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane active" id="tab-product-main">
        <form id="product-detail-form">
            <div class="pull-right">
                <button class="btn btn-primary" type="submit">Сохранить</button>
                <button class="btn btn-default" type="reset">Отменить изменения</button>
            </div>
            <h3>Редактирование товара</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Основные параметры</h3>
                            </div>
                            <div class="panel-body" style="height: 520px">
                                <div id="main-form" class="" method="post">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Название<%= SerenityShop.data.langLabel %></label>
                                        <input name="title" type="text" class="form-control product-property translated-field"  id="inputName"
                                               value="<%- p.get('title') %>">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputCode" class="control-label">Код 1С</label>
                                        <input name="code" type="text" class="form-control product-property"  id="inputCode"
                                               value="<%- p.get('code') %>">
                                    </div>
                                    <% if(sections.length>1){ %>
                                    <div class="form-group">
                                        <label for="inputSection" class="control-label">Раздел</label>
                                        <select name="section_id" class="form-control product-property"  id="inputSection">
                                            <% sections.each(function (s) { %>
                                            <option value="<%= s.id %>" <%= s.id==p.get('section').id ? 'selected="selected"' : ''%>>
                                                <% if(s.get('cat_level')>1){ %>
                                                &boxur;&boxh;
                                                <% } %>
                                                <%= s.get('title') %>
                                            </option>
                                            <% });%>
                                        </select>
                                    </div>
                                    <% } %>
                                    <div class="form-group">
                                        <label for="inputSef" class="control-label">SEF URL</label>
                                        <input name="sef" type="text" class="form-control product-property"  id="inputSef" value="<%- p.get('sef') %>">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputMTitle" class="control-label">Title<%= SerenityShop.data.langLabel %></label>
                                        <textarea name="meta_title" class="form-control product-property translated-field"
                                                  id="inputMTitle"><%- p.get('meta_title') %></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputMDescr" class="control-label">Description<%= SerenityShop.data.langLabel %></label>
                                        <textarea name="meta_description" class="form-control product-property translated-field"
                                                  id="inputMDescr"><%- p.get('meta_description') %></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputMKeyw" class="control-label">Keywords<%= SerenityShop.data.langLabel %></label>
                                        <textarea name="meta_keywords" class="form-control product-property translated-field"
                                                  id="inputMKeyw"><%- p.get('meta_keywords') %></textarea>
                                    </div>
                                    <div class="form-group">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Дополнительные параметры</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-horizontal col-sm-6" role="form">
                                    <div class="form-group">
                                        <label for="inputPrice" class="col-sm-6 control-label">Цена</label>
                                        <div class="col-sm-6">
                                            <input name="price" type="text" class="form-control product-property"  id="inputPrice"
                                                   value="<%= p.get('price') ? p.get('price').replace('.',',') : '' %>" placeholder="0,00" pattern="\d+(,\d{2})?">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputSalePrice" class="col-sm-6 control-label">Акционная цена</label>
                                        <div class="col-sm-6">
                                            <input name="sale_price" type="text" class="form-control product-property"  id="inputSalePrice"
                                                   value="<%= p.get('sale_price') ? p.get('sale_price').replace('.',',') : '' %>" placeholder="0,00" pattern="\d+(,\d{2})?">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputStock" class="col-sm-6 control-label">Склад</label>
                                        <div class="col-sm-6">
                                            <input name="stock" type="number" class="form-control product-property"  id="inputStock"
                                                   value="<%= p.get('stock') %>" placeholder="0" pattern="\d+">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputActive" class="col-sm-6 control-label">Статус</label>
                                        <div class="col-sm-6">
                                            <select name="active" id="inputActive" class="form-control product-property">
                                                <option value="-2" <%= p.get('active')==-2 ? 'selected' :'' %>>Скрытый</option>
                                                <option value="-1" <%= p.get('active')==-1 ? 'selected' :'' %>>Необработан</option>
                                                <option value="0" <%= p.get('active')==0 ? 'selected' :'' %>>Неактивен</option>
                                                <option value="1" <%= p.get('active')==1 ? 'selected' :'' %>>Активен</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputHome" class="col-sm-7 control-label">На главной</label>
                                        <div class="col-sm-5">
                                            <input class="product-flag" name="home" type="checkbox" id="inputHome" style="margin-top: 10px;" <%= p.get('home') ? 'checked' : '' %> value="1">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputNew" class="col-sm-7 control-label">Новинка</label>
                                        <div class="col-sm-5">
                                            <input class="product-flag" name="new" type="checkbox" id="inputNew" style="margin-top: 10px;" <%= p.get('new') ? 'checked' : '' %> value="1">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputHit" class="col-sm-7 control-label">Хит продаж</label>
                                        <div class="col-sm-5">
                                            <input class="product-flag" name="hit" type="checkbox" id="inputHit" style="margin-top: 10px;" <%= p.get('hit') ? 'checked' : '' %> value="1">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputLocalBrand" class="col-sm-7 control-label">Отечественный</label>
                                        <div class="col-sm-5">
                                            <input class="product-flag" name="local_brand" type="checkbox" id="inputLocalBrand" style="margin-top: 10px;" <%= p.get('local_brand') ? 'checked' : '' %> value="1">
                                        </div>
                                    </div>

                                </div>
                                <div class="product-thumb col-sm-6">
                                    <div class="btn-file btn btn-primary">
                                        <span class="glyphicon glyphicon-upload"></span>
                                        Загрузить фото
                                        <input type="file" name="image" id="product-image-input" accept="image/*"/>
                                    </div>
                                    <% if(p.get('image')){ %>
                                    <img src="/media/product/<%= p.id %>_small.<%= p.get('image') %>">
                                    <% } %>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Характеристики товаров</h3>
                            </div>
                            <div class="panel-body" style="height: 248px; padding: 0; overflow: auto">
                                <div id="options-form" action="">
                                    <table id="option-list" class="table table-striped table-fixed">
                                        <tbody>
                                        <% _.each(p.get('options'),function(o){ %>
                                        <%= optionTmpl({o: o}) %>
                                        <% }); %>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Описание товара<%= SerenityShop.data.langLabel %></h3>
                            </div>
                            <div class="panel-body">
                                <div id="description-form" class="form-horizontal" method="post">
                                    <div class="form-group">
                                        <textarea id="description-editor" class="form-control product-property"  name="description"><%=p.get('description')%></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <button class="btn btn-primary" type="submit">Сохранить</button>
                            <button class="btn btn-default" type="reset">Отменить изменения</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>
    <div class="tab-pane" id="tab-product-modifications">
        <div class="pull-right">
            <button id="add-modification-trigger" class="btn btn-sm btn-primary" type="button">
                <i class="glyphicon glyphicon-plus"></i>
                Добавить модификацию
            </button>
        </div>
        <div id="modifications-list" class="row"></div>
    </div>
</div>
</script>
<script type="text/html" id="tmpl_shop_unprocessed_products"><div class="row">
    <div class="col-md-3">
        <ul id="section-nav" class="nav nav-pills nav-stacked">
            <% sections.each(function(s){ %>
                <li>
                    <a href="#products/<%= s.id %>" data-id="<%= s.id %>">
                        <% if(s.get('cat_level')>1){ %>
                        <span style="display: inline-block;margin-left: <%= (s.get('cat_level')-1)*10 %>px">&boxur;&boxh;</span>
                        <% } %>
                        <%= s.get('title') %>
                    </a>
                </li>
            <% }); %>
            <li class="active"><a href="#products/0">Необработанные товары</a></li>
            <li><a href="#products/-1">Товары из удаленных категорий</a></li>
        </ul>
    </div>
    <div class="col-md-9">
        <div class="clearfix">
            <div class="pull-left">
                <input type="text" id="title-filter-input" class="form-control" value="" placeholder="Фильтр по названию">
            </div>
            <div class="pull-right">
                <button id="group-items-trigger" class="btn btn-success disabled" style="margin-right: 10px;">Сгрупировать</button>
                <div class="btn-file btn btn-primary">
                    <span class="glyphicon glyphicon-upload"></span>
                    Загрузить файл
                    <input type="file" name="image" id="product-list-file-input" accept="text/xml"/>
                </div>
            </div>
        </div>
        <div class="clearfix" id="pagination-container" style="margin-top: 10px;">
            <div class="pull-right">
                <button id="prev-page"><i class="glyphicon glyphicon-chevron-left"></i></button>
                <span id="page-range"></span>
                <button id="next-page"><i class="glyphicon glyphicon-chevron-right"></i></button>
            </div>
        </div>
        <div class="scroll-container">
            <table class="table">
                <thead>
                <tr style="vertical-align: top;">
                    <th style="width: 40px;" class="center">&nbsp;</th>
                    <th style="vertical-align: top;">Название</th>
                    <th style="vertical-align: top;">Склад</th>
                    <th style="vertical-align: top;">Цена</th>
                    <th style="width: 130px;" class="center">&nbsp;</th>
                </tr>
                </thead>
                <tbody id="product-list">

                </tbody>
            </table>
        </div>

    </div>
</div>
</script>
<script type="text/html" id="tmpl_shop_unprocessed_products_row"><tr data-id="<%= p.id%>">
    <td class="center"><input class="select-product" type="checkbox" value="<%= p.id %>" /></td>
    <td><%= p.get('title') %></td>
    <td><%= p.get('stock') %></td>
    <td><%= p.get('price') %></td>
    <td>
        <a class="btn btn-xs btn-success" href="#product/<%= p.id%>">
            <span class="glyphicon glyphicon-edit"></span>
        </a>
        <% if(!p.get('modifications').length){ %>
        <a class="attach-trigger btn btn-xs btn-warning" href="#" title="Прикрепить к другому товару">
            <span class="glyphicon glyphicon-pushpin"></span>
        </a>
        <% } %>
        <a class="hide-trigger btn btn-xs btn-warning" href="#" title="Сделать скрытым">
            <span class="glyphicon glyphicon-eye-close"></span>
        </a>
        <a class="delete-trigger btn btn-xs btn-danger" href="#" title="Удалить">
            <span class="glyphicon glyphicon-remove"></span>
        </a>
    </td>
</tr>
<% p.get('modifications').each(function(m){%>
<tr class="modifications_<%= p.id%>" data-modification="<%= m.id%>" data-id="<%= m.get('parent_id') %>">
    <td class="center">&nbsp;</td>
    <td>&boxur;&boxh; <%= m.get('title') %></td>
    <td><%= m.get('stock') %></td>
    <td><%= m.get('price') %></td>
    <td>
        <a class="delete-trigger btn btn-xs btn-danger" href="#">
            <span class="glyphicon glyphicon-remove"></span>
        </a>
    </td>
</tr>
<%}); %></script>
<!---deleted products templates-->
    <script type="text/html" id="tmpl_shop_deleted_products"><div class="row">
        <div class="col-md-3">
            <ul id="section-nav" class="nav nav-pills nav-stacked">
                <% sections.each(function(s){ %>
                <li>
                    <a href="#products/<%= s.id %>" data-id="<%= s.id %>">
                        <% if(s.get('cat_level')>1){ %>
                        <span style="display: inline-block;margin-left: <%= (s.get('cat_level')-1)*10 %>px">&boxur;&boxh;</span>
                        <% } %>
                        <%= s.get('title') %>
                    </a>
                </li>
                <% }); %>
                <li><a href="#products/0">Необработанные товары</a></li>
                <li class="active"><a href="#products/-1">Товары из удаленных категорий</a></li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="clearfix">
                <div class="pull-left">
                    <input type="text" id="title-filter-input" class="form-control" value="" placeholder="Фильтр по названию">
                </div>
                <div class="pull-right">
                    <button id="group-items-trigger" class="btn btn-success disabled" style="margin-right: 10px;">Сгрупировать</button>
                    <div class="btn-file btn btn-primary">
                        <span class="glyphicon glyphicon-upload"></span>
                        Загрузить файл
                        <input type="file" name="image" id="product-list-file-input" accept="text/xml"/>
                    </div>
                </div>
            </div>
            <div class="clearfix" id="pagination-container" style="margin-top: 10px;">
                <div class="pull-right">
                    <button id="prev-page"><i class="glyphicon glyphicon-chevron-left"></i></button>
                    <span id="page-range"></span>
                    <button id="next-page"><i class="glyphicon glyphicon-chevron-right"></i></button>
                </div>
            </div>
            <div class="scroll-container">
                <table class="table">
                    <thead>
                    <tr style="vertical-align: top;">
                        <th style="width: 40px;" class="center">&nbsp;</th>
                        <th style="vertical-align: top;">Название</th>
                        <th style="vertical-align: top;">Склад</th>
                        <th style="vertical-align: top;">Цена</th>
                        <th style="width: 130px;" class="center">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody id="product-list">

                    </tbody>
                </table>
            </div>

        </div>
        </div>
    </script>
    <script type="text/html" id="tmpl_shop_deleted_products_row"><tr data-id="<%= p.id%>">
        <td class="center"><input class="select-product" type="checkbox" value="<%= p.id %>" /></td>
        <td><%= p.get('title') %></td>
        <td><%= p.get('stock') %></td>
        <td><%= p.get('price') %></td>
        <td>
            <a class="btn btn-xs btn-success" href="#product/<%= p.id%>">
                <span class="glyphicon glyphicon-edit"></span>
            </a>
            <% if(!p.get('modifications').length){ %>
            <a class="attach-trigger btn btn-xs btn-warning" href="#" title="Прикрепить к другому товару">
                <span class="glyphicon glyphicon-pushpin"></span>
            </a>
            <% } %>
            <a class="hide-trigger btn btn-xs btn-warning" href="#" title="Сделать скрытым">
                <span class="glyphicon glyphicon-eye-close"></span>
            </a>
            <a class="delete-trigger btn btn-xs btn-danger" href="#" title="Удалить">
                <span class="glyphicon glyphicon-remove"></span>
            </a>
        </td>
        </tr>
        <% p.get('modifications').each(function(m){%>
        <tr class="modifications_<%= p.id%>" data-modification="<%= m.id%>" data-id="<%= m.get('parent_id') %>">
            <td class="center">&nbsp;</td>
            <td>&boxur;&boxh; <%= m.get('title') %></td>
            <td><%= m.get('stock') %></td>
            <td><%= m.get('price') %></td>
            <td>
                <a class="delete-trigger btn btn-xs btn-danger" href="#">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
            </td>
        </tr>
        <%}); %></script>
<!--deleted products templates end-->
    <!-- Javascript files -->
    <script type="text/javascript" src="/lib/js/backbone.js"></script>
    <script type="text/javascript" src="/lib/js/typeahead.jquery.min.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/vendor/moment/moment.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/vendor/moment/ru.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/vendor/moment/uk.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/shop.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/core/helpers.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/core/router.js"></script>

    <!-- MODELS -->
    <script type="text/javascript" src="/admin/template/shop/js/models/base.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/models/section.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/models/product.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/models/order_item.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/models/order.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/models/callback.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/models/customer.js"></script>

    <!-- COLLECTIONS -->
    <script type="text/javascript" src="/admin/template/shop/js/collections/base.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/collections/sections.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/collections/products.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/collections/order_items.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/collections/orders.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/collections/callbacks.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/collections/callback_items.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/collections/customers.js"></script>

        <!-- VIEWS -->
    <script type="text/javascript" src="/admin/template/shop/js/views/base.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/views/product_list.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/views/unprocessed_product_list.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/views/deleted_product_list.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/views/product_details.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/views/order_list.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/views/order_details.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/views/customer_list.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/views/customer_details.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/views/callbacks_list.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/views/callback_details.js"></script>
    <!--popups-->
    <script type="text/javascript" src="/admin/template/shop/js/views/popup/base.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/views/popup/product_selector.js"></script>
    <script type="text/javascript" src="/admin/template/shop/js/views/popup/color_selector.js"></script>
    <!--partials-->
    <script type="text/javascript" src="/admin/template/shop/js/views/partials/modification.js"></script>

{/literal}
<script type="text/javascript">
SerenityShop.data.lockedOptions = {$lockedOptions|json_encode};
SerenityShop.data.modificatorOptions = {$modificatorOptions|array_values|json_encode};
SerenityShop.data.paymentMethods = {$paymentMethods|json_encode};
SerenityShop.data.shipmentMethods = {$shipmentMethods|json_encode};
SerenityShop.data.addressList = {$addressList|json_encode};
SerenityShop.data.langLabel = '{$lang_label}';
</script>
