/**
 * Created by Sergiy on 31.12.13.
 */
(function () {

    if (window.shopMap) {

        var sliderArray = window.shopMap.items;

        var sliderTpl = _.template($('#tmpl_slider_item').html());
        var slide = sliderTpl({items: sliderArray});

        $('.gallery-carousel_wrapper').html(slide);


        var app = {
            data: {
                "items": sliderArray,
                "mapDefaults": {
                    "lat": 48.603847242800214,
                    "lng": 31.53074453124998,
                    "zoom": 6
                }
            },
            events: {
                'click@.left': 'onSlideLeft',
                'click@.right': 'onSlideRight'
            },
            markers: [],
            templates: {
                row: 'tmpl_store_item'
            },
            init: function () {
                app.$el = $('#main-content');
                app.$ = function (selector) {
                    return app.$el.find(selector);
                }
                _.each(app.events, function (handler, selector) {
                    selector = selector.split('@');
                    if (!_.isFunction(app[handler])) return;
                    app.$el.on(selector[0], selector[1], function () {
                        app[handler].apply(app, arguments);
                    });
                });
                _.each(app.templates, function (id, name) {
                    app.templates[name] = _.template($('#' + id).html());
                });
                app.createMap();
                app.renderMarkers();
            },

            createMap: function () {
                var mapOptions = {
                    center: new google.maps.LatLng(app.data.mapDefaults.lat, app.data.mapDefaults.lng),
                    zoom: app.data.mapDefaults.zoom,
                    minZoom: 5,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                app.map = new google.maps.Map(
                    document.getElementById("map_canvas"),
                    mapOptions
                );

                var styles = [{"featureType":"all","elementType":"geometry.fill","stylers":[{"weight":"2.00"}]},{"featureType":"all","elementType":"geometry.stroke","stylers":[{"color":"#9c9c9c"}]},{"featureType":"all","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#eeeeee"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#7b7b7b"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#c8d7d4"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#070707"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]}]

                app.map.setOptions({styles: styles});

                app.geocoder = new google.maps.Geocoder();
                app.$('.thumbnail').fancybox({
                    helpers: {
                        title: {
                            type: 'inside',
                            position: 'top'
                        }
                    },
                    padding: [5, 15, 15, 15]
                });
            },

            renderMarkers: function () {
                var that = this, $container = this.$('#store-slider ul');
                app.$strip = $container;
                $container.empty();
                app.clearMarkers();
                _.each(this.data.items, function (i, index) {
                    var marker;
                    $container.append(app.templates.row({
                        item: i,
                        index: index
                    }))
                    marker = new google.maps.Marker({
                        map: app.map,
                        position: new google.maps.LatLng(i.lat, i.lng),
                        draggable: false,
                        title: i.city + ', ' + i.address + ', ' + i.phone,
                        icon: {
                            url: "/assets/img/map-marker.png",
                            scaledSize: new google.maps.Size(36, 52)
                        }
                    });
                    google.maps.event.addListener(marker, 'click', function () {
                        console.log('CLICK MARKER', i, index);
                        var $slide = $('#map_slide_' + index),
                            width = that.$strip.width(),
                            frameWidth = that.$strip.parent().width(),
                            left = -320 * index;
                        if (left >= 0) left = 0;
                        if (frameWidth + 20 >= width + left) left = frameWidth - width + 20;
                        that.sliderLeft = left;
                        that.$strip.css('left', left + 'px');
                        $slide.addClass('pulsate');
                        setTimeout(function () {
                            $slide.removeClass('pulsate');
                        }, 4000);
                    });
                    app.markers.push(marker);
                });
                $container.css('width', (320 * this.data.items.length) + 'px');
                app.refreshMap();
            },

            onResetMap: function () {
                var center = new google.maps.LatLng(app.data.mapDefaults.lat, app.data.mapDefaults.lng);
                app.map.setCenter(center);
                app.map.setZoom(app.data.mapDefaults.zoom);
            },

            clearMarkers: function () {
                _.each(app.markers, function (m) {
                    m.setMap(null);
                });
                app.markers = [];
            },

            refreshMap: function () {
                google.maps.event.trigger(app.map, 'resize');
            },

            onSlideLeft: function (e) {
                e.preventDefault();
                var left = this.sliderLeft || 0;
                left = left + 320;
                if (left >= 0) left = 0;
                this.sliderLeft = left;
                this.$strip.css('left', left + 'px');
            },

            onSlideRight: function (e) {
                e.preventDefault();
                var left = this.sliderLeft || 0,
                    width = this.$strip.width(),
                    frameWidth = this.$strip.parent().width(),
                    newLeft;
                newLeft = left - 320;
                if (frameWidth + 20 >= width + newLeft) newLeft = frameWidth - width + 20;
                this.sliderLeft = newLeft;
                this.$strip.css('left', newLeft + 'px');
            }

        };

        window.shopMap = app;
        $(app.init);

    }
})();