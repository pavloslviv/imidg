<div class="container" style="text-align: center">

    <div class="cms-content box-c">

        <h3 style="color: #833939;">{$locale.order_number} {$order->get('id')}</h3>

        <h3 style="color: #833939;">{$locale.order_sent}: {$order->get('customer_mail')}</h3>

        <h2>{$locale.order_thank_you}</h2>

    {if $payment eq 'liqpay'}

        <p>{$locale.pay_now_info}</p>

        {$liqPay->checkout()}

        <div class="row">
            <div id="privat-steps">
                <div class="step step-button">
                    <div class="button-img">
                        <img src="/template/images/pay_now.png" alt="Крок 1"/>
                    </div>
                    <div class="title">{$locale.step} 1.</div>
                    <div class="text">
                        {$locale.press_pay_now}

                    </div>
                </div>
                <div class="privat-arrow"></div>
                <div class="step">
                    <img src="/template/images/pay_now2.png" alt="Крок 2"/>
                    <div class="title">{$locale.step} 2.</div>
                    <div class="text">
                        {$locale.write_data}

                    </div>
                </div>
                <div class="privat-arrow"></div>
                <div class="step step-button">
                    <div class="button-img">
                        <img src="/template/images/pay_now3.png" alt="Крок 3"/>
                    </div>
                    <div class="title">{$locale.step} 3.</div>
                    <div class="text">
                        {$locale.accept_payment}
                    </div>
                </div>
            </div>
        </div>
    {/if}

    </div>
</div>

