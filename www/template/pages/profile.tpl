
<section class="cabinet">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="main-tabs cabinet-tabs">
                    <div class="main-tabs_links">
                        <ul class="clearfix">
                            <li><a href="#" class="tab-link active">{$locale.user_data}<i class="icon-2-down"></i></a></li>
                            <li><a href="#" class="tab-link active">{$locale.order_list}<i class="icon-2-down"></i></a></li>
                            <!--<li><a href="#" class="tab-link">список бажаного<i class="icon-2-down"></i></a></li>
                            <li><a href="#" class="tab-link">мої відгуки<i class="icon-2-down"></i></a></li>
                            <li><a href="#" class="tab-link">переглянуті товари<i class="icon-2-down"></i></a></li>-->
                        </ul>
                    </div>
                    <div class="main-tabs_content">
                        <div class="one-tab user-data">
                            <div class="user-data_form clearfix">
                                <form action="">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="user-data_title ftl">{$locale.edit_user_data}</div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <fieldset class="input-item">
                                                        <label for="user-name">{$locale.pib}</label>
                                                        <input id="user-name" type="text" value="{$customer.name}" disabled>
                                                        <button class="toggle-editing icon-edit" data-type="text" data-name="name"></button>
                                                    </fieldset>
                                                    <fieldset class="input-item">
                                                        <label for="user-email">E-mail</label>
                                                        <input id="user-email" type="email" value="{$customer.mail}" disabled>
                                                        <button class="toggle-editing icon-edit" data-type="email" data-name="mail"></button>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <fieldset class="input-item">
                                                        <label for="user-tel">{$locale.phone}</label>
                                                        <input id="user-tel" type="tel" value="{$customer.phone}" disabled>
                                                        <button class="toggle-editing icon-edit" data-type="tel" data-name="phone"></button>
                                                    </fieldset>

                                                    <fieldset id="discount-container" class="control input-item">
                                                        <label for="disc">{$locale.discount_code}:</label>

                                                        <input id="disc" type="text" value="{if $discount}{if $discount.code}{$discount.code}{else}{$locale.discount_processing}{/if} ({$discount.discount|price}%){else}{$locale.discount_none}{/if}" disabled>
                                                        {*<span style="display: inline-block;font-size: 12px">({$locale.discount_changes_left}: <span id="discount-change-counter">{$customer.discount_change}</span> )</span>*}

                                                        {*{if $customer.discount_change>0 && (!$discount || $discount.code)}*}
                                                            <button id="editDiscount" class="toggle-editing icon-edit" data-type="text" data-name="discount"></button>
                                                        {*{/if}*}
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="user-data_title ftl">{$locale.old_pass}</div>
                                            <div class="row" id="password-container">
                                                <div class="col-md-12">
                                                    <fieldset class="input-item">
                                                        <label for="user-pass_old">{$locale.old_pass}</label>
                                                        <input id="user-pass_old" name="old-password" type="password" disabled>
                                                    </fieldset>
                                                    <fieldset class="input-item">
                                                        <label for="user-pass_new">{$locale.new_pass}</label>
                                                        <input id="user-pass_new" name="pass" type="password" disabled>
                                                    </fieldset>
                                                    <fieldset class="input-item">
                                                        <label for="user-pass_repeat">{$locale.confirm_pass}</label>
                                                        <input id="user-pass_repeat" name="pass-confirm" type="password" disabled>
                                                    </fieldset>
                                                    <div class="box-r user-data_btn">
                                                        <button type="button" id="editPassword" data-name="pass" class="btn btn-full">
                                                            <span>{$locale.edit}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="one-tab user-products order-accordion">
                            {if $orders}
                                <div class="hidden">
                                    {rsort($orders)}
                                </div>
                                <ul>
                                    {foreach from=$orders item="o"}
                                        <li>
                                            <input type="radio" id="input_{$o.id}" data-order="{$o.id}" name="order">
                                            <label for="input_{$o.id}" data-order="{$o.id}">{$locale.order_no} {$o.id} ({$o.date|date_format:"d-m-Y"})</label>
                                            <div class="tab" id="order_{$o.id}">
                                                <div class="content">
                                                    <div class="order_info">
                                                        <span>{$locale.order_status}:
                                                            <b>
                                                                {if $o.status=='new'}
                                                                    {$locale.order_status_new}
                                                                {elseif $o.status=='processing'}
                                                                    {$locale.order_status_processing}
                                                                {elseif $o.status=='shipped'}
                                                                    {$locale.order_status_shipped}
                                                                {elseif $o.status=='cancelled'}
                                                                    {$locale.order_status_cancelled}
                                                                {elseif $o.status=='done'}
                                                                    {$locale.order_status_done}
                                                                {/if}
                                                            </b>
                                                        </span>
                                                    </div>
                                                    <div class="order_list">

                                                    </div>

                                                </div>
                                            </div>
                                        </li>
                                    {/foreach}
                                </ul>

                            {else}
                                <h2>{$locale.cart_is_empty_detailed}</h2>
                            {/if}
                        </div>
                        <!--<div class="one-tab user-products">
                            <div class="user-products_count">2 Товари</div>
                            <div class="user-products_item clearfix">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                        <div class="user-products_img">
                                            <div class="cell">
                                                <a href="#"><img src="assets/img/one-good_1.jpg" alt="alt"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-7 col-sm-6 col-xs-6">
                                        <div class="user-products_title">
                                            <b>Lacoste Essentia</b>
                                            <span>Туалетна вода 125 мл</span>
                                        </div>
                                        <div class="user-products_availability available">Товар в наявності!</div>
                                        <div class="user-products_price"><b>1 677</b> грн.</div>
                                        <div class="user-products_amount">
                                            <span>Кількість:</span>
                                            <input type="number" value="1" min="1">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                        <div class="box-r user-products_btn">
                                            <a href="#" class="btn btn-full"><span>+  До кошика</span></a>
                                        </div>
                                        <div class="box-c">
                                            <a href="#" class="user-products_del">Видалити</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="user-products_item clearfix">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                        <div class="user-products_img">
                                            <div class="cell">
                                                <a href="#"><img src="assets/img/one-good_2.jpg" alt="alt"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-7 col-sm-6 col-xs-6">
                                        <div class="user-products_title">
                                            <b>Lacoste Essentia</b>
                                            <span>Туалетна вода 125 мл</span>
                                        </div>
                                        <div class="user-products_availability unavailable">нема в наявності</div>
                                        <div class="user-products_price"><b>1 677</b> грн.</div>
                                        <div class="user-products_amount">
                                            <span>Кількість:</span>
                                            <input type="number" value="1" min="1">
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                                        <div class="box-r user-products_btn">
                                            <a href="#" class="btn btn-disabled"><span>+  До кошика</span></a>
                                        </div>
                                        <div class="user-products_notif">
                                            <input type="checkbox" class="checkbox" id="checkbox_1">
                                            <label class="checkbox-label" for="checkbox_1">
                                                <i class="icon-checked-1"></i>Повідомити про наявність
                                            </label>
                                        </div>
                                        <div class="box-c">
                                            <a href="#" class="user-products_del">Видалити</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="one-tab user-comments clearfix">
                            <div class="row">
                                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                                    <div class="comments">
                                        <div class="comments-item question clearfix">
                                            <div class="comments-item_left">
                                                <div class="comments-item_name">Тарас</div>
                                                <div class="comments-item_date">06 Жовтень 2016</div>
                                            </div>
                                            <div class="comments-item_right">
                                                <div class="comments-item_descr">
                                                    <p>Доброго дня! Чи наявнi парфуми Burberry London? Я у Вас вже замовляла,не можу знайти.Дякую</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comments-item answer clearfix">
                                            <div class="comments-item_left">
                                                <div class="comments-item_name">Адміністратор</div>
                                                <div class="comments-item_date">06 Жовтень 2016</div>
                                            </div>
                                            <div class="comments-item_right">
                                                <div class="comments-item_descr">
                                                    <p>Добрий день, Олена! Дякуємо за звернення. Зателефонуйте в наш інтернет-магазин, оформіть замовлення і ми надішлемо Ваш улюблений аромат.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comments-item question clearfix">
                                            <div class="comments-item_left">
                                                <div class="comments-item_name">Тарас</div>
                                                <div class="comments-item_date">06 Жовтень 2016</div>
                                            </div>
                                            <div class="comments-item_right">
                                                <div class="comments-item_descr">
                                                    <p>Доброго дня! Чи наявнi парфуми Burberry London? Я у Вас вже замовляла,не можу знайти.Дякую</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comments-item answer clearfix">
                                            <div class="comments-item_left">
                                                <div class="comments-item_name">Адміністратор</div>
                                                <div class="comments-item_date">06 Жовтень 2016</div>
                                            </div>
                                            <div class="comments-item_right">
                                                <div class="comments-item_descr">
                                                    <p>Добрий день, Олена! Дякуємо за звернення. Зателефонуйте в наш інтернет-магазин, оформіть замовлення і ми надішлемо Ваш улюблений аромат.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comments-item question clearfix">
                                            <div class="comments-item_left">
                                                <div class="comments-item_name">Тарас</div>
                                                <div class="comments-item_date">06 Жовтень 2016</div>
                                            </div>
                                            <div class="comments-item_right">
                                                <div class="comments-item_descr">
                                                    <p>Доброго дня! Чи наявнi парфуми Burberry London? Я у Вас вже замовляла,не можу знайти.Дякую</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comments-item answer clearfix">
                                            <div class="comments-item_left">
                                                <div class="comments-item_name">Адміністратор</div>
                                                <div class="comments-item_date">06 Жовтень 2016</div>
                                            </div>
                                            <div class="comments-item_right">
                                                <div class="comments-item_descr">
                                                    <p>Добрий день, Олена! Дякуємо за звернення. Зателефонуйте в наш інтернет-магазин, оформіть замовлення і ми надішлемо Ваш улюблений аромат.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="one-tab browse-products clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4">
                                    <div class="one-good with-sale">
                                        <div class="one-good_ribbon new">New</div>
                                        <div class="one-good_ribbon sale">акція</div>
                                        <div class="one-good_img">
                                            <div class="cell">
                                                <a href="#"><img src="assets/img/one-good_1.jpg" alt="alt"></a>
                                            </div>
                                        </div>
                                        <div class="one-good_name"><a href="#">Lacoste Essential</a></div>
                                        <div class="one-good_stars">
                                            <input type="radio" name="star" class="rating" value="1" />
                                            <input type="radio" name="star" class="rating" value="2" />
                                            <input type="radio" name="star" class="rating" value="3" />
                                            <input type="radio" name="star" class="rating" value="4" />
                                            <input type="radio" name="star" class="rating" value="5" />
                                        </div>
                                        <div class="one-good_price">
                                            <span class="old-price">968 грн</span>
                                            <b>677 грн</b>
                                        </div>
                                        <div class="one-good_footer">
                                            <a href="#" class="btn"><span>+  До кошика</span></a>
                                            <a href="#" class="btn to-favorite">
                                                <i class="fa fa-heart-o before" aria-hidden="true"></i>
                                                <i class="fa fa-heart after" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="one-good">
                                        <div class="one-good_ribbon new">New</div>
                                        <div class="one-good_img">
                                            <div class="cell">
                                                <a href="#"><img src="assets/img/one-good_2.jpg" alt="alt"></a>
                                            </div>
                                        </div>
                                        <div class="one-good_name"><a href="#">Burberry Sport Ice Burberry Sport Ice Burberry Sport Ice</a></div>
                                        <div class="one-good_stars">
                                            <input type="radio" name="star" class="rating" value="1" />
                                            <input type="radio" name="star" class="rating" value="2" />
                                            <input type="radio" name="star" class="rating" value="3" />
                                            <input type="radio" name="star" class="rating" value="4" />
                                            <input type="radio" name="star" class="rating" value="5" />
                                        </div>
                                        <div class="one-good_price">
                                            <b>611 грн</b>
                                        </div>
                                        <div class="one-good_footer">
                                            <a href="#" class="btn"><span>+  До кошика</span></a>
                                            <a href="#" class="btn to-favorite">
                                                <i class="fa fa-heart-o before" aria-hidden="true"></i>
                                                <i class="fa fa-heart after" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="one-good with-sale">
                                        <div class="one-good_ribbon new">New</div>
                                        <div class="one-good_ribbon sale">акція</div>
                                        <div class="one-good_img">
                                            <div class="cell">
                                                <a href="#"><img src="assets/img/one-good_3.jpg" alt="alt"></a>
                                            </div>
                                        </div>
                                        <div class="one-good_name"><a href="#">Lanvin Eclat de Fleurs</a></div>
                                        <div class="one-good_stars">
                                            <input type="radio" name="star" class="rating" value="1" />
                                            <input type="radio" name="star" class="rating" value="2" />
                                            <input type="radio" name="star" class="rating" value="3" />
                                            <input type="radio" name="star" class="rating" value="4" />
                                            <input type="radio" name="star" class="rating" value="5" />
                                        </div>
                                        <div class="one-good_price">
                                            <span class="old-price">968 грн</span>
                                            <b>677 грн</b>
                                        </div>
                                        <div class="one-good_footer">
                                            <a href="#" class="btn"><span>+  До кошика</span></a>
                                            <a href="#" class="btn to-favorite">
                                                <i class="fa fa-heart-o before" aria-hidden="true"></i>
                                                <i class="fa fa-heart after" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="one-good">
                                        <div class="one-good_ribbon new">New</div>
                                        <div class="one-good_img">
                                            <div class="cell">
                                                <a href="#"><img src="assets/img/one-good_4.jpg" alt="alt"></a>
                                            </div>
                                        </div>
                                        <div class="one-good_name"><a href="#">J.H.a G. Mad Madame J.H.a G. Mad Madame</a></div>
                                        <div class="one-good_stars">
                                            <input type="radio" name="star" class="rating" value="1" />
                                            <input type="radio" name="star" class="rating" value="2" />
                                            <input type="radio" name="star" class="rating" value="3" />
                                            <input type="radio" name="star" class="rating" value="4" />
                                            <input type="radio" name="star" class="rating" value="5" />
                                        </div>
                                        <div class="one-good_price">
                                            <b>611 грн</b>
                                        </div>
                                        <div class="one-good_footer">
                                            <a href="#" class="btn"><span>+  До кошика</span></a>
                                            <a href="#" class="btn to-favorite">
                                                <i class="fa fa-heart-o before" aria-hidden="true"></i>
                                                <i class="fa fa-heart after" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="one-good">
                                        <div class="one-good_ribbon new">New</div>
                                        <div class="one-good_img">
                                            <div class="cell">
                                                <a href="#"><img src="assets/img/one-good_5.jpg" alt="alt"></a>
                                            </div>
                                        </div>
                                        <div class="one-good_name"><a href="#">Burberry Sport Ice Burberry Sport Ice Burberry Sport Ice</a></div>
                                        <div class="one-good_stars">
                                            <input type="radio" name="star" class="rating" value="1" />
                                            <input type="radio" name="star" class="rating" value="2" />
                                            <input type="radio" name="star" class="rating" value="3" />
                                            <input type="radio" name="star" class="rating" value="4" />
                                            <input type="radio" name="star" class="rating" value="5" />
                                        </div>
                                        <div class="one-good_price">
                                            <b>611 грн</b>
                                        </div>
                                        <div class="one-good_footer">
                                            <a href="#" class="btn"><span>+  До кошика</span></a>
                                            <a href="#" class="btn to-favorite">
                                                <i class="fa fa-heart-o before" aria-hidden="true"></i>
                                                <i class="fa fa-heart after" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="one-good with-sale">
                                        <div class="one-good_ribbon new">New</div>
                                        <div class="one-good_ribbon sale">акція</div>
                                        <div class="one-good_img">
                                            <div class="cell">
                                                <a href="#"><img src="assets/img/one-good_6.jpg" alt="alt"></a>
                                            </div>
                                        </div>
                                        <div class="one-good_name"><a href="#">Lanvin Eclat de Fleurs</a></div>
                                        <div class="one-good_stars">
                                            <input type="radio" name="star" class="rating" value="1" />
                                            <input type="radio" name="star" class="rating" value="2" />
                                            <input type="radio" name="star" class="rating" value="3" />
                                            <input type="radio" name="star" class="rating" value="4" />
                                            <input type="radio" name="star" class="rating" value="5" />
                                        </div>
                                        <div class="one-good_price">
                                            <span class="old-price">968 грн</span>
                                            <b>677 грн</b>
                                        </div>
                                        <div class="one-good_footer">
                                            <a href="#" class="btn"><span>+  До кошика</span></a>
                                            <a href="#" class="btn to-favorite">
                                                <i class="fa fa-heart-o before" aria-hidden="true"></i>
                                                <i class="fa fa-heart after" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



