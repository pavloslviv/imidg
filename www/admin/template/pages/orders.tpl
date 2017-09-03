{*<link rel="stylesheet" type="text/css" href="/lib/jquery/fullcalendar/fullcalendar.css" />
<script type="text/javascript" src="/lib/jquery/fullcalendar/fullcalendar.min.js"></script>
<script type="text/javascript" src="template/js/calendar_defaults.js"></script>*}
<script type="text/javascript" src="/lib/jquery/jquery.tools.min.js"></script>
<script type="text/javascript" src="template/js/orders.js"></script>
<script type="text/javascript">
    App.orderList = {$items|json_encode};
    App.data.severTimeOffset = -{$severTimeOffset*1000};
</script>
<div class="container" id="orders-container">
    <div class="row">
        <div class="span10">
            <h2>Заказы</h2>
            {*<div id="calendar"></div>*}
        </div>
        <div class="span2" style="text-align: right">
            <a class="btn btn-primary" href="index.php?com=orders&action=edit&id=0">Создать заказ</a>
        </div>
    </div>
    <div>
        Показывать только:
        <select id="status-filter">
            <option value="all">Все</option>
            <option value="draft">Черновик</option>
            <option value="hold">Зарезервирован</option>
            <option value="done">Выполнен</option>
            <option value="expired">Оплата просрочена</option>
            <option value="deleted">Удален</option>
        </select>
         <input id="date-filter-start" type="text" class="date-input" data-type="start"> по <input id="date-filter-end" type="text" class="date-input" data-type="end">
    </div>

    <table id="user_list" class="paramlist table table-striped table-condensed">
        <thead>
        <tr>
            <th>Дата</th>
            <th>Имя</th>
            <th>Контакты</th>
            <th>Стоимость</th>
            <th>Скидка</th>
            <th>Статус</th>
            <th>Общее время</th>
            <th style="width: 155px;">Действия</th>
        </tr>
        </thead>
        <tbody id="order-list">
        </tbody>
    </table>
</div>
<script type="text/template" id="tmpl-order-list">
    <% _.each(items,function(item){ %>
    <tr data-id="<%= item.id %>">
        <td><%= App.formatDate(item.date) %> <%= App.formatTime(item.date) %></td>
        <td><%= item.name %></td>
        <td>
            Телефон: <%= item.phone %><br>
            Skype: <%= item.skype %><br>
            E-mail: <%= item.mail %>
        </td>
        <td><%= item.price %></td>
        <td><%= item.discount %></td>
        <td>
            <% } else if (item.status=='draft'){ %>
            <span class="label">Черновик</span>
            <% } else if(item.status=='hold'){ %>
            <span class="label label-warning">Зарезервирован</span>
            <% } else if (item.status=='expired'){ %>
            <span class="label label-important">Оплата просрочена</span>
            <% }  else if (item.status=='done'){ %>
            <span class="label label-info">Выполнен</span>
            <% }  else if (item.status=='deleted'){ %>
            <span class="label label-inverse">Удален</span>
            <% } %>
        </td>
        <td>
            <% if(item.total_time){ %>
        <%= Math.floor(item.total_time/3600) %> ч. <i class="icon-info-sign popover-trigger" title="Часы заказа"></i>
            <div style="display: none">
            <% if(item.periods){ %>
            <% _.each(item.periods,function(period){ %>
                <p><%= App.formatDate(period.start) %> <%= App.formatTime(period.start) %> <%= period.full_name %></p>
            <% });
               }
                }%>
            </div>
        </td>
        <td>
            <a class="btn btn-mini btn-success" href="index.php?com=orders&action=edit&id=<%= item.id %>"><i class="icon-edit"></i> править</a>
        {*<a class="delete btn btn-mini btn-danger" href="index.php?com=orders&action=delete&id=<%= item.id %>"><i class="icon-trash"></i> удалить</a>*}
        </td>
    </tr>
    <% }); %>
</script>