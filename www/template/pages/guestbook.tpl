

<section class="guest-book">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="sect-title">
                    <h3>{$locale.guest_book}</h3>
                </div>
            </div>
            <div class="col-md-8 col-md-offset-2">
                <div class="guest-book_wrapper">
                    <div class="guest-book_btn">
                        <a href="#comment-popup" class="btn open-modal"><span>{$locale.leave_feedback}</span></a>
                    </div>
                    <div class="comments">
                        {foreach from=$items item='item'}
                            <div class="comments-item question clearfix">
                                <div class="comments-item_left">
                                    <div class="comments-item_name">{$item.client_name}</div>
                                    <div class="comments-item_date">{$item.client_date|date_format:"%d.%m.%Y"}</div>
                                </div>
                                <div class="comments-item_right">
                                    <div class="comments-item_descr">
                                        <p>{$item.text}</p>
                                    </div>
                                </div>
                            </div>
                            {if $item.response_text}
                                <div class="comments-item answer clearfix">
                                    <div class="comments-item_left">
                                        <div class="comments-item_name">{$locale.administrator}</div>
                                        <div class="comments-item_date">{$item.response_date|date_format:"%d.%m.%Y"}</div>
                                    </div>
                                    <div class="comments-item_right">
                                        <div class="comments-item_descr">
                                            <p>{$item.response_text}</p>
                                        </div>
                                    </div>
                                </div>
                            {/if}
                        {/foreach}

                    </div>
                </div>
            </div>

            {if $page_count>0}
                {include file="blocks/pagination.tpl" baseURL='guestbook'}
            {/if}

        </div>
    </div>
</section>



{*<div class="guestbook-list">*}
{*{foreach from=$items item='item'}*}
    {*<div class="item">*}
        {*<div class="heading">*}
            {*<span class="name">{$item.client_name}</span>*}
            {*<span class="date">{$locale.feedback_date} {$item.client_date|date_format:"%d.%m.%Y"}</span>*}
        {*</div>*}
        {*<div class="text">{$item.text}</div>*}
    {*</div>*}
    {*{if $item.response_text}*}
        {*<div class="response">*}
            {*<div class="heading">*}
                {*<span class="name">{$locale.administrator}</span>*}
                {*<span class="date">{$locale.reply_date} {$item.response_date|date_format:"%d.%m.%Y"}</span>*}
            {*</div>*}
            {*<div class="text">{$item.response_text}</div>*}
        {*</div>*}
    {*{/if}*}
{*{/foreach}*}
{*</div>*}


<div id="comment-popup" class="comment-popup">
    <div class="comment-popup_form clearfix">
        <form id="guestbook-post" action="{$lang_suffix}/guestbook?action=post">
            <div class="comment-popup_left">
                <fieldset class="input-item">
                    <label for="comment-name">{$locale.your_name}</label>
                    <input id="comment-name" type="text" name="client_name" value="{if $currentUser}{$currentUser.name}{/if}">
                </fieldset>
                <fieldset class="input-item">
                    <label for="comment-email">{$locale.your_mail}</label>
                    <input id="comment-email" type="email" name="client_mail" value="{if $currentUser}{$currentUser.mail}{/if}">
                </fieldset>
                {*<fieldset class="input-item">*}
                    {*<label for="comment-city">Ваше місто</label>*}
                    {*<input id="comment-city" type="text">*}
                {*</fieldset>*}
            </div>
            <div class="comment-popup_right">
                <fieldset class="input-item">
                    <label for="comment-text">{$locale.feedback_text}</label>
                    <textarea id="comment-text" name="text" placeholder="{$locale.feedback_text_placehoder}"></textarea>
                </fieldset>
            </div>
            <div class="comment-popup_footer box-c">
                {*<div class="comment-popup_stars">*}
                    {*<span>Ваша оцінка</span>*}
                    {*<div class="rating-stars">*}
                        {*<input type="radio" name="star" class="rating" value="1" />*}
                        {*<input type="radio" name="star" class="rating" value="2" />*}
                        {*<input type="radio" name="star" class="rating" value="3" />*}
                        {*<input type="radio" name="star" class="rating" value="4" />*}
                        {*<input type="radio" name="star" class="rating" value="5" />*}
                    {*</div>*}
                {*</div>*}

                <button id="submit-feedback" type="submit" class="btn btn-full"><span>{$locale.send_feedback}</span></button>
            </div>
        </form>
    </div>
</div>

<div id="success_comment" class="simple-modal">
    <p>{$locale.feedback_success}</p>
    <p>{$locale.thank_you}</p>
</div>

{*<form id="guestbook-post" action="{$lang_suffix}/guestbook?action=post">*}
    {*<div class="h1">{$leave.feedback}</div>*}
    {*<div class="heading clearfix">*}
        {*<label>*}
            {*{$locale.your_name}*}
            {*<input type="text" name="client_name" value="{if $currentUser}{$currentUser.name}{/if}" />*}
        {*</label>*}
        {*<label>*}
            {*{$locale.your_mail}*}
            {*<input type="email" name="client_mail" value="{if $currentUser}{$currentUser.mail}{/if}"  />*}
        {*</label>*}
    {*</div>*}
    {*<label class="text">*}
        {*{$locale.feedback_text}:*}
        {*<textarea name="text" id="" cols="30" rows="10" placeholder="{$locale.feedback_text_placehoder}"></textarea>*}
    {*</label>*}
    {*<button id="submit-feedback" class="btn-red-big" type="submit">{$locale.send_feedback}</button>*}
{*</form>*}


{*<script type="text/javascript">*}
    {*$(function(){*}
        {*$('#guestbook-post').on('submit',function(e){*}
            {*e.preventDefault();*}
            {*var $form = $(this),*}
                {*data = $form.serializeObject();*}
            {*$.post('/guestbook?action=post',data,function(r){*}
                {*$form*}
                    {*.find('.helper').remove()*}
                    {*.find('.error').removeClass('error');*}
                {*if(r.result!='success'){*}
                    {*if(r.data){*}
                        {*$form*}
                            {*.find('[name="'+ r.data.field+'"]')*}
                            {*.before('<span class="validation-error">'+ r.data.message+'</span>');*}
                    {*} else {*}
                        {*SerenityShop.alert(r.message,'{$locale.error}!');*}
                    {*}*}
                    {*return;*}
                {*}*}
                {*$form.find('input,textarea').val('');*}
                {*SerenityShop.alert('{$locale.feedback_success}','{$locale.thank_you}!');*}
            {*});*}

        {*});*}
    {*});*}
{*</script>*}