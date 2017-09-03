/**
 * Created by Sergiy on 12.12.13.
 */
$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
(function () {
    var app = {
        data: {},
        lang: {},

        initBrandAlphabet: function(){
            var groups = {},
                $container = $('#brand-alphabet .letter-sorting'),
                $source = $('#filter_40, #filter_41, #filter_42,#filter_43,#filter_51,#filter_55'),
                $allBrands = $source.find('li'),
                $currentGroup;
            if(!$source.length) {
                $('#brand-alphabet').remove();
            }
            $allBrands.each(function(){
                var $link = $(this).find('a.checkbox'),
                    letter = $link.text().charAt(0);
                if(!groups[letter]){
                    groups[letter] = $link;
                } else {
                    groups[letter] = groups[letter].add($link);
                }
            });
            $.each(groups,function(letter){
                $container.append('<a href="#" data-letter="'+letter+'">'+letter+'</a>');
            });
            $container.on('click','a',function(e){
                e.preventDefault();
                var $link = $(this),
                    $group = groups[$link.data('letter')],
                    isActive = $link.hasClass('checked');
                console.log($group);
                if(!$group) return;
                if(!$container.find('.checked').length){
                    $allBrands.find('a').hide();
                    // $('#more-brands-link').remove();
                }
                $group.toggle(!isActive);
                $link.toggleClass('checked',!isActive);
                if(!$container.find('.checked').length){
                    $allBrands.find('a').show();
                    // app.initBrandsMore();
                }

                console.log($allBrands);
            })
        },

        initBrandsMore: function(){
            var $container = $('#filter_40, #filter_41, #filter_42,#filter_43,#filter_51').find('ul'),
                $brands = $container.find('li'),
                limit= 15,
                activeCount,
                $hiddenBrands,
                $moreBrandsLink;
            //$('#more-brands-link').remove();
            if($brands.length<=limit) return;
            activeCount= $brands.filter('.active').length;
            if(activeCount>=limit){
                $hiddenBrands = $brands.not('.active');
            } else {
                $hiddenBrands = $brands.not('.active').slice(limit-activeCount);
            }
            if($hiddenBrands.length){
                $hiddenBrands.hide();
                $moreBrandsLink = $('<a id="more-brands-link" href="#">'+SerenityShop.lang.more_brands+'</a>');
                $container.append($moreBrandsLink);
                $moreBrandsLink.on('click',function(e){
                    e.preventDefault();
                    $hiddenBrands.show();
                    $moreBrandsLink.remove();
                });
            }

        },

        onPriceRangeChange: function(e){
            $('#apply-price-filter').addClass('enabled');
        },

        onPriceFromChange: function(e){
            var $inputFrom = $(e.currentTarget),
                $inputTo = $('#current-price input.to'),
                price = $inputFrom.val(),
                priceMin = parseInt($inputFrom.attr('min')),
                priceMax = parseInt($inputTo.val());
            if(price<priceMin){
                $inputFrom.val(priceMin);
                price = priceMin;
            } else if(price>priceMax){
                $inputFrom.val(priceMax);
                price = priceMax;
            }
            $('#price-range').val([price,null]);
            $('#apply-price-filter').addClass('enabled');
        },
        onPriceToChange: function(e){
            var $inputTo = $(e.currentTarget),
                $inputFrom = $('#current-price input.from'),
                price = $inputTo.val(),
                priceMin = parseInt($inputFrom.val()),
                priceMax = parseInt($inputTo.attr('max'));
            if(price>priceMax){
                $inputFrom.val(priceMax);
                price = priceMax;
            } else if(price<priceMin){
                $inputTo.val(priceMin);
                price = priceMin;
            }
            $('#price-range').val([null,price]);
            $('#apply-price-filter').addClass('enabled');
        },

        onPriceRangeApply: function(e){
            if(e) e.preventDefault();
            var $input = $('#price-range'),
                range = $input.val(),
                start = Math.round($('#minCost').val()),
                end = Math.round($('#maxCost').val()),
                currentLocation;
            currentLocation = window.location.href;
            currentLocation = currentLocation.replace(/\&?price_from=?[\d\.]*/gi,'');
            currentLocation = currentLocation.replace(/\&?price_to=?[\d\.]*/gi,'');
            currentLocation = currentLocation+(currentLocation.indexOf('?')===-1 ? '?' : '&')+'price_from='+start+'&price_to='+end;
            window.location.href = currentLocation;
        },

        onPriceRangeSlide: function(){
            var $input = $(this),
                range = $input.val(),
                $priceCont = $('#current-price');
            $priceCont.find('.from').val(Math.round(range[0]));
            $priceCont.find('.to').val(Math.round(range[1]));
        },

        activateSlider: function(){
            var $container = $('#slider'),
                $strip = $container.find('.slide-strip'),
                $slides = $strip.find('a.slide'),
                $pointersContainer = $container.find('.pointers'),
                baseW = 360, baseH = 395,
                step,currentPosition = 0,
                stepCount = Math.ceil($slides.length/3)- 1,
                updateSize = function(){
                    var w = ($container.width())/3,
                        h = w*baseH/baseW;
                    $slides.css({
                        'width': w+'px',
                        'height': h+'px'
                    });
                    step = w*3+2;
                };
                $(window).on('resize', _.debounce(updateSize,100));
                updateSize();
            for(var i=0; i<=stepCount; i++){
                $pointersContainer.append('<a class="pointer '+(!i ? 'active' : '')+'" href="#" data-index="'+i+'"></a>');
            }
            $container
                .on('click','a.next',function(e){
                    e.preventDefault();
                    if(currentPosition>-100*stepCount){
                        currentPosition = currentPosition-100;
                    } else {
                        currentPosition = 0;
                    }
                    $strip.css('left',currentPosition+'%');
                    activatePointer(currentPosition/-100);
                })
                .on('click','a.prev',function(e){
                    e.preventDefault();
                    if(currentPosition<0){
                        currentPosition = currentPosition+100;
                    } else {
                        currentPosition = -100*stepCount;
                    }
                    $strip.css('left',currentPosition+'%');
                    activatePointer(currentPosition/-100);
                })
                .on('click','a.pointer',function(e){
                    var $pointer = $(this),
                        index = $pointer.data('index');
                    e.preventDefault();
                    currentPosition = -100*index;
                    $strip.css('left',currentPosition+'%');
                    activatePointer(index);
                });
            function activatePointer(index){
                $pointersContainer
                    .find('a.pointer').removeClass('active')
                    .eq(index).addClass('active');
            }
        },

        onSignupClick: function(e){
            e.preventDefault();
            var formTemplate = _.template($('#tmpl-signup-form').html()),
                $popup;
            $popup = $(formTemplate());
            $popup.find("#signup-phone").mask('38(099)999-99-99', {
                placeholder: '38(000)000-00-00',
            });
            $('body').addClass('lock-scroll').append($popup);
            $popup
                .find('.popup-close')
                .on('click',function(e){
                    e.preventDefault();
                    $popup.remove();
                    $('body').removeClass('lock-scroll');
                }).end()
                .find('form').on('submit',app.onSignupSubmit)
                .find('input[name="have-discount"]').on('change',function(e){
                    $('#signup-discount').toggle($(this).val()=='1');
                });
        },

        onCallMeClick: function(e){
            e.preventDefault();
            var formTemplate = _.template($('#tmpl-call-me-form').html()),
                $popup;
            $popup = $(formTemplate());
            $popup.find("#call-me-phone").mask('38(099)999-99-99', {
                placeholder: '38(000)000-00-00',
            });
            $('body').addClass('lock-scroll').append($popup);
            $popup
                .find('.popup-close')
                .on('click',function(e){
                    e.preventDefault();
                    $popup.remove();
                    $('body').removeClass('lock-scroll');
                }).end()
                .find('form').on('submit',app.onCallMeSubmit);
        },

        onSignupSubmit: function(e){
            e.preventDefault();
            var $form = $(e.currentTarget),
                data = $form.serializeObject();
            $.post(SerenityShop.urlSuffix+'/customer/signup',data,function(r){
                $form.find('.helper').remove();
                $form.find('.error').removeClass('error');

                if(r.result=='error'){
                    if(r.data){
                        $form
                            .find('input[name="'+ r.data.field+'"]')
                            .after('<span class="helper">'+ r.data.message+'</span>')
                            .parents('.input-item').addClass('error')
                    } else {
                        $.fancybox({
                            openEffect	: 'fade',
                            closeEffect	: 'fade',
                            padding: 0,
                            tpl: {
                                closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                            },
                            content: '<div class="simple-modal" style="display: block;">' + r.message + '</div>'
                        });
                    }
                    return;
                }
                window.location.reload();
                return;
                /*$('#cart-summary .customer-name').text(r.data.name+'!')
                $('#customer-mod').removeClass('show-login').addClass('logged-in');
                $('body').removeClass('lock-scroll');
                $form.closest('.popup-overlay').remove();*/
            });
        },

        onRecoverPassword: function (e) {
            e.preventDefault();
            var $form = $(e.currentTarget),
                data = $form.serializeObject();
            $.post('/customer/recover_pass',data,function(r){
                $form.find('.helper').remove();
                $form.find('.error').removeClass('error');
                if(r.result!='success'){
                    if(r.data.field){
                        $form
                            .find('input[name="'+ r.data.field+'"]')
                            .after('<span class="helper">'+ r.data.message+'</span>')
                            .parents('.input-item').addClass('error')
                    } else {
                        SerenityShop.alert(r.data.message,'Помилка!');
                    }
                    return;
                }
                SerenityShop.alert(r.data.message,'Вітаємо!',function(){
                    window.location.href='/';
                });
            });
        },

        onCallMeSubmit: function(e){
            e.preventDefault();
            var $form = $(e.currentTarget),
                data = $form.serializeObject();
            $.post(SerenityShop.urlSuffix+'/callback/save',data,function(r){

                $form.find('.helper').remove();
                $form.find('.error').removeClass('error');

                if(!r.status){

                        for (var k in r.errors) {
                            $form
                                .find('input[name="'+ k+'"]')
                                .after('<span class="helper">'+ r.errors[k]+'</span>')
                                .parents('.input-item').addClass('error')
                        }
                    } else {
                        //SerenityShop.alert(r.message,SerenityShop.lang.error);

                        $.fancybox({
                            openEffect	: 'fade',
                            closeEffect	: 'fade',
                            padding: 0,
                            tpl: {
                                closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                            },
                            content: '<div class="simple-modal" style="display: block;">' + SerenityShop.lang.we_callback + '</div>'
                        });
                    $form.trigger('reset');

                }


                return false;


                //window.location.href = '/callback/confirm';
                /*$('#cart-summary .customer-name').text(r.data.name+'!')
                 $('#customer-mod').removeClass('show-login').addClass('logged-in');
                 $('body').removeClass('lock-scroll');
                 $form.closest('.popup-overlay').remove();*/
            });
        },


    onRecoverClick: function(e){
            e.preventDefault();
            var $popup = $(_.template($('#tmpl-recover-form').html(),{}));
            $('body').addClass('lock-scroll').append($popup);
            $popup
                .find('.popup-close')
                .on('click',function(e){
                    e.preventDefault();
                    $popup.remove();
                    $('body').removeClass('lock-scroll');
                }).end()
                .find('form').on('submit',app.onRecoverSubmit);
        },

        onRecoverSubmit: function(e){
            e.preventDefault();
            var $form = $(e.currentTarget),
                data = $form.serializeObject();
            $.post(SerenityShop.urlSuffix+'/customer/recover_request',data,function(r){

                $form.find('.helper').remove();
                $form.find('.error').removeClass('error');

                if(r.result=='error'){
                    if(r.data){
                        $form
                            .find('input[name="'+ r.data.field+'"]')
                            .after('<span class="helper">'+ r.data.message+'</span>')
                            .parents('.input-item').addClass('error')
                    } else {
                        $.fancybox({
                            openEffect	: 'fade',
                            closeEffect	: 'fade',
                            padding: 0,
                            tpl: {
                                closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                            },
                            content: '<div class="simple-modal" style="display: block;">' + SerenityShop.lang.error + '</div>'
                        });
                        // SerenityShop.alert(r.data.message,SerenityShop.lang.error);
                    }
                    return;
                }
                // SerenityShop.alert(r.data.message,SerenityShop.lang.attention,function(){
                //     $('.popup-overlay').remove();
                // });

                $.fancybox({
                    openEffect	: 'fade',
                    closeEffect	: 'fade',
                    padding: 0,
                    tpl: {
                        closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                    },
                    content: '<div class="simple-modal" style="display: block;">' + r.data.message + '</div>'
                });


                $form.trigger('reset');

            });
        },

        onContactClick: function(e){
            e.preventDefault();
            console.log('Contact click')
            var $trigger = $(e.currentTarget),
                $popup = $(_.template($('#tmpl-contact-form').html(),{
                    mode: $trigger.data('mode')
                })),
                $productLink;
            $('body').addClass('lock-scroll').append($popup);
            $popup.find("#contact-phone").mask('38(099)999-99-99', {
                placeholder: '38(000)000-00-00',
            });
            $popup
                .find('.popup-close')
                .on('click',function(e){
                    e.preventDefault();
                    $popup.remove();
                    $('body').removeClass('lock-scroll');
                }).end()
                .find('form').on('submit',app.onContactSubmit);
            switch($trigger.data('mode')){
                case 'product':
                    $productLink = $trigger.closest('.item').find('.title a');
                    $popup.find('#contact-message').val(SerenityShop.lang.notify_me+$productLink.text()+' ('+$productLink.get(0).href+')');
                    break;
                case 'callback':
                    $popup.find('#contact-message').val(SerenityShop.lang.call_me);
                    break;
            }
        },

        onContactSubmit: function(e){
            e.preventDefault();
            var $form = $(e.currentTarget),
                data = $form.serializeObject();
            $.post(SerenityShop.urlSuffix+'/customer/contact',data,function(r){
                console.log(r);
                console.log(data);
                $form.find('.helper').remove();
                $form.find('.error').removeClass('error');
                if(r.result=='error'){
                    if(r.data){
                        $form
                            .find('[name="'+ r.data.field+'"]')
                            .after('<span class="helper">'+ r.data.message+'</span>')
                            .parents('.input-item').addClass('error')
                    } else {
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
                    return;
                }

                $.fancybox({
                    openEffect	: 'fade',
                    closeEffect	: 'fade',
                    padding: 0,
                    tpl: {
                        closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                    },
                    content: '<div class="simple-modal" style="display: block;">' + r.data.message + '</div>'
                });
                // SerenityShop.alert(r.data.message,SerenityShop.lang.attention,function(){
                //     $('.popup-overlay').remove();
                // });

                $form.trigger('reset');
            });
        },

        onOneClick: function(e){
             e.preventDefault();
             var id = $(e.target).attr('data-id');

             var formTemplate = _.template($('#one-click-form').html()),
             $popup;
             $popup = $(formTemplate());
             $popup.find("#one-click-phone").mask('38(099)999-99-99', {
                placeholder: '38(000)000-00-00',
            });
             //one-click-product-id
             $popup.find("#one-click-product-id").val(id);
             $('body').addClass('lock-scroll').append($popup);
             $popup
             .find('.popup-close')
             .on('click',function(e){
             e.preventDefault();
             $popup.remove();
             $('body').removeClass('lock-scroll');
             }).end()
             .find('form').on('submit',app.onOneClickSubmit);
        },

        commentGuestbook: function (e) {
            e.preventDefault();
            var $form = $(this),
                data = $form.serializeObject();
            $.post('/guestbook?action=post', data, function (r) {
                $form.find('.helper').remove();
                $form.find('.error').removeClass('error');

                if (r.result != 'success') {
                    if (r.data) {
                        $form
                            .find('[name="' + r.data.field + '"]')
                            .after('<span class="helper">' + r.data.message + '</span>')
                            .parents('.input-item').addClass('error')
                    } else {
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
                    return;
                }

                $.fancybox({
                    openEffect	: 'fade',
                    closeEffect	: 'fade',
                    padding: 0,
                    tpl: {
                        closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                    },
                    content: $('#success_comment')
                });

                $form.trigger('reset');



                // $.fancybox({
                //     openEffect	: 'fade',
                //     closeEffect	: 'fade',
                //     padding: 0,
                //     tpl: {
                //         closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                //     },
                //     content: '<div class="simple-modal" style="display: block;">' + '{$locale.feedback_success}'+ '{$locale.thank_you}!' + '</div>'
                // });
            });
        },

        onOneClickSubmit: function(e){
            e.preventDefault();
            var $form = $(e.currentTarget),
                data = $form.serializeObject();

            $.post(SerenityShop.urlSuffix+'/cart/oneClick',data,function(r){
                console.log(r);
                console.log(data);

                $form.find('.helper').remove();
                $form.find('.error').removeClass('error');

                if(r.result=='error'){
                    if(r.data){
                        $form
                            .find('input[name="'+ r.data.field+'"]')
                            .after('<span class="helper">'+ r.data.message+'</span>')
                            .parents('.input-item').addClass('error')
                    } else {
                        $.fancybox({
                            openEffect	: 'fade',
                            closeEffect	: 'fade',
                            padding: 0,
                            tpl: {
                                closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                            },
                            content: '<div class="simple-modal" style="display: block;"><p>' + SerenityShop.lang.error + '</p></div>'
                        });
                        //SerenityShop.alert(r.data.message,SerenityShop.lang.error);
                    }
                    return false;
                }

                $form.find('input').val('');

                $.fancybox({
                    openEffect	: 'fade',
                    closeEffect	: 'fade',
                    padding: 0,
                    tpl: {
                        closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                    },
                    content: '<div class="simple-modal" style="display: block;"><p>'+  SerenityShop.lang.order_number + ' - ' + data.id +'</p><p>'+ SerenityShop.lang.order_thank_you +'</p></div>'
                });

                // window.location.href = '/cart/confirmOneClick';
                /*$('#cart-summary .customer-name').text(r.data.name+'!')
                 $('#customer-mod').removeClass('show-login').addClass('logged-in');
                 $('body').removeClass('lock-scroll');
                 $form.closest('.popup-overlay').remove();*/
            });
        },

        onSubscribeClick: function (e) {
            e.preventDefault();
            var $trigger = $(e.currentTarget),
                $popup = $(_.template($('#tmpl-subscribe-form').html(),{})),
                $productLink;
            $('body').addClass('lock-scroll').append($popup);
            $popup
                .find('.popup-close')
                .on('click',function(e){
                    e.preventDefault();
                    $popup.remove();
                    $('body').removeClass('lock-scroll');
                }).end()
                .find('form').on('submit', function (e) {
                    e.preventDefault();
                    var $form = $(e.currentTarget);
                    var data = $form.serializeObject();
                    $.post('/customer/subscribe',data, function (r) {
                        if(r.result=='error'){
                            SerenityShop.alert(r.message,SerenityShop.lang.error);
                        } else {
                            $popup.remove();
                            $('body').removeClass('lock-scroll');
                            SerenityShop.alert(r.message,SerenityShop.lang.done);
                        }
                    });
                });
        },

        footerSubscribe: function(){
            $('.subscibe_form').on('submit', function (e) {
                e.preventDefault();
                var $form = $(e.currentTarget);
                var data = $form.serializeObject();
                $.post('/customer/subscribe',data, function (r) {
                    if(r.result=='error'){
                        $.fancybox({
                            openEffect	: 'fade',
                            closeEffect	: 'fade',
                            padding: 0,
                            tpl: {
                                closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                            },
                            content: '<div class="simple-modal" style="display: block;">' + r.data.message + '</div>'
                        });
                    } else {
                        $form.trigger('reset');
                        $.fancybox({
                            openEffect	: 'fade',
                            closeEffect	: 'fade',
                            padding: 0,
                            tpl: {
                                closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                            },
                            content: '<div class="simple-modal" style="display: block;">' + r.message + '</div>'
                        });
                    }
                });
            });
        },

        bidLoginEvents: function(){

            //New actions in forms
            $('#call-me-form').on('submit',app.onCallMeSubmit);

            $('#signup-form').on('submit',app.onSignupSubmit);

            $('#one-click-form').on('submit',app.onOneClickSubmit);

            $('#guestbook-post').on('submit',app.commentGuestbook);

            $('#pass-recovery').on('submit',app.onRecoverSubmit);

            $('#recover-form').on('submit',app.onRecoverPassword);

            // $('.add-to-cart-trigger').on('click',app.)







            $('#signup').on('click',app.onSignupClick);
            $('.call-me-trigger').on('click',app.onCallMeClick);
            $('#call-me-button').on('click',app.onCallMeClick);
            $('.one-click-trigger').on('click', app.onOneClick);
            $('#pass-recovery-trigger').on('click',app.onRecoverClick);
            $('#login').on('click',function(e){
                e.preventDefault();
                $('#customer-mod').toggleClass('show-login');
            });
            $('#customer-mod .close-popup').on('click',function(e){
                e.preventDefault();
                $('#customer-mod').removeClass('show-login');
            });
            $('#logout').on('click',function(e){
                e.preventDefault();
                $.get(SerenityShop.urlSuffix+'/customer/logout',function(r){
                    if(r.result!='success') return;
                    window.location.reload();
                    /*$('#customer-mod').removeClass('logged-in');
                    if(window.location.pathname.indexOf('/customer')!==-1) window.location.href='/';*/
                });
            });
            $('#login-form').on('submit',function(e){

                e.preventDefault();
                var $form = $(this),
                    data = $form.serializeObject();
                $.post(SerenityShop.urlSuffix+'/customer/login',data,function(r){
                    $form.find('.helper').remove();
                    $form.find('.error').removeClass('error');

                    if(r.result=='error'){
                        if(r.data){
                            $form
                                .find('input[name="'+ r.data.field+'"]')
                                .after('<span class="helper">'+ r.data.message+'</span>')
                                .parents('.input-item').addClass('error')
                        }
                        return;
                    }
                    window.location.reload();
                    /*$('#cart-summary .customer-name').text(r.data.name+'!')
                    $('#customer-mod').removeClass('show-login').addClass('logged-in');
                    $form.find('input').each(function(){
                        this.value='';
                    });*/
                });
            })
        },

        onAddToCartClick: function(e){
            e.preventDefault();
            var $button= $(e.currentTarget),
                $qtyInput,
                type = $button.data('type'),
                productId = $button.data('id'), qty, $overlay;
            if(type=='form'){
                $qtyInput = $button.parents('.product-to-cash').find('input[name="qty"]');
                qty = $qtyInput.val();
                //qty = !_.isNaN(parseInt(qty)) ? parseInt(qty) : 1;
                $qtyInput.val(qty);
                //$overlay = $('.popup-overlay')
                //$overlay.hide();
                //$('body').removeClass('lock-scroll');
                $.post(SerenityShop.urlSuffix+'/cart/update',{id: productId, qty: qty},function(r){

                    if(r.result != 'success'){
                        $.fancybox({
                            openEffect	: 'fade',
                            closeEffect	: 'fade',
                            padding: 0,
                            tpl: {
                                closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                            },
                            content: '<div class="simple-modal" style="display:block;">' + ( r.data ? r.data.message : r.message )+ '</div>'
                        });

                        if(r.data && r.data.max_qty){
                            $qtyInput.val(r.data.max_qty);
                        }
                        //$overlay.show();
                        return;
                    }

                    $.fancybox({
                        openEffect	: 'fade',
                        closeEffect	: 'fade',
                        padding: 0,
                        tpl: {
                            closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                        },
                        content: '<div class="simple-modal" style="display: block;">' + SerenityShop.lang.add_to_card + '</div>'
                    });

                    var popupTpl = _.template($('#tmpl-get-basket-items').html()),
                        $popup = $(popupTpl({products: r.items , total: r.data}));

                    $('#open-basket').html($popup);

                    $('#open-basket input.number').styler();

                    $('.header-basket_icon').html('<i class="fa fa-shopping-cart" aria-hidden="true"></i><span>' + r.data.count + '</span>');
                    $('.header-basket_price').html(r.data.price + '<span>грн.</span>');

                    //$overlay.trigger('popupHide');
                    //$overlay.remove();
                    //$('.open-basket .header-basket_icon').append('<span>' + r.data.count + '</span>');
                    //$('.open-basket .header-basket_price').html(app.formatPrice(r.data.price));
                });
            } else {
                app.openModificationSelector(productId);
            }
        },

        openModificationSelector: function(productId){
            $.get(SerenityShop.urlSuffix+'/product',{json: 1,id: productId},function(r){

                if(r.result!='success'){

                    if(r.message){
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
                    return;
                }
                var popupTpl = _.template($('#tmpl-add-to-cart-form').html()),
                    $popup = $(popupTpl({product: r.data}));

                $.fancybox({
                    openEffect	: 'fade',
                    closeEffect	: 'fade',
                    padding: 0,
                    tpl: {
                        closeBtn : '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>'
                    },
                    content: $popup
                });

                $('input.number').styler();


                //$('body').addClass('lock-scroll').append($popup);
                //$p = $popup.find('.popup');
                //adjustPosition = _.debounce(function (){
                //    $p.css({
                //        'margin-left': (-$p.outerWidth()/2)+'px',
                //        'margin-top': (-$p.outerHeight()/2)+'px'
                //    })
                //},50);
                //$(window).on('resize',adjustPosition);
                //$popup
                //    .on('popupHide', function () {
                //        $(window).off('resize',adjustPosition);
                //    })
                //    .find('.popup-close')
                //    .on('click',function(e){
                //        e.preventDefault();
                //        $(window).off('resize',adjustPosition);
                //        $popup.remove();
                //        $('body').removeClass('lock-scroll');
                //    });
                //_.delay(adjustPosition,1);
            });
        },

        alert: function(text,title,cb){
            var $popup = $(_.template($('#tmpl-custom-alert').html(),{text: text, title: title || SerenityShop.lang.attention})),
                $popupWindow = $popup.find('.popup');
            $('body').addClass('lock-scroll').append($popup);
            function unlockScroll(){
                if(!$('.popup-overlay').length){
                    $('body').removeClass('lock-scroll');
                }
            }
            $popup
                .find('.popup-close, button')
                .on('click',function(e){
                    e.preventDefault();
                    if(cb) cb.apply(this);
                    $popup.remove();
                    unlockScroll();
                });
            _.delay(function(){
                $popup.css('opacity',1);
                $popupWindow.css({
                    'left': '50%',
                    'top': '50%',
                    'margin-left': '-'+($popupWindow.outerWidth()/2)+'px',
                    'margin-top': '-'+($popupWindow.outerHeight()/2)+'px'
                });
            },1);
            return $popup;
        },

        formatPrice: function(price){
            price = _.isNaN(parseFloat(price)) ? 0 : parseFloat(price);
            return price.toFixed(2).replace('.00','').replace('.',',');
        },

        init: function(){

            var $priceRange = $('#price-range');
            if($priceRange.length){
                try {
                    var range = document.getElementById('price-range'),
                        limitFieldMin = document.getElementById('minCost'),
                        limitFieldMax = document.getElementById('maxCost'),
                        inputs = [limitFieldMin, limitFieldMax];

                    noUiSlider.create(range, {
                        range: {
                            'min':parseInt($priceRange.data('from')),
                            'max':parseInt($priceRange.data('to'))
                        },
                        start: [parseInt($priceRange.data('start')),parseInt($priceRange.data('end'))],
                        connect: true,
                        format: wNumb({
                            decimals: 0
                        }),
                        step: 1
                    });

                    range.noUiSlider.on('update', function( values, handle ){
                        (handle ? limitFieldMax : limitFieldMin).value = values[handle];
                    });

                    function setSliderHandle(i, value) {
                        var r = [null,null];
                        r[i] = value;
                        range.noUiSlider.set(r);
                    }

                    inputs.forEach(function(input, handle) {

                        input.addEventListener('change', function(){
                            setSliderHandle(handle, this.value);
                        });

                        input.addEventListener('keydown', function( e ) {

                            var values = range.noUiSlider.get();
                            var value = Number(values[handle]);

                            // [[handle0_down, handle0_up], [handle1_down, handle1_up]]
                            var steps = range.noUiSlider.steps();

                            // [down, up]
                            var step = steps[handle];

                            var position;

                            // 13 is enter,
                            // 38 is key up,
                            // 40 is key down.
                            switch ( e.which ) {

                                case 13:
                                    setSliderHandle(handle, this.value);
                                    break;

                                case 38:

                                    // Get step to go increase slider value (up)
                                    position = step[1];

                                    // false = no step is set
                                    if ( position === false ) {
                                        position = 1;
                                    }

                                    // null = edge of slider
                                    if ( position !== null ) {
                                        setSliderHandle(handle, value + position);
                                    }

                                    break;

                                case 40:

                                    position = step[0];

                                    if ( position === false ) {
                                        position = 1;
                                    }

                                    if ( position !== null ) {
                                        setSliderHandle(handle, value - position);
                                    }

                                    break;
                            }
                        });
                    });

                    $('#apply-price-filter').on('click',app.onPriceRangeApply);



                    // $priceRange.noUiSlider({
                    //     range: {
                    //         'min':parseInt($priceRange.data('from')),
                    //         'max':parseInt($priceRange.data('to'))
                    //     },
                    //     start: [parseInt($priceRange.data('start')),parseInt($priceRange.data('end'))],
                    //     connect: true,
                    //     slide: app.onPriceRangeSlide,
                    //     step: 1
                    // }).change(app.onPriceRangeChange);
                    // $('#current-price input.from').on('change',app.onPriceFromChange);
                    // $('#current-price input.to').on('change',app.onPriceToChange);
                }
                catch(e){
                    // $('#price-filter').hide();
                }


            }
            $('.contact-trigger').on('click',app.onContactClick);
            $('#callback-form').on('submit',app.onContactSubmit);

            $('.subscription-trigger').on('click',app.onSubscribeClick);

            app.footerSubscribe();

            $('#aside-handle').on('click',function(e){
                e.preventDefault();
                $('aside').toggleClass('show');
                $('body').toggleClass('aside-open');
            });

            $('.header-lang').on('click','a',function(e){
                var link = window.location.href.split(window.location.host);
                e.preventDefault();
                link[1] = link[1].replace('/ru','');
                if($(this).data('lang')!=''){
                    link[1] = '/'+$(this).data('lang')+link[1];
                }
                window.location.href=link.join(window.location.host);
            });

            app.bidLoginEvents();
            $(document).on('click','.add-to-cart-trigger',app.onAddToCartClick);
        }
    };
    window.SerenityShop = app;
    
    function Experts() {
        
        return {
            sliceList: function() {
                var item = $('.experts__top__info__list ul');
                
                if(item.length) {
                    item.each(function(){
                        var row = $(this).find('li'),
                            rowL = Math.ceil(row.length/2);

                        row.slice(0, rowL).wrapAll('<div class="list-half"></div>');
                        row.slice(rowL, row.length).wrapAll('<div class="list-half"></div>');
                    })
                }
            },
            
            select2Init: function() {
                var item = $('.select-wrap select');
                
                if(item.length) {
                    item.each(function(){
                        $(this).select2({
                            minimumResultsForSearch: -1
                        })
                    })
                }
            }
        }
    }
    
    var E = new Experts();
    
    $(document).ready(function(){
        app.init();
        E.sliceList();    
        E.select2Init();
    });
    
})();