{*<div class="h1">{$locale.your_data}</div>*}
{*<div id="profile-form">*}
    {*<div class="control">*}
        {*<label for="">{$locale.pib}:</label>*}

        {*<div class="field simple">*}
            {*<span class="input-container">{$customer.name}</span>*}
            {*<button class="toggle-editing" data-type="text" data-name="name"><i class="icon icon-edit"></i></button>*}
        {*</div>*}
    {*</div>*}
    {*<div class="control">*}
        {*<label for="">E-mail:</label>*}

        {*<div class="field simple">*}
            {*<span class="input-container">{$customer.mail}</span>*}
            {*<button class="toggle-editing" data-type="email" data-name="mail"><i class="icon icon-edit"></i></button>*}
        {*</div>*}
    {*</div>*}
    {*<div id="password-container" class="control">*}
        {*<label for="">{$locale.pass}:</label>*}

        {*<div class="field">*}
            {*<span class="input-container">*********</span>*}
            {*<button id="editPassword" class="toggle-editing" data-type="password" data-name="pass"><i class="icon icon-edit"></i></button>*}
        {*</div>*}
    {*</div>*}
    {*<div class="control">*}
        {*<label for="">{$locale.phone}:</label>*}

        {*<div class="field simple">*}
            {*<span class="input-container">{$customer.phone}</span>*}
            {*<button class="toggle-editing" data-type="tel" data-name="phone"><i class="icon icon-edit"></i></button>*}
        {*</div>*}
    {*</div>*}
    {*<div id="discount-container" class="control">*}
        {*<label for="">{$locale.discount_code}:</label>*}

        {*<div class="field">*}
            {*<span class="input-container">{if $discount}{if $discount.code}{$discount.code}{else}{$locale.discount_processing}{/if} ({$discount.discount|price}%){else}{$locale.discount_none}{/if}</span>*}
            {*{if $customer.discount_change>0 && (!$discount || $discount.code)}*}
                {*<button id="editDiscount" class="toggle-editing" data-type="text" data-name="discount"><i class="icon icon-edit"></i></button>*}
            {*{/if}*}
            {*<span style="font-size: 12px">({$locale.discount_changes_left}: <span id="discount-change-counter">{$customer.discount_change}</span> )</span>*}
        {*</div>*}
    {*</div>*}
    {*<div id="profile-subscribe" class="control">*}
        {*<label><input type="checkbox"{if $customer.subscribe==1} checked{/if}/>{$locale.subscription}</label>*}
    {*</div>*}
{*</div>*}
{*{if $orders}*}
{*<div class="h1">{$locale.order_list}</div>*}
{*<div id="order-list">*}
    {*{foreach from=$orders item="o"}*}
        {*<div id="order_{$o.id}" class="order-item">*}
            {*<div class="order-heading">*}
                {*<div class="link">*}
                    {*<a href="#" data-order="{$o.id}"><i class="icon icon-toggle"></i>{$locale.order_no} {$o.id}</a>*}
                {*</div>*}

                {*<table>*}
                    {*<tr>*}
                        {*<td>*}
                            {*{$locale.order_status}:*}
                            {*{if $o.status=='new'}*}
                                {*{$locale.order_status_new}*}
                            {*{elseif $o.status=='processing'}*}
                                {*{$locale.order_status_processing}*}
                            {*{elseif $o.status=='shipped'}*}
                                {*{$locale.order_status_shipped}*}
                            {*{elseif $o.status=='cancelled'}*}
                                {*{$locale.order_status_cancelled}*}
                            {*{elseif $o.status=='done'}*}
                                {*{$locale.order_status_done}*}
                            {*{/if}*}
                        {*</td>*}
                        {*<td>*}
                            {*{$locale.order_total}: {$o.total|price} {$locale.uah}.*}
                        {*</td>*}
                    {*</tr>*}
                {*</table>*}
            {*</div>*}
            {*<div class="order-content">*}

            {*</div>*}
        {*</div>*}
    {*{/foreach}*}
{*</div>*}
{*{/if}*}


