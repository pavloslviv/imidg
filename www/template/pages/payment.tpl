<link rel="stylesheet" href="/template/css/privat.css"/>
{if $order}

<h1 style="text-align: center;">{$locale.payment_order_num}{$order.id}</h1>
<div style="max-width: 400px; margin: 0 auto">
    <p style="color: #833939;font-family: Trebuchet MS; font-size: 16px;">
        {$locale.order_number}: <strong>{$order.id}</strong>
    </p>
    <p style="color: #833939;font-family: Trebuchet MS; font-size: 16px;">
        {$locale.payment_sum}: <strong>{$order.total|price} {$locale.uah}</strong>
    </p>
</div>

{$form}
    <div id="privat-steps">
        <div class="step">
            <img src="/template/images/privat/s1.png" alt="Крок 1"/>
            <div class="title">Крок 1.</div>
            <div class="text">
                Натисніть кнопку «Оплатит через Приват24»
            </div>
        </div>
        <div class="privat-arrow"></div>
        <div class="step">
            <img src="/template/images/privat/s2.png" alt="Крок 2"/>
            <div class="title">Крок 2.</div>
            <div class="text">
                Увійдіть в Приват24 під своїм логіном та паролем
            </div>
        </div>
        <div class="privat-arrow"></div>
        <div class="step">
            <img src="/template/images/privat/s3.png" alt="Крок 3"/>
            <div class="title">Крок 3.</div>
            <div class="text">
                Підтвердіть оплату на користь інтернет –магазину  «Імідж»
            </div>
        </div>
    </div>

{else}
    <h1 style="text-align: center;">{$locale.error}</h1>
    <p style="color: #833939;font-family: Trebuchet MS; font-size: 16px;">
        {$locale.payment_not_found}
    </p>
{/if}