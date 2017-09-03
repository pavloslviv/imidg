<section class="container cms_content">
    <div class="row">

        {if $error}
            <div class="box-c">
                <h1 class="h1" style="text-align: center;">{$locale.wrong_recovery_code}</h1>
            </div>
        {else}
        {*{literal}*}
            {*<script type="text/javascript">*}
                {*$(function(){*}
                    {*$('#recover-form').on('submit',function(e){*}
                        {*e.preventDefault();*}
                        {*var $form = $(e.currentTarget),*}
                            {*data = $form.serializeObject();*}
                        {*$.post('/customer/recover_pass',data,function(r){*}
                            {*$form.find('.helper').remove();*}
                            {*$form.find('.error').removeClass('error');*}
                            {*if(r.result!='success'){*}
                                {*if(r.data.field){*}
                                    {*$form*}
                                        {*.find('input[name="'+ r.data.field+'"]')*}
                                        {*.after('<span class="helper">'+ r.data.message+'</span>')*}
                                        {*.parents('.input-item').addClass('error')*}
                                {*} else {*}
                                    {*SerenityShop.alert(r.data.message,'Помилка!');*}
                                {*}*}
                                {*return;*}
                            {*}*}
                            {*SerenityShop.alert(r.data.message,'Вітаємо!',function(){*}
                                {*window.location.href='/';*}
                            {*});*}
                        {*});*}

                    {*});*}
                {*});*}
            {*</script>*}
        {*{/literal}*}
            <div class="col-lg-offset-3 col-lg-6 col-md-offset-2 col-md-8 col-sm-offset-1 col-sm-10">
                <form id="recover-form" class="form" autocomplete='off'>
                    <div class="box-c">
                        <h1 class="h1">{$locale.recovery_h}</h1>
                    </div>
                    <input type="hidden" name="code" value="{$code}">

                    <fieldset class="input-item">
                        <label for="signup-mail">E-mail <sup>*</sup></label>
                        <input id="signup-mail" type="email" name="mail">
                    </fieldset>
                    <fieldset class="input-item">
                        <label for="signup-pass">{$locale.new_pass} <sup>*</sup></label>
                        <input id="signup-pass" type="password" name="pass">
                    </fieldset>
                    <fieldset class="input-item">
                        <label for="signup-pass-confirm">{$locale.confirm_pass} <sup>*</sup></label>
                        <input id="signup-pass-confirm" type="password" name="pass-confirm">
                    </fieldset>

                    <div class="input-item_title"><sup>*</sup> - {$locale.required_field}</div>

                    <div class="box-c">
                        <button type="submit" class="btn btn-full">
                            <span>{$locale.save}</span>
                        </button>
                    </div>

                </form>
            </div>
        {/if}
    </div>
</section>
