function FrontCustom() {

    return {
        headerLang: function () {
            $('.header-lang > a').click(function () {
                $('.header-lang_dropdown').slideToggle(400);
                $(this).toggleClass('active');

                return false;
            });
        },

        mainSlider: function () {
            $('.main-slider_wrapper').slick({
                infinite: true,
                autoplay: true,
                adaptiveHeight: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
                nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            arrows: false,
                            dots: true
                        }
                    },
                ]
            });

            function mainSliderDots() {
                var dots = $('.main-slider').find('.slick-dots li');

                for (var i = 0; i < dots.length; i++) {
                    dots.width($('.main-slider').width() / dots.length);
                }
            };

            mainSliderDots();
            $(window).resize(function () {
                mainSliderDots();
            });

            //var sliderHeight = $('.main-slider_slide').height()

        },

        oneGoodStars: function () {
            $('.one-good_stars').rating();
        },

        productCarousel: function () {
            $('.product-carousel_wrapper').slick({
                infinite: false,
                slidesToShow: 4,
                slidesToScroll: 1,
                prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
                nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 540,
                        settings: {
                            slidesToShow: 1,
                        }
                    },
                ]
            });
        },

        brandsCarousel: function () {
            $('.brands-slider_wrapper').slick({
                infinite: true,
                slidesToShow: 5,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
                nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                        }
                    },
                ]
            });
        },

        inputMask: function () {
            $('input[type="tel"]').mask('38(099)999-99-99', {
                placeholder: '38(000)000-00-00',
            });
        },

        openEnterForm: function () {
            function authFormPosition() {
                if ($('.header-auth').length) {
                    var windowWidth = $(window).width(),
                        btnPosition = $('.header-auth').offset().left + $('.header-auth').outerWidth(),
                        position = windowWidth - btnPosition;

                    $('.auth-form').css({
                        'right': position,
                    });
                }

            };

            authFormPosition();
            $(window).resize(function () {
                authFormPosition();
            });

            $('body').on('click', '.top-line .header-auth a', function () {
                var firstClick = true,
                    enterForm = $('.enter-form'),
                    regForm = $('.registration-form');

                if (enterForm.hasClass('open')) {
                    enterForm.slideUp(500).removeClass('open');
                } else if (!(enterForm.hasClass('open')) && regForm.hasClass('open')) {
                    regForm.slideUp(500).removeClass('open')
                } else {
                    enterForm.slideDown(500).addClass('open');
                }
                $(this).toggleClass('active');
                return false;
            });

            $('body').on('click', '.descktop .open-registr_form', function () {
                $('.enter-form').slideUp(500).removeClass('open');
                $('.registration-form').slideDown(500).addClass('open');
                return false;
            });

            $('body').on('click', '.descktop .open-enter_form', function () {
                $('.registration-form').slideUp(500).removeClass('open');
                $('.enter-form').slideDown(500).addClass('open');
                return false;

            });

            $('body').on('click', '.header-mobile .header-auth > a', function () {
                $('.enter-form').slideToggle(400);
                $(this).toggleClass('active');

                return false;
            });

            $('body').on('click', '.fancybox-overlay .open-enter_form', function (e) {
                e.preventDefault();

                $.fancybox.close();
            });
        },

        basketPopup: function () {

            $('.open-basket, .product-img .fancybox-image').fancybox({
                scrolling: 'visible',
                openEffect: 'elastic',
                closeEffect: 'elastic',
                openSpeed: 400,
                closeSpeed: 400,
                padding: 0,
                tpl: {
                    closeBtn: '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>',
                }
            });

            $('.bascket-back').click(function (e) {
                e.preventDefault();

                $.fancybox.close();
            });

        },

        modal: function () {
            $('.open-modal').fancybox({
                scrolling: 'visible',
                openEffect: 'fade',
                closeEffect: 'fade',
                openSpeed: 400,
                closeSpeed: 400,
                padding: 0,
                tpl: {
                    closeBtn: '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>',
                }
            });

            $('.bonus-plus-modal .btn').click(function (e) {
                e.preventDefault();

                $.fancybox.close();
            });

            $('.btn-simple.open-modal').on('click', function () {
                var productId = $(this).attr('data-id');
                $('#buy-oneclick').find('#one-click-product-id').attr('value', productId)
            });
        },

        formStyler: function () {
            $('input.number').styler();
            $('.user-products_amount input').styler();

            $('.dropdown select').styler({
                selectSmartPositioning: false,
            });
            $('.input-item select').styler({
                selectSmartPositioning: false,
            });
        },

        mobileHeader: function () {
            function headerAppend() {
                if ($(window).outerWidth() < 992) {
                    $('.logo-wrapper .logo').prependTo('.header-mobile > div');
                    $('.top-nav').prependTo('.mobile-menu');
                    $('.header-auth').appendTo('.mobile-menu');
                    $('.header-profile').appendTo('.mobile-menu');
                    $('.header-search').appendTo('.mobile-menu');
                    if ($('.mobile-menu .footer-contacts_item.tel').length == false) {
                        console.log('remove');
                        $('.footer-contacts_item.tel')
                            .clone().appendTo('.mobile-menu');
                    }

                    $('.auth-form.enter-form').appendTo('.header-auth').removeClass('descktop');
                    $('.header-lang').appendTo('.mobile-menu');
                    $('.registration-form').appendTo('body').attr('id', 'open-registr_form');
                    $('.auth-form.enter-form').find('.open-registr_form').addClass('open-modal')
                        .attr('href', '#open-registr_form').removeClass('descktop');
                    $('.header-basket .open-basket').appendTo('.header-mobile > div');
                } else {
                    $('.header-mobile .logo').appendTo('.logo-wrapper');
                    $('.top-nav').prependTo('.top-line .container');
                    $('.header-lang').appendTo('.header-top_right');
                    $('.header-auth').appendTo('.header-top_right');
                    $('.header-profile').appendTo('.header-top_right');
                    $('.auth-form.enter-form').appendTo('body').addClass('descktop');
                    $('.auth-form.registration-form').appendTo('body').addClass('descktop').attr('id', '');
                    $('.auth-form.enter-form').find('.open-registr_form').removeClass('open-modal').attr('href', '#');
                    $('.header-mobile .open-basket').appendTo('.header-basket');
                }
            };

            headerAppend();
            // $(window).resize(function () {
            //     headerAppend();
            // })

            $('.mobile-menu_btn').click(function () {
                $('.mobile-menu').toggleClass('active');
                $('body, html').toggleClass('menu-open');
                $(this).toggleClass('active');
                return false;
            })
        },

        mobileMenu: function () {
            function mobileMenuAppend() {
                if ($(window).outerWidth() < 992) {
                    $('.main-nav_wrapper').addClass('cd-dropdown-wrapper');
                    $('.main-nav').addClass('cd-dropdown');
                    $('.main-nav > ul').addClass('cd-dropdown-content');
                    $('.main-nav > ul ul').addClass('cd-secondary-dropdown is-hidden');
                } else {
                    $('.main-nav_wrapper').removeClass('cd-dropdown-wrapper');
                    $('.main-nav').removeClass('cd-dropdown');
                    $('.main-nav > ul').removeClass('cd-dropdown-content');
                    $('.main-nav > ul ul').removeClass('cd-secondary-dropdown is-hidden');
                }
            };

            mobileMenuAppend();
            $(window).resize(function () {
                mobileMenuAppend();
            });
        },

        footerHeight: function () {

            function setFooterHeight() {
                var footerHeight = $('.footer-wrapper').outerHeight();

                $('.main-wrapper').css({
                    'paddingBottom': footerHeight + 'px',
                });
                $('.footer-wrapper').css({
                    'marginTop': '-' + footerHeight + 'px'
                });
            };

            setFooterHeight();
            $(window).resize(function () {
                setFooterHeight();
            });

        },

        mobileFooter: function () {
            function mobileFooterChanges() {
                if ($(window).outerWidth() < 992) {
                    $('.footer-social').appendTo('.footer-main .container');
                    $('.footer-wrapper').addClass('mobile');
                    $('.footer-contacts').hide();
                    $('.footer-nav').hide();
                    $('.footer-subscribe').hide();
                } else {
                    $('.footer-social').appendTo('.footer-col:first');
                    $('.footer-wrapper').removeClass('mobile');
                    $('.footer-contacts').show();
                    $('.footer-nav').show();
                    $('.footer-subscribe').show();
                }

                if ($(window).outerWidth() < 768) {
                    $('.copyright').appendTo('.footer-main .container');
                    $('.footer-social').appendTo('.footer-main .container');
                } else {
                    $('.copyright').appendTo('.footer-bottom .container > div > div:first');
                }
            };

            mobileFooterChanges();
            $(window).resize(function () {
                mobileFooterChanges();
            });

            $('.footer-wrapper.mobile .footer-title').click(function () {
                if (!($(this).hasClass('active'))) {
                    $('.footer-title').removeClass('active').next().slideUp(500);
                    $(this).addClass('active').next().slideDown(500);
                } else {
                    $(this).removeClass('active').next().slideUp(500);
                }
            });
        },

        mobileBasket: function () {
            function mobileBasketAppend() {
                if ($(window).outerWidth() < 768) {
                    $('.basket-item').each(function () {
                        $(this).find('.basket-item_amount').prependTo($(this).find('.basket-item_price'));
                        $(this).find('.basket-item_del').appendTo($(this).find('.basket-item_info'));
                    });
                } else {
                    $('.basket-item').each(function () {
                        $(this).find('.basket-item_amount').appendTo($(this).find('.basket-item_info'));
                        $(this).find('.basket-item_del').appendTo($(this).find('.basket-item_price'));
                    });
                }
            };

            mobileBasketAppend();
            $(window).resize(function () {
                mobileBasketAppend();
            });
        },

        customScroll: function () {
            $('.filter-list.with-scroll').mCustomScrollbar();
        },

        filterSlider: function () {
            // var maxValue = 1000;
            // $('.filter-price_slider').slider({
            //     min: 0,
            //     max: maxValue,
            //     values: [0,maxValue],
            //     range: true,
            //     stop: function(event, ui) {
            //         $("input#minCost").val($(".filter-price_slider").slider("values",0));
            //         $("input#maxCost").val($(".filter-price_slider").slider("values",1));
            //     },
            //     slide: function(event, ui){
            //         $("input#minCost").val($(".filter-price_slider").slider("values",0));
            //         $("input#maxCost").val($(".filter-price_slider").slider("values",1));
            //     },
            // });
            //
            // $("input#minCost").change(function(){
            //     var value1=$("input#minCost").val();
            //     var value2=$("input#maxCost").val();
            //     if(parseInt(value1) > parseInt(value2)){
            //         value1 = value2;
            //         $("input#minCost").val(value1);
            //     }
            //     $(".filter-price_slider").slider("values",0,value1);
            // });
            // $("input#maxCost").change(function(){
            //     var value1=$("input#minCost").val();
            //     var value2=$("input#maxCost").val();
            //
            //     if (value2 > maxValue) {
            //         value2 = maxValue;
            //         $("input#maxCost").val(maxValue);
            //     }
            //     if(parseInt(value1) > parseInt(value2)){
            //         value2 = value1;
            //         $("input#maxCost").val(value2);
            //     }
            //     $(".filter-price_slider").slider("values",1,value2);
            // });
            //
            // $('input#maxCost, input#minCost').mask('000000000');

            //$('.filter-list .checkbox').click(function () {
            //    $(this).toggleClass('checked');
            //    return false;
            //});
        },

        filterMobile: function () {
            function filterMobileChange() {
                if ($(window).outerWidth() < 992) {
                    $('.catalog-filter').appendTo('body').attr('id', 'open-filter');
                } else {
                    $('.catalog-filter').appendTo('.filter-append').attr('id', '');
                }
            };

            filterMobileChange();
            /*$(window).resize(function(){
             filterMobileChange();
             });*/
        },

        ratingStars: function () {
            $('.rating-stars').rating();
        },

        productTabs: function () {

            // $('.read-more').toggle(function () {
            //     $('.read-more').html(SerenityShop.lang.less);
            //     $('.read-more').prev('.custom-page-text').addClass('open');
            // },function () {
            //     $('.read-more').html(SerenityShop.lang.more);
            //     $('.read-more').prev('.custom-page-text').removeClass('open');
            // });

            $('.read-more').on('click', function () {
                var btn = $(this),
                    hideText = btn.prev('.custom-page-text');

                if (hideText.hasClass('open')) {
                    btn.html(SerenityShop.lang.more);
                } else {
                    btn.html(SerenityShop.lang.less);
                }
                hideText.toggleClass('open');
            });

            function productTabsMobile() {
                if ($(window).outerWidth(true) <= 767) {
                    $('.product-tabs').addClass('mobile').removeClass('desktop');

                    for (var i = 0; i < $('.product-tabs .one-tab').length; i++) {

                        var $li = $('.product-tabs_links > ul > li').eq(i),
                            $tab = $('.product-tabs .one-tab').eq(i);

                        $tab.appendTo($li);
                    }

                    $('.product-tabs_links .tab-link').next().hide();
                    $('.product-tabs_links .tab-link').removeClass('active');
                    $('.product-tabs_links li:first').children('.product-tabs .one-tab').show().prev('.tab-link').addClass('active');

                } else if ($(window).outerWidth(true) >= 767) {

                    $('.product-tabs').removeClass('mobile').addClass('desktop');
                    var $all_li = $('.product-tabs').find('.product-tabs_links').children('ul').children('li'),
                        $tabWrapper = $('.product-tabs_content');

                    $all_li.each(function (index) {
                        var $tab = $(this).find('.one-tab');

                        $tab.appendTo($tabWrapper);
                    });

                    $('.product-tabs .one-tab').hide().first().show();
                    $('.product-tabs_links .tab-link').removeClass('active');
                    $('.product-tabs_links li').eq(0).children('.tab-link').addClass('active')
                }
            };

            productTabsMobile()
            $(window).resize(function () {
                productTabsMobile()
            });

            $('body').on('click', '.product-tabs.mobile .tab-link', function () {
                if (!$(this).hasClass('active')) {
                    $('.tab-link').removeClass('active').next().slideUp(400);
                    $(this).addClass('active').next().slideDown(400);
                } else {
                    $(this).removeClass('active').next().slideUp(400);
                }
                return false;
            });

            $('body').on('click', '.product-tabs.desktop .tab-link', function () {
                var $currentLi = $(this).parent('li');
                $('.product-tabs_links .tab-link').removeClass('active').parent('li').eq($currentLi.index()).children('.tab-link').addClass('active');
                $('.product-tabs .one-tab').hide().eq($currentLi.index()).fadeIn(400);

                return false;
            });

            $('.review-count').click(function () {
                $('body, html').animate({
                    scrollTop: $('.product-tabs').offset().top
                }, 500);

                $('.product-tabs_links .tab-link').removeClass('active').parent('li:nth-child(2)').children('.tab-link').addClass('active');
                $('.product-tabs .one-tab').hide().eq(1).fadeIn(400);

                return false;
            });
        },

        mainTabs: function () {
            function mainTabsMobile() {
                if ($(window).outerWidth(true) <= 767) {
                    $('.main-tabs').addClass('mobile').removeClass('desktop');

                    for (var i = 0; i < $('.main-tabs .one-tab').length; i++) {

                        var $li = $('.main-tabs_links > ul > li').eq(i),
                            $tab = $('.main-tabs .one-tab').eq(i);

                        $tab.appendTo($li);
                    }

                    $('.main-tabs_links .tab-link').next().hide();
                    $('.main-tabs_links .tab-link').removeClass('active');
                    $('.main-tabs_links li:first').children('.main-tabs .one-tab').show().prev('.tab-link').addClass('active');

                } else if ($(window).outerWidth(true) >= 767) {

                    $('.main-tabs').removeClass('mobile').addClass('desktop');
                    var $all_li = $('.main-tabs').find('.main-tabs_links').children('ul').children('li'),
                        $tabWrapper = $('.main-tabs_content');

                    $all_li.each(function (index) {
                        var $tab = $(this).find('.one-tab');

                        $tab.appendTo($tabWrapper);
                    });

                    $('.main-tabs .one-tab').hide().first().show();
                    $('.main-tabs_links .tab-link').removeClass('active');
                    $('.main-tabs_links li').eq(0).children('.tab-link').addClass('active')
                }
            };

            mainTabsMobile()
            $(window).resize(function () {
                mainTabsMobile()
            });

            $('body').on('click', '.main-tabs.mobile .tab-link', function () {
                if (!$(this).hasClass('active')) {
                    $('.tab-link').removeClass('active').next().slideUp(400);
                    $(this).addClass('active').next().slideDown(400);
                } else {
                    $(this).removeClass('active').next().slideUp(400);
                }
                return false;
            });

            $('body').on('click', '.main-tabs.desktop .tab-link', function () {
                var $currentLi = $(this).parent('li');
                $('.main-tabs_links .tab-link').removeClass('active').parent('li').eq($currentLi.index()).children('.tab-link').addClass('active');
                $('.main-tabs .one-tab').hide().eq($currentLi.index()).fadeIn(400);

                return false;
            });
        },

        orderTabs: function () {

            for (var i = 0; i < $('.order-tabs .one-tab').length; i++) {

                var $li = $('.order-tabs_links > ul > li').eq(i),
                    $tab = $('.order-tabs .one-tab').eq(i);

                $tab.appendTo($li);
            }

            $('.order-tabs_links .tab-link').next().hide();
            $('.order-tabs_links .tab-link').removeClass('active');
            $('.order-tabs_links li:first').children('.main-tabs .one-tab').show().prev('.tab-link').addClass('active');

            $('body').on('click', '.order-tabs .tab-link', function () {
                if (!$(this).hasClass('active')) {
                    $('.tab-link').removeClass('active').next().slideUp(400);
                    $(this).addClass('active').next().slideDown(400);
                } else {
                    $(this).removeClass('active').next().slideUp(400);
                }
                return false;
            });
        },

        orderForms: function () {
            $('#remember-pass').click(function () {
                $('.order-enter_form').show();
                $('.order-forgetpass_form').hide();

                return false;
            });

            $('#forget-pass').click(function () {
                $('.order-enter_form').hide();
                $('.order-forgetpass_form').show();

                return false;
            });
        },

        profileMenu: function () {
            $('.header-profile > a').click(function () {
                $(this).next('nav').slideToggle(500);
                $(this).toggleClass('active');

                return false;
            });
        },

        newsList: function () {
            $('.news-list_wrapper').masonry({
                itemSelector: '.masonry-item'
            });
        },

        postDetailsGallery: function () {
            $('.post-details_gallery').fancybox({
                openEffect: 'fade',
                closeEffect: 'fade',
                padding: 0,
                tpl: {
                    closeBtn: '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>',
                },
                afterLoad: function () {
                    $('.fancybox-overlay').addClass('gallery');
                },
            });
        },

        popupMap: function () {
            var control = $('.open-map-popup');

            if (control.length) {
                control.each(function () {
                    var Lat = parseFloat($(this).data('lat')),
                        Lng = parseFloat($(this).data('lng')),
                        address = $(this).text(),
                        sheadule = $(this).closest('td').closest('tr').find('td.col-2').text(),
                        tel = $(this).closest('td').closest('tr').find('td.col-3').text();

                    $(this).fancybox({
                        beforeShow: function () {
                            customMap.smallMap(Lat, Lng);

                            $('.map-popup').find('.map-popup_address span').text(address);
                            $('.map-popup').find('.map-popup_tel span').text(tel);
                            $('.map-popup').find('.map-popup_sheadule span').text(sheadule);
                        },
                        scrolling: 'visible',
                        openEffect: 'fade',
                        closeEffect: 'fade',
                        openSpeed: 400,
                        closeSpeed: 400,
                        padding: 0,
                    });

                    $('.map-popup_btn .btn').click(function (e) {
                        e.preventDefault();

                        $.fancybox.close();
                    });

                    console.log(tel);
                })
            }
        },

        galleryCarousel: function () {
            $('.gallery-carousel_wrapper').slick({
                infinite: false,
                slidesToShow: 3,
                slidesToScroll: 1,
                prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
                nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 540,
                        settings: {
                            slidesToShow: 1,
                        }
                    },
                ]
            });
        },


        galleryPopup: function () {
            $('.gallery-item').fancybox({
                openEffect: 'fade',
                closeEffect: 'fade',
                padding: 100,
                maxWidth: 1000,
                autoSize: true,
                tpl: {
                    closeBtn: '<a title="Close" class="fancybox-item fancybox-close icon-cancel" href="javascript:;"></a>',
                },
                afterLoad: function () {
                    $('.fancybox-overlay').addClass('gallery');
                },
            });
        },

        selectProduct: function () {
            $('.product-param a').on('click', function () {
                var productId = $(this).attr('item-id');
                $('.product-param a').removeClass('active');
                $(this).addClass('active');
                $('.product-to-cash:not([item-id="' + productId + '"])').hide();
                $('.product-to-cash[item-id="' + productId + '"]').show();
            });
        }

    };
};