{*<form id="support-post" action="{$lang_suffix}/customer/support">*}
    {*<div class="h1">{$locale.tech_support}</div>*}
    {*<label>*}
        {*{$locale.support_topic}:*}
        {*<input type="text" name="topic" value="" maxlength="255"/>*}
    {*</label>*}
    {*<label>*}
        {*{$locale.support_text}:*}
        {*<textarea name="text" id="" cols="30" rows="10" maxlength="2048"></textarea>*}
    {*</label>*}
    {*<button id="submit-feedback" class="btn-red-big" type="submit">{$locale.support_send}</button>*}
{*</form>*}

{*<script type="text/javascript">*}
    {*$(function(){*}
        {*$('#support-post').on('submit',function(e){*}
            {*e.preventDefault();*}
            {*var $form = $(this),*}
                    {*data = $form.serializeObject();*}
            {*$.post('/customer/support',data,function(r){*}
                {*$form.find('.validation-error').remove();*}
                {*if(r.result!='success'){*}
                    {*if(r.data){*}
                        {*$form*}
                                {*.find('[name="'+ r.data.field+'"]')*}
                                {*.before('<span class="validation-error">'+ r.data.message+'</span>');*}
                    {*} else {*}
                        {*SerenityShop.alert(r.message,'{$locale.error}');*}
                    {*}*}
                    {*return;*}
                {*}*}
                {*$form.find('input,textarea').val('');*}
                {*SerenityShop.alert('{$locale.support_send_success}','{$locale.thank_you}');*}
            {*});*}

        {*});*}
    {*});*}
{*</script>*}
<script type="text/javascript">
    var customerInfo = {$customer|json_encode};
    var customerDiscount  = {if $discount}{$discount|json_encode}{else} { } {/if};
