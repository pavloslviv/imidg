
<div class="container">
    <div class="cms-content box-c">
        {if $order && $data.state=='test' || $data.state=='ok'}

        <h1 style="text-align: center;">{$locale.payment_success_h}</h1>

        <div style="max-width: 400px; margin: 0 auto">
            <p style="color: #833939;font-family: Trebuchet MS; font-size: 16px;">
                {$locale.payment_success_message}
            </p>
            <p style="color: #833939;font-family: Trebuchet MS; font-size: 16px;">
                {$locale.order_number}: <strong>{$order.id}</strong>
            </p>
            <p style="color: #833939;font-family: Trebuchet MS; font-size: 16px;">
                {$locale.payment_success_total}: <strong>{$data.amt|price} {$locale.uah}</strong>
            </p>
        </div>

        {$form}
        {else}
            <h1 style="text-align: center;">{$locale.error}!</h1>
            <p style="color: #833939;font-family: Trebuchet MS; font-size: 16px;">
                {$locale.payment_error}
            </p>
        {/if}
    </div>
</div>