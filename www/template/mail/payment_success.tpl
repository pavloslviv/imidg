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
<h1>Надійшла оплата за замовлення</h1>
{* INFO *}
<h3>Контактні дані</h3>

<div class="option-group">
    Через систему LiqPay надійшла оплата за замовлення номер - {$data.payment.order_id}
    <p>
        Сума: {$data.payment.amount}
    </p>
    <p>
        Комісія: {$data.payment.receiver_commission}
    </p>
    <p>
        ID платежу: {$data.payment.liqpay_order_id}
    </p>
</div>


</body>
</html>