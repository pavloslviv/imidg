/**
 * Created by Sergiy on 31.12.13.
 */
(function () {
    var app = {
        data: {},
        events: {
            'change@.basket-item_amount input': 'onQtyChange',
            'click@.basket-item_del': 'onItemDelete',
            'click@#order-steps .tabs a': 'onTabClick',
            'click@#set-contacts-trigger': 'onSetContacts',
            'click@#set-shipping-trigger': 'onSetShipping',
            // 'click@#set-payment-trigger': 'onSetPayment',
            'submit@#submitOrder': 'submitOrder',
            'change@#select_order': 'selectOrder'
        },
        init: function(){
            app.$el = $('body');
            _.each(app.events,function(handler,selector){
                selector= selector.split('@');
                if(!_.isFunction(app[handler])) return;
                app.$el.on(selector[0],selector[1],function(){
                    app[handler].apply(app,arguments);
                });
            });
            // $('#customer_phone').mask("(999) 999-99-99");
        },

        onQtyChange: function(e){
            var $input = $(e.currentTarget),
                productId = $input.data('id'),
                qty = !_.isNaN(parseInt($input.val())) ? parseInt($input.val()) : 1;
            $.post(SerenityShop.urlSuffix+'/cart/update',{id: productId, qty: qty, mode: 'replace'},function(r){
                if(r.result!='success'){
                    return;
                }

                $input.val(qty);
                $('.header-basket_icon span').html(r.data.count);
                $('.basket-popup_total span b').html(SerenityShop.formatPrice(r.data.price));
                $('.header-basket_price b').html(SerenityShop.formatPrice(r.data.price));
            });
        },

        onItemDelete: function(e){
            e.preventDefault();
            var $link = $(e.currentTarget),
                productId = $link.data('id'),
                $row = $link.closest('.basket-item');
            $row.hide();

            $.post(SerenityShop.urlSuffix+'/cart/update',{id: productId, qty: 0, mode: 'delete'},function(r){

                if(r.result!='success'){
                    SerenityShop.alert(r.data ? r.data.message : r.message,SerenityShop.lang.error);
                    $row.show();
                    return;
                }
                $row.remove();
                if($('.basket-item').length==0){
                    window.location.reload();
                }
                $('.header-basket_icon span').html(r.data.count);
                $('.basket-popup_total span b').html(SerenityShop.formatPrice(r.data.price));
                $('.header-basket_price b').html(SerenityShop.formatPrice(r.data.price));
            });
        },

        onSetContacts: function(e){
            e.preventDefault();
            var data = {},
                $inputs = $('.tab-info .option-group input');
            $inputs.each(function(){
                var $input = $(this);
                data[$input.attr('name')]=$input.val();
            });
            $('.validation-error').remove();
            $.post(SerenityShop.urlSuffix+'/cart/contacts',data,function(r){
                if(r.result!='success'){
                    if(r.data){
                        $inputs.filter('[name="'+ r.data.field+'"]').before('<span class="validation-error">'+r.data.message+'</span>');
                    } else {
                        SerenityShop.alert(r.message,SerenityShop.lang.error);
                    }
                    return;
                }
                $('.tabs .tab-shipping').addClass('enabled');
                app.switchToTab('tab-shipping');
            });
        },

        onSetShipping: function(e){
            e.preventDefault();
            var data = {
                    method:'',
                    data: {}
                },
                $tab = $('.tab-shipping'),
                $method = $tab.find('input[name="shipping_method"]:checked'),
                $inputs = $method.closest('.option-group').find('.control');
            data.method = $method.val();
            $inputs.each(function(){
                var $input = $(this);
                data.data[$input.attr('name')]=$input.val();
            });
            $('.validation-error').remove();
            $.post(SerenityShop.urlSuffix+'/cart/shipping',data,function(r){
                if(r.result!='success'){
                    if(r.data){
                        $inputs.filter('[name="'+ r.data.field+'"]').before('<span class="validation-error">'+r.data.message+'</span>');
                    } else {
                        SerenityShop.alert(r.message,SerenityShop.lang.error);
                    }
                    return;
                }
                $('.tabs .tab-payment').addClass('enabled');
                app.switchToTab('tab-payment');
            });
        },

        onSetPayment: function(e){
            e.preventDefault();
            var data = {
                    method:'',
                    data: {}
                },
                $tab = $('.tab-payment'),
                $method = $tab.find('input[name="payment_method"]:checked');
            data.method = $method.val();
            $('.validation-error').remove();
            $.post(SerenityShop.urlSuffix+'/cart/payment',data,function(r){
                if(r.result!='success'){
                    if(r.data){
                        $method.before('<span class="validation-error">'+r.data.message+'</span>');
                    } else {
                        SerenityShop.alert(r.message,SerenityShop.lang.error);
                    }
                    return;
                }
                window.location.href = '/cart/confirm';
            });
        },

        selectOrder: function (e) {
            e.preventDefault();
            $('.order-form_left .input-wrap').addClass('hidden');

            var select_order = $('#select_order').val();
            $('#'+select_order).removeClass('hidden');
        },

        submitOrder: function(e) {
            e.preventDefault();
            var data = {
                data_ship:{}
            },
                $allinput = $('#submitOrder select, #submitOrder input, #submitOrder textarea');
                $inputs = $('#submitOrder select.option, #submitOrder input.option');
                $inputs_ship = $('#submitOrder .input-wrap:not(.hidden) input, #submitOrder .input-wrap:not(.hidden) select, #submitOrder .input-wrap:not(.hidden) textarea');
            $inputs.each(function(){
                var $input = $(this);
                data[$input.attr('name')]=$input.val();
            });

            $inputs_ship.each(function () {
                var $input = $(this);
                data.data_ship[$input.attr('name')]=$input.val();
            });

            console.log(data);

            $('.helper').remove();
            $('.input-item').removeClass('error');

            $.post(SerenityShop.urlSuffix+'/cart/submitOrder',data,function(r) {
                console.log(r);

                if(r.result!='success'){
                    for (var i = 0; i < r.errors.length; i++) {
                        $allinput.filter('[name="'+ r.errors[i].field+'"]').closest('.input-item')
                            .append('<span class="helper">'+r.errors[i].message+'</span>')
                            .addClass('error');
                    }
                    return;
                }
                window.location.href = '/cart/confirm';

            });
        },

        onTabClick: function(e){
            e.preventDefault();
            var $link = $(e.currentTarget);
            if($link.hasClass('enabled')){
                this.switchToTab($link.data('tab'));
            }

        },

        switchToTab: function(tabClass){
            this.$el
                .find('.tabs a').removeClass('active')
                .filter('.'+tabClass).addClass('active');
            this.$el
                .find('.tab').removeClass('active')
                .filter('.'+tabClass).addClass('active');
        }

    }
    window.ShopCart = app;
    $(app.init);
})();