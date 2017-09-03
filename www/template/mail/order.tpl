<!DOCTYPE html>
<html>
<head>
    <title>{$data.meta_title}</title>
    <meta name="description" content="{$data.meta_descr}">
    <meta name="keywords" content="{$data.meta_keyw}">

    {literal}
        <style type="text/css">
            html, body {
                margin: 0;
                padding: 0;
            }

            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 14px;
                color: #212121;
                padding: 50px;
            }

            a img {
                border: none;
            }

            h1, h4 {
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                color: #d15151; /* text color */
            }
            h2, h3 {
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            }

            h1 {
                font-size: 24px;
                font-weight: normal;
            }

            p {
                margin: 0;
                margin-bottom: 5px;
            }
            table {
                border-collapse: collapse;
            }
            td {
                padding: 5px;
                border: 2px solid #833939;
            }
        </style>
    {/literal}
</head>
<body>
<h1>Дякуємо за покупку!</h1>
{* INFO *}
<h3>Контактні дані</h3>

<div class="option-group">
    Ім'я: {$data.order.customer_name}<br>
    E-mail: {$data.order.customer_mail}<br>
    Контактний телефон: {$data.order.customer_phone}<br>
</div>

<h4>Найближчим часом наш менеджер зв’яжеться з Вами для підтвердження замовлення.</h4>

<h3>Ваше замовлення</h3>
<div id="cart-items">
    <table>
        {foreach from=$data.products item="p"}
            <tr>
                <td class="image">
                    <a class="image" href="{$HTTP_ROOT}/product/{$p.pid}-{$p.sef}">
                        {if $p.image}
                            <img src="{$HTTP_ROOT}/media/product/{$p.pid}_small.{$p.image}">
                        {else}
                            <img src="{$HTTP_ROOT}/template/images/placeholder_140.png">
                        {/if}
                    </a>
                </td>
                <td class="title"><a href="{$HTTP_ROOT}/product/{$p.pid}-{$p.sef}">{$p.title}</a></td>
                <td class="qty">{$p.qty} шт.</span></td>
                <td class="price">{$p.price|price} {$locale.uah}</td>
            </tr>
        {/foreach}
    </table>
</div>
<div class="summary">
    <div class="total">Загальна вартість замовлення:<span>{$data.order.total|price} грн</span></div>
</div>

{* SHIPPING *}
<h3>Доставка</h3>
<div>
    {if $data.order.shipment=='courier'}
        <p>Кур'єром по Львову</p>
        <p>Адреса:<br>
            {$data.order.shipment_data.courier_address}
        </p>
    {elseif $data.order.shipment=='new_post'}
        <p>Нова Пошта</p>
        <p>Місто: {$data.order.shipment_data.city}</p>
        <p>Склад №: {$data.order.shipment_data.warehouse}</p>
    {elseif $data.order.shipment=='post'}
        <p>Укрпошта</p>
        <p>Адреса:<br>
            {$data.order.shipment_data.address}
        </p>
    {/if}
</div>

{* PAYMENT *}
<h3>Оплата</h3>
<div>
    {if $data.order.payment=='cash'}
        <p>Оплата при отриманні</p>
    {elseif $data.order.payment=='privat24'}
        <p>Оплата  здійснюється згідно інструкції,яку надішле Вам менеджер після підтвердження замовлення.</p>
    {elseif $data.order.payment=='bank'}
        <p>Рахунок для оплати у банку</p>
        <div>{$data.bank_info}</div>
    {elseif $data.order.payment=='liqpay'}
        <p>Оплата за допомогою LiqPay</p>
    {/if}
</div>
<h3 style="color: #d15151;">Сподіваємося ,що співпраця з нами принесе Вам тільки позитивні емоції!</h3>
<p style="color: #d15151;">У разі виникнення додаткових питань телефонуйте<br>
    {$data.phones}
<p style="color: #d15151;">З найкращими побажаннями ,<br>
    Ваш «Імідж»</p>


</body>
</html>