</script>
<script type="text/html" id="tmpl_order_content">
        <div id="order_item">
            <table>
                <% _.each(o.items, function(p){ %>
                <tr>
                    <td class="image">
                        <a class="image" href="{$lang_suffix}/product/<%= p.product_id %>-<%= p.sef %>">
                            <% if(p.image){ %>
                            <img src="/media/product/<%= p.product_id %>_small.<%= p.image %>">
                            <% } else { %>
                            <img src="/template/images/placeholder_140.png">
                            <% } %>
                        </a>
                    </td>
                    <td class="title">
                        <a href="{$lang_suffix}/product/<%= p.product_id %>-<%= p.sef %>"><%- p.title %></a>
                    </td>
                    <td class="qty"><b><%= p.qty %> шт.</b></td>
                    <td class="price">
                        <b><%= SerenityShop.formatPrice(p.price) %> {$locale.uah}</b>
                    </td>
                </tr>
                <% }); %>
                <tr class="order_summary">
                    <td colspan="3">
                        <% if (o.totalDiscount){ %>
                        <div class="total">{$locale.discount}:<b><%= o.discount*100 %>% (-<%= o.totalDiscount %> {$locale.uah})</b></div>
                        <% } %>
                    </td>
                    <td>
                        <div class="total">{$locale.total}:<b><%= SerenityShop.formatPrice(o.total) %> {$locale.uah}</b></div>
                    </td>
                </tr>
            </table>
        </div>
</script>