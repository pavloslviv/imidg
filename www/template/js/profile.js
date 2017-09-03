/**
 * Created by Sergiy on 31.12.13.
 */
(function () {
    if(window.customerInfo) {

        var app = {
            data: {
                customer :{}
            },
            events: {
                'click@.input-item button.toggle-editing': 'onToggleEdit',
                'change@#profile-subscribe input': 'onSubscriptionToggle',
                'click@#editDiscount': 'onToggleDiscountEdit',
                'click@#editPassword': 'onTogglePasswordEdit',
                'change@.order-accordion li input': 'onToggleOrder'
            },
            init: function () {
                app.$el = $('.cabinet');
                _.each(app.events, function (handler, selector) {
                    selector = selector.split('@');
                    if (!_.isFunction(app[handler])) return;
                    app.$el.on(selector[0], selector[1], function () {
                        app[handler].apply(app, arguments);
                    });
                });
                app.template_order = _.template($('#tmpl_order_content').html());
            },

            onToggleEdit: function (e) {
                var $button = $(e.currentTarget),
                    $field = $button.closest('.input-item:not(#discount-container)'),
                    type = $button.data('type'),
                    name = $button.data('name'),
                    data, value, $input;

                if ($field.hasClass('editing')) {
                    $input = $field.find('input');
                    value = $input.val();
                    data = {id: app.data.customer.id};
                    data[name] = value;
                    $.post(SerenityShop.urlSuffix + '/customer/update', data, function (r) {

                        $field.find('.helper').remove();
                        if (r.result != 'success') {
                            $field
                                .addClass('error');
                            $input.after('<span class="helper">' + (r.data ? r.data.message : r.message) + '</span>');
                            return;
                        }
                        _.extend(app.data.customer, r.data);
                        $field.find('.input-container').html(r.data[name]);
                        $button
                            .addClass('icon-edit')
                            .removeClass('icon-checked-1');
                        $field
                            .removeClass('editing')
                            .removeClass('error')
                            .find('.helper').remove();
                        $input.prop("disabled", true);
                        if (name == 'name') {
                            $('#cart-summary .customer-name').html(r.data.name + '!');
                        }
                    });
                } else {
                    // value = this.data.customer[name] || '';
                    $input = $field.find('input');
                    // $field.find('.input-container').html($input);
                    // if (type == 'tel') {
                    //     $input.mask("(999) 999-99-99");
                    // }
                    $field.addClass('editing');
                    $input
                        .prop("disabled", false)
                        .trigger('focus');
                    $button
                        .removeClass('icon-edit')
                        .addClass('icon-checked-1');

                }
                e.preventDefault();

            },

            onSubscriptionToggle: function (e) {
                var $input = $(e.currentTarget),
                    value = $input.is(':checked') ? 1 : 0;
                $.post(SerenityShop.urlSuffix + '/customer/update', {
                    id: app.data.customer.id,
                    subscribe: value
                }, function (r) {
                    $input.parent().find('.validation-error').remove();
                    if (r.result != 'success') {
                        $input.before('<span class="validation-error">' + (r.data ? r.data.message : r.message) + '</span>');
                        return;
                    }
                });
            },

            onToggleDiscountEdit: function (e) {
                var $button = $(e.currentTarget),
                    $field = $('#discount-container'),
                    name = 'discount',
                    data, value, $input;

                if ($field.hasClass('editing')) {
                    $input = $field.find('input');
                    value = $input.val();
                    data = {code: value};
                    $.post(SerenityShop.urlSuffix + '/customer/change_discount', data, function (r) {
                        if (r.data && r.data.hasOwnProperty('counter')) {
                            $('#discount-change-counter').text(r.data.counter);
                        }
                        $field
                            .find('.helper').remove()
                            .removeClass('.error');

                        if (r.result != 'success') {
                            $field.addClass('error');
                            $input.after('<span class="helper">' + (r.data ? r.data.message : r.message) + '</span>');
                            if (r.data && r.data.counter === 0) {
                                _.delay(function () {
                                    window.location.reload();
                                }, 5000)
                            }
                            return;
                        }
                        if (r.data.counter === 0) {
                            window.location.reload();
                            return;
                        }
                        if (!app.data.discount) {
                            app.data.discount = {};
                        }
                        _.extend(app.data.discount, r.data);
                        $field.find('.input-container').html(r.data.code + ' (' + SerenityShop.formatPrice(r.data.discount) + '%)');
                        $button
                            .removeClass('icon-checked-1')
                            .addClass('icon-edit');

                        $input
                            .prop("disabled", true);

                        $field.removeClass('editing');
                    });
                } else {
                    value = this.data.discount && this.data.discount.code ? this.data.discount.code : '';

                    $input = $field.find('input');

                    $field.addClass('editing');

                    $input
                        .prop("disabled", false)
                        .val(value)
                        .trigger('focus');
                    $button
                        .removeClass('icon-edit')
                        .addClass('icon-checked-1');

                }
            },

            onTogglePasswordEdit: function (e) {
                var $button = $(e.currentTarget),
                    $field = $('#password-container'),
                    name = 'discount',
                    data = {}, value, $input;

                if ($field.hasClass('editing')) {
                    $input = $field.find('.input-item input');
                    $input.each(function () {
                        var $this = $(this);
                        data[$this.attr('name')] = $this.val();
                    });
                    console.log(data);
                    $.post(SerenityShop.urlSuffix + '/customer/change_password', data, function (r) {
                        console.log(r);
                        $field.find('.helper').remove();
                        $field.find('.input-item').removeClass('error');

                        if (r.result != 'success') {
                            $input.filter('[name="' + r.data.field + '"]')
                                .after('<span class="helper">' + r.data.message + '</span>')
                                .parent('.input-item').addClass('error');
                            return;
                        }
                        $button.html('<span>'+ SerenityShop.lang.edit +'</span>');
                        $field.removeClass('editing');
                        $.fancybox({
                            openEffect	: 'fade',
                            closeEffect	: 'fade',
                            padding: 0,
                            tpl: {
                                closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                            },
                            content: '<div class="simple-modal" style="display: block;">' + SerenityShop.lang.pass_change_success + '</div>'
                        });
                    });
                } else {
                    $input = $field.find(".input-item input");
                    $field
                        .addClass('editing')
                        .find('.input-item').removeClass('error');
                    $input.each(function () {
                        $(this)
                            .prop("disabled", false)
                            .val('');
                    });

                    $button.html('<span>'+ SerenityShop.lang.save +'</span>');

                    $field.find('input#user-pass_old').trigger('focus');


                    // $input = $('<span class="field-wrapper"><input type="password" name="old-password" placeholder="' + SerenityShop.lang.old_pass + '"></span>');
                    // $field.find('.input-container')
                    //     .html($input)
                    //     .append(
                    //         '<span class="field-wrapper"><input type="password" name="pass" placeholder="' + SerenityShop.lang.new_pass + '"></span>' +
                    //         '<span class="field-wrapper"><input type="password" name="pass-confirm" placeholder="' + SerenityShop.lang.pass_confirm + '"></span>'
                    //     );
                    // $field.addClass('editing');
                    // _.delay(function () {
                    //     $field.find('input').on('change', function () {
                    //         $field.find('.validation-error').remove();
                    //     });
                    //     $input.trigger('focus');
                    // }, 10);
                }
            },

            onToggleOrder: function (e) {
                e.preventDefault();
                var $link = $(e.currentTarget),
                    orderId = $link.data('order'),
                    $container = $('#order_' + orderId);

                $.get(SerenityShop.urlSuffix + '/customer/get_order', {id: orderId}, function (r) {

                    if (r.result != 'success') {

                        $.fancybox({
                            openEffect	: 'fade',
                            closeEffect	: 'fade',
                            padding: 0,
                            tpl: {
                                closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                            },
                            content: '<div class="simple-modal" style="display: block;">' + SerenityShop.lang.error + '</div>'
                        });

                    }

                    $container.find('.order_list').html(app.template_order({
                        o: r.order
                    }));
                });
            }

        };
        app.data.customer = window.customerInfo;

        $(app.init);
    }
})();