{if $products && count($products)>0}.

    <section class="order-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="sect-title">
                        <h3>{$locale.go_to_cart}</h3>
                    </div>
                </div>
                <div class="col-lg-8 col-md-7">
                    <div class="order-client">
                        <div class="main-tabs">
                            <div class="main-tabs_content">
                                <div class="one-tab new-client">
                                    <div class="order-form">
                                        <form id="submitOrder">
                                            <div class="order-step">
                                                <div class="order-step_title">
                                                    <span>1</span>
                                                    <b>{$locale.authorization}</b>
                                                </div>
                                                <div class="order-form_wrapper clearfix">
                                                    <div class="order-form_left">
                                                        <fieldset class="input-item">
                                                            <label for="order-name_1">{$locale.pib} <sup>*</sup></label>
                                                            <input id="order-name_1" name="name" type="text" class="option" value="{$currentUser.name}">
                                                        </fieldset>
                                                    </div>
                                                    <div class="order-form_right">
                                                        <fieldset class="input-item">
                                                            <label for="order-email_1">E-mail <sup>*</sup></label>
                                                            <input id="order-email_1" class="option" name="mail" type="email" value="{$currentUser.mail}">
                                                        </fieldset>
                                                        <fieldset class="input-item">
                                                            <label for="customer_phone">{$locale.contact_phone} <sup>*</sup></label>
                                                            <input id="customer_phone" class="option" name="phone" type="tel" value="{$currentUser.phone}">
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-step">
                                                <div class="order-step_title">
                                                    <span>2</span>
                                                    <b>{$locale.info_delivery}</b>
                                                </div>
                                                <div class="order-form_wrapper clearfix">
                                                    <div class="order-form_left">
                                                        <fieldset class="input-item">
                                                            <label for="select_order">{$locale.delivery} <sup>*</sup></label>
                                                            <select id="select_order" name="shipping_method" class="option" data-placeholder="{$locale.select_shipment_method}" >
                                                                <option></option>
                                                                <option value="pickup">{$locale.shipment_pickup}</option>
                                                                <option value="courier">{$locale.shipment_courier}</option>
                                                                <option value="new_post">{$locale.shipment_new_post}</option>
                                                                <option value="post">{$locale.shipment_ukrpost}</option>
                                                            </select>
                                                        </fieldset>

                                                        <fieldset id="pickup" class="input-item hidden input-wrap">
                                                            <label for="order-department_1">{$locale.shop} <sup>*</sup></label>
                                                            <select id="order-department_1" class="address" data-placeholder="{$locale.select_office}" name="office" >
                                                                <option></option>
                                                                {foreach from=$addressList item="a" key="ai"}
                                                                    <option{if $cart.shipment && $cart.shipment.office_id==$ai} selected="selected" {/if} value="{$ai}">{$a.city}, {$a.address}</option>
                                                                {/foreach}
                                                            </select>
                                                        </fieldset>

                                                        <fieldset id="courier" class="input-item hidden input-wrap">
                                                            <label for="order-message_1">{$locale.add_comment} <sup>*</sup></label>
                                                            <textarea id="order-message_1" class="address" name="courier_address" placeholder="{$locale.address_courier}">{$cart.shipment.courier_address}</textarea>
                                                        </fieldset>

                                                        <div id="new_post" class="input-item hidden input-wrap">
                                                            <fieldset class="input-item">
                                                                <label for="order-city_1">{$locale.city} <sup>*</sup></label>
                                                                <input id="order-city_1" name="city" class="address" type="text" value="{$cart.shipment.city}" >
                                                            </fieldset>
                                                            <fieldset class="input-item">
                                                                <label for="order-city_1">{$locale.warehouse} <sup>*</sup></label>
                                                                <input id="order-city_1" name="warehouse" class="address" type="text" value="{$cart.shipment.warehouse}" >
                                                            </fieldset>
                                                        </div>

                                                        <fieldset id="post" class="input-item hidden input-wrap">
                                                            <label for="order-message_1">{$locale.add_comment} <sup>*</sup></label>
                                                            <textarea id="order-message_1" name="address" class="address" placeholder="{$locale.address_post}">{$cart.shipment.address}</textarea>
                                                        </fieldset>

                                                    </div>
                                                    <div class="order-form_right">
                                                        <fieldset class="input-item">
                                                            <label for="order-payment_1">{$locale.select_payment_method} <sup>*</sup></label>
                                                            <select id="order-payment_1" name="payment_method" class="option" data-placeholder="{$locale.select_payment_method}" >
                                                                <option></option>
                                                                <option value="liqpay">{$locale.payment_card}</option>
                                                                <option value="cash">{$locale.payment_cash}</option>
                                                            </select>
                                                        </fieldset>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box-r">
                                                <button type="submit" id="set-payment-trigger" class="btn btn-full">
                                                    <span>{$locale.confirm_order}</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5">
                    <div class="order-product">
                        <div class="order-product_title clearfix">
                            <b>{$locale.your_order}</b>
                            <a href="#open-basket" class="btn-simple inner open-basket">{$locale.edit}</a>
                        </div>
                        {if $smarty.session.cart.items && $cartSummary.count > 0}

                        {foreach from=$smarty.session.cart.items item="p" key='k'}

                            <div class="order-product_item clearfix">
                            <div class="order-product_img">
                                <div class="cell">
                                    <a href="{$lang_suffix}/product/{if $p.parent_id != 0}{$p.parent_id}{else}{$k}{/if}">
                                        {if $p.image}
                                            <img src="/media/product/{if $p.parent_id != 0}{$p.parent_id}{else}{$k}{/if}_small.{$p.image}" alt="{$p.title}">
                                        {else}
                                            <img src="/template/images/placeholder_140.png" alt="{$p.title}">
                                        {/if}
                                    </a>
                                </div>
                            </div>
                            <div class="order-product_info">
                                <div class="order-product_name">
                                    <a href="{$lang_suffix}/product/{if $p.parent_id != 0}{$p.parent_id}{else}{$k}{/if}-{$p.sef}">{if $p.parent_id != 0}{$p.parent_title}{else}{$p.title}{/if}</a>
                                </div>
                                    <div class="order-product_descr">
                                        {if $p.parent_id != 0}
                                            <p>{$p.title}</p>
                                        {/if}
                                        {if $p.color}
                                            <span class="color" style="background-color: #{$p.color}"></span>
                                        {/if}
                                    </div>
                                <div class="order-product_amount">{$locale.quantity}: <span>{$p.qty}</span></div>
                            </div>
                            <div class="order-product_price">
                                {if $p.sale_price > 0}
                                    <span class="old-price">{$p.price} {$locale.uah}</span>
                                    <b>{$p.sale_price} {$locale.uah}</b>
                                {else}
                                    <b>{$p.price} <span>{$locale.uah}</span></b>
                                {/if}
                            </div>
                        </div>

                        {/foreach}
                        {/if}

                        <div class="order-product_footer">
                            {if $cart.totalDiscount}
                            <div class="order-product_sales clearfix">
                                <p>{$locale.discount_club} <b>({$cart.discount*100|price}%)</b></p>
                                <span>-<b>{$cart.totalDiscount|price}</b> {$locale.uah}</span>
                            </div>
                            {/if}
                            <div class="order-product_total clearfix">
                                <p>{$locale.total}:</p>
                                <span><b>{$cart.totalPrice|price}</b> {$locale.uah}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {*<div id="cart-container">*}
    {*<div id="order-steps">*}
        {*<div class="tabs">*}
            {*<a href="#" data-tab="tab-payment" class="tab-payment{if $cart.shipment} active enabled{/if}">{$locale.payment}<i class="icon icon-order-tab-inact"></i><i class="icon icon-order-tab-act"></i></a>*}
            {*<a href="#" data-tab="tab-shipping" class="tab-shipping{if ($currentUser || $cart.contacts) && !$cart.shipment} active{/if}{if $currentUser || $cart.contacts} enabled{/if}">{$locale.delivery}<i class="icon icon-order-tab-inact"></i><i class="icon icon-order-tab-act"></i></a>*}
            {*<a href="#" data-tab="tab-info" class="tab-info{if !$currentUser && !$cart.contacts} active{/if} enabled">{$locale.info}<i class="icon icon-order-tab-inact"></i><i class="icon icon-order-tab-act"></i></a>*}
        {*</div>*}
        {*<div class="tab-content">*}
            {* INFO *}
            {*<div class="tab tab-info{if !$currentUser && !$cart.contacts} active{/if}">*}
                {*<div class="tip">{$locale.enter_contacts}:{if $currentUser}<br><span style="font-size: 12px">({$locale.you_can_change_in_profile})</span>{/if}</div>*}
                {*<div class="option-group">*}
                    {*<input name="name" type="text" placeholder="{$locale.your_name}"{if $currentUser} disabled="disabled" value="{$currentUser.name}" title="{$locale.you_can_change_in_profile1}"{else}value="{$cart.contacts.name}"{/if}/>*}
                    {*<input name="mail" type="text" placeholder="E-mail"{if $currentUser} disabled="disabled" value="{$currentUser.mail}" title="{$locale.you_can_change_in_profile1}"{else}value="{$cart.contacts.mail}"{/if}/>*}
                    {*<input id="customer_phone" name="phone" type="tel" placeholder="{$locale.contact_phone}"{if $currentUser} disabled="disabled"  value="{$currentUser.phone}" title="{$locale.you_can_change_in_profile1}"{else}value="{$cart.contacts.phone}"{/if}/>*}
                {*</div>*}
                {*<button id="set-contacts-trigger" class="btn-red-big"><i class="icon icon-play"></i> {$locale.shipment_methods}</button>*}
            {*</div>*}
            {* SHIPPING *}
            {*<div class="tab tab-shipping {if !$cart.shipment && $cart.contacts} active{/if}">*}
                {*<div class="tip">{$locale.select_shipment_method}:</div>*}
                {*<div class="option-group">*}
                    {*<label class="main-switch">*}
                        {*<input type="radio" name="shipping_method" id="" value="pickup" {if !$cart.shipment || $cart.shipment.method=='pickup'} checked="checked" {/if} />*}
                        {*{$locale.shipment_pickup}*}
                    {*</label>*}
                    {*<div class="controls">*}
                        {*<select class="control" name="office" id="">*}
                            {*{foreach from=$addressList item="a" key="ai"}*}
                                {*<option{if $cart.shipment && $cart.shipment.office_id==$ai} selected="selected" {/if} value="{$ai}">{$a.city}, {$a.address}</option>*}
                            {*{/foreach}*}
                        {*</select>*}
                    {*</div>*}
                {*</div>*}
                {*<div class="option-group">*}
                    {*<label class="main-switch">*}
                        {*<input type="radio" name="shipping_method" id="" value="courier" {if $cart.shipment.method=='courier'} checked="checked" {/if} />*}
                        {*{$locale.shipment_courier}*}
                    {*</label>*}
                    {*<div class="controls">*}
                        {*<textarea class="control" name="courier_address" id="" cols="30" rows="10" style="height: 45px;" placeholder="{$locale.address_courier}">{$cart.shipment.courier_address}</textarea>*}
                    {*</div>*}
                {*</div>*}
                {*<div class="option-group">*}
                    {*<label class="main-switch">*}
                        {*<input type="radio" name="shipping_method" id="" value="new_post"{if $cart.shipment && $cart.shipment.method=='new_post'} checked="checked" {/if}/>*}
                        {*{$locale.shipment_new_post}*}
                    {*</label>*}
                    {*<div class="controls">*}
                        {*<input class="control" name="city" type="text" placeholder="{$locale.city}" value="{$cart.shipment.city}" />*}
                        {*<input class="control" name="warehouse" type="text" placeholder="{$locale.warehouse}" value="{$cart.shipment.warehouse}"/>*}
                    {*</div>*}
                {*</div>*}
                {*<div class="option-group">*}
                    {*<label class="main-switch">*}
                        {*<input type="radio" name="shipping_method" id="" value="post" {if $cart.shipment && $cart.shipment.method=='post'} checked="checked" {/if}/>*}
                        {*{$locale.shipment_ukrpost}*}
                    {*</label>*}
                    {*<div class="controls">*}
                    {*<textarea class="control" name="address" id="" cols="30" rows="10" placeholder="{$locale.address_post}">{$cart.shipment.address}</textarea>*}
                    {*</div>*}
                {*</div>*}
                {*<button id="set-shipping-trigger" class="btn-red-big"><i class="icon icon-play"></i> {$locale.payment_methods}</button>*}
            {*</div>*}
            {* PAYMENT *}
            {*<div class="tab tab-payment{if $cart.shipment} active{/if}">*}
                {*<div class="tip">{$locale.select_payment_method}:</div>*}
                {*<!--<div class="option-group">*}
                    {*<label class="main-switch">*}
                        {*<input type="radio" name="payment_method" id="" value="bank" checked />*}
                        {*{$locale.payment_bank}*}
                    {*</label>*}
                    {*<div class="descr">({$locale.payment_bank_info})</div>*}
                {*</div>-->*}
                {*<!--<div class="option-group">*}
                    {*<label class="main-switch">*}
                        {*<input type="radio" name="payment_method" id="" value="privat24" />*}
                        {*{$locale.payment_card}*}
                    {*</label>*}
                    {*<div class="descr">({$locale.payment_card_info})</div>*}
                {*</div>-->*}
                {*<div class="option-group">*}
                    {*<label class="main-switch">*}
                        {*<input type="radio" name="payment_method" id="" value="liqpay" />*}
                        {*{$locale.payment_card}*}
                    {*</label>*}
                {*</div>*}
                {*<div class="option-group">*}
                    {*<label class="main-switch">*}
                        {*<input type="radio" name="payment_method" id="" value="cash" />*}
                        {*{$locale.payment_cash}*}
                    {*</label>*}
                    {*<div class="descr">({$locale.payment_cash_info})</div>*}
                {*</div>*}
                {*<button id="set-payment-trigger" class="btn-red-big">{$locale.confirm_order}</button>*}
            {*</div>*}
        {*</div>*}
    {*</div>*}
    {*<div id="cart-items">*}
        {*<table>*}
            {*{foreach from=$products item="p"}*}
            {*<tr>*}
                {*<td class="image">*}
                    {*<a class="image" href="{$lang_suffix}/product/{if $p.parent_id}{$p.parent_id}{else}{$p.id}{/if}-{$p.sef}">*}
                        {*{if $p.image}*}
                            {*<img src="/media/product/{if $p.parent_id}{$p.parent_id}{else}{$p.id}{/if}_small.{$p.image}" alt="{$p.title}">*}
                        {*{else}*}
                            {*<img src="/template/images/placeholder_140.png" alt="{$p.title}">*}
                        {*{/if}*}
                    {*</a>*}
                {*</td>*}
                {*<td class="title">*}
                    {*<a href="{$lang_suffix}/product/{if $p.parent_id}{$p.parent_id}{else}{$p.id}{/if}-{$p.sef}">{$p.title}</a>*}
                {*</td>*}
                {*<td class="qty"><span class="product-qty"><input type="number" name="qty" min="1" data-id="{$p.id}" value="{$cart.items[$p.id]}"/> шт.</span></td>*}
                {*<td class="price">*}
                    {*<span class="regular{if $p.sale_price} strike{/if}">{$p.price|price} {$locale.uah}</span>*}
                    {*{if $p.sale_price}*}
                        {*<br><span class="sale">{$p.sale_price|price} {$locale.uah}</span>*}
                    {*{/if}*}
                {*</td>*}
                {*<td class="delete">*}
                    {*<a href="#" data-id="{$p.id}"><i class="icon icon-remove"></i></a>*}
                {*</td>*}
            {*</tr>*}
            {*{/foreach}*}
        {*</table>*}
    {*</div>*}
    {*<div class="summary">*}
        {*{if $cart.totalDiscount}*}
        {*<div class="total-discount">{$local.discount}:<span>{$cart.discount*100|price}% (-{$cart.totalDiscount|price} {$locale.uah})</span></div>*}
        {*{/if}*}
        {*<div class="total">{$locale.total}:<span>{$cart.totalPrice|price} {$locale.uah}</span></div>*}
    {*</div>*}
{*</div>*}
{else}
    <div class="container">
        <div class="cms-content box-c">
            <h1>{$locale.cart_is_empty}</h1>
            <h3>{$locale.cart_is_empty_detailed}</h3>
        </div>
    </div>

{/if}