function FrontMap() {
    return {
        offices: function () {
            if ($('#map_canvas').length) {
                var mapCanvas = document.getElementById('map_canvas');

                function initialize() {
                    // задаєм параметри карти
                    var mapOptions = {
                        center: new google.maps.LatLng(49.835412, 24.017184),
                        zoom: 10,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };

                    var styles = [{"featureType":"all","elementType":"geometry.fill","stylers":[{"weight":"2.00"}]},{"featureType":"all","elementType":"geometry.stroke","stylers":[{"color":"#9c9c9c"}]},{"featureType":"all","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#eeeeee"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#7b7b7b"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#c8d7d4"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#070707"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]}]
                    var map = new google.maps.Map(mapCanvas, mapOptions);

                    map.setOptions({styles: styles});

                    //Масив з місцями і маркерами
                    var markers = [],
                        myPlaces = [];

                    //Добавляєм місця в масив
                    myPlaces.push(new Place('пл.Соборна,14', 49.838784, 24.034294, 'ТЦ "Роксолана"'));

                    //Добавляєм маркери для кожного місця
                    for (var i = 0, n = myPlaces.length; i < n; i++) {
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(myPlaces[i].latitude, myPlaces[i].longitude),
                            map: map,
                            title: myPlaces[i].name,
                            icon: {
                                url: "assets/img/map-marker.png",
                                scaledSize: new google.maps.Size(36, 52)
                            }
                        });
                        //Добавляєм попап карти
                        var infowindow = new google.maps.InfoWindow({
                            content: '<h3>' + myPlaces[i].name + '</h3><br/>' + myPlaces[i].description
                        });
                        //Привязуєм попап до маркеру
                        makeInfoWindowEvent(map, infowindow, marker);
                        markers.push(marker);
                    }
                };

                //Привязуєм клік до маркеру
                function makeInfoWindowEvent(map, infowindow, marker) {
                    google.maps.event.addListener(marker, 'click', function () {
                        infowindow.open(map, marker);
                    });
                }

                //Клас для маніпулювання місцями
                function Place(name, latitude, longitude, description) {
                    this.name = name;  // название
                    this.latitude = latitude;  // широта
                    this.longitude = longitude;  // долгота
                    this.description = description;  // описание места
                };

                google.maps.event.addDomListener(window, 'load', initialize);
            }

        },

        smallMap: function (Lat, Lng) {
            if ($('#map_small').length) {

                var mapCanvas = document.getElementById('map_small');

                function initialize() {
                    // задаєм параметри карти
                    var mapOptions = {
                        center: new google.maps.LatLng(Lat, Lng),
                        zoom: 12,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };

                    var styles = [
                        {
                            "featureType": "water",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#e9e9e9"
                                },
                                {
                                    "lightness": 17
                                }
                            ]
                        },
                        {
                            "featureType": "landscape",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#f5f5f5"
                                },
                                {
                                    "lightness": 20
                                }
                            ]
                        },
                        {
                            "featureType": "road.highway",
                            "elementType": "geometry.fill",
                            "stylers": [
                                {
                                    "color": "#ffffff"
                                },
                                {
                                    "lightness": 17
                                }
                            ]
                        },
                        {
                            "featureType": "road.highway",
                            "elementType": "geometry.stroke",
                            "stylers": [
                                {
                                    "color": "#ffffff"
                                },
                                {
                                    "lightness": 29
                                },
                                {
                                    "weight": 0.2
                                }
                            ]
                        },
                        {
                            "featureType": "road.arterial",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#ffffff"
                                },
                                {
                                    "lightness": 18
                                }
                            ]
                        },
                        {
                            "featureType": "road.local",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#ffffff"
                                },
                                {
                                    "lightness": 16
                                }
                            ]
                        },
                        {
                            "featureType": "poi",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#f5f5f5"
                                },
                                {
                                    "lightness": 21
                                }
                            ]
                        },
                        {
                            "featureType": "poi.park",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#dedede"
                                },
                                {
                                    "lightness": 21
                                }
                            ]
                        },
                        {
                            "elementType": "labels.text.stroke",
                            "stylers": [
                                {
                                    "visibility": "on"
                                },
                                {
                                    "color": "#ffffff"
                                },
                                {
                                    "lightness": 16
                                }
                            ]
                        },
                        {
                            "elementType": "labels.text.fill",
                            "stylers": [
                                {
                                    "saturation": 36
                                },
                                {
                                    "color": "#333333"
                                },
                                {
                                    "lightness": 40
                                }
                            ]
                        },
                        {
                            "elementType": "labels.icon",
                            "stylers": [
                                {
                                    "visibility": "off"
                                }
                            ]
                        },
                        {
                            "featureType": "transit",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#f2f2f2"
                                },
                                {
                                    "lightness": 19
                                }
                            ]
                        },
                        {
                            "featureType": "administrative",
                            "elementType": "geometry.fill",
                            "stylers": [
                                {
                                    "color": "#fefefe"
                                },
                                {
                                    "lightness": 20
                                }
                            ]
                        },
                        {
                            "featureType": "administrative",
                            "elementType": "geometry.stroke",
                            "stylers": [
                                {
                                    "color": "#fefefe"
                                },
                                {
                                    "lightness": 17
                                },
                                {
                                    "weight": 1.2
                                }
                            ]
                        }
                    ]
                    var map = new google.maps.Map(mapCanvas, mapOptions);

                    map.setOptions({styles: styles});

                    //Масив з місцями і маркерами
                    var markers = [],
                        myPlaces = [];

                    //Добавляєм місця в масив
                    myPlaces.push(new Place(Lat, Lng));

                    //Добавляєм маркери для кожного місця
                    for (var i = 0, n = myPlaces.length; i < n; i++) {
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(myPlaces[i].latitude, myPlaces[i].longitude),
                            map: map,
                            title: myPlaces[i].name,
                            icon: {
                                url: "assets/img/map-marker.png",
                                scaledSize: new google.maps.Size(36, 52)
                            }
                        });
                        markers.push(marker);
                    }
                };

                //Привязуєм клік до маркеру
                function makeInfoWindowEvent(map, infowindow, marker) {
                    google.maps.event.addListener(marker, 'click', function () {
                        infowindow.open(map, marker);
                    });
                }

                //Клас для маніпулювання місцями
                function Place(latitude, longitude) {
                    this.latitude = latitude;  // широта
                    this.longitude = longitude;  // долгота
                };

                initialize();
            }
        },
    };
};

var customApp = new FrontCustom();
var customMap = new FrontMap();

$(document).ready(function () {

    customApp.headerLang();
    customApp.mainSlider();
    // customApp.oneGoodStars();
    customApp.productCarousel();
    customApp.brandsCarousel();
    customApp.inputMask();
    customApp.selectProduct();
    customApp.openEnterForm();
    customApp.formStyler();
    customApp.mobileHeader();
    customApp.mobileMenu();
    customApp.mobileFooter();
    customApp.footerHeight();
    customApp.mobileBasket();
    customApp.customScroll();
    customApp.filterSlider();
    customApp.filterMobile();
    // customApp.ratingStars();
    customApp.productTabs();
    customApp.mainTabs();
    customApp.orderTabs();
    customApp.orderForms();
    customApp.profileMenu();
    customApp.newsList();
    customApp.postDetailsGallery();
    customApp.galleryCarousel();
    customApp.galleryPopup();
    customApp.modal();
    customApp.basketPopup();
    customApp.popupMap();

    // customMap.offices();

});
