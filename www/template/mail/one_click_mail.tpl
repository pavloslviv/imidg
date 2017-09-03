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
<h1>Нове замовлення в один клік</h1>
{* INFO *}
<h3>Контактні дані</h3>

<div class="option-group">
    Контактний телефон: {$data.order.customer_phone}<br>
</div>

<h3>Товари</h3>
<div>
    <table>
        <thead>
            <tr>
                <td>Фото</td>
                <td>Розділ</td>
                <td>Код</td>
                <td>Товар</td>
                <td>К-ть</td>
                <td>Ціна</td>
                <td>Коментар</td>
            </tr>
        </thead>
        <tbody>
        {foreach from=$data.products item="p"}
            <tr>
                <td class="image">
                    <a class="image" href="{$HTTP_ROOT}/product/{$p.pid}-{$p.sef}">
                        {if $p.image}
                            <img src="{$HTTP_ROOT}/media/product/{$p.pid}_small.{$p.image}" style="max-height: 100px">
                        {else}
                            <img src="{$HTTP_ROOT}/template/images/placeholder_140.png" style="max-height: 100px>
                        {/if}
                    </a>
                </td>
                <td class="title">{$sections[$p.section_id].title}</td>
                <td class="title">{$p.code}</td>
                <td class="title"><a href="{$HTTP_ROOT}/product/{$p.pid}-{$p.sef}">{$p.title}</a></td>
                <td class="qty">{$p.qty} шт.</span></td>
                <td class="price">{$p.price|price} {$locale.uah}</td>
                <td>{if $p.comment}{$p.comment}{else}&nbsp;{/if}</td>
            </tr>
        {/foreach}
        </tbody>

    </table>
</div>
<div class="summary">
    <div class="total">Загальна вартість замовлення:<span>{$data.order.total|price} {$locale.uah}</span></div>
</div>



</body>
</html>