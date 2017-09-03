/**
 * Created by Sergiy on 31.12.13.
 */
(function () {
    var app = {
        data: {"items":[],"mapDefaults":{"lat":48.603847242800214,"lng":31.53074453124998,"zoom":6}},
        events: {
            'click@#reset-trigger': 'onResetAll',
            'click@#save-trigger': 'onSaveAll',
            'click@#fix-map-trigger': 'onFixMap',
            'click@#reset-map-trigger': 'onResetMap',
            'click@#find-address-trigger': 'onFindAddress',
            'click@#save-address-trigger': 'onSaveForm',
            'click@#reset-address-trigger': 'onResetForm',
            'keyup@#address-list input': 'onItemChange',
            'click@#address-list .add-image-trigger': 'onAddImage',
            'click@#address-list .remove-item-trigger': 'onItemRemove'
        },
        markers: [],
        templates: {
            row: 'tmpl_address_row'
        },
        init: function () {
            app.$el = $('#main-content');
            app.$ = function(selector) {
                return app.$el.find(selector);
            }
            _.each(app.events, function (handler, selector) {
                selector = selector.split('@');
                if (!_.isFunction(app[handler])) return;
                app.$el.on(selector[0], selector[1], function () {
                    app[handler].apply(app, arguments);
                });
            });
            _.each(app.templates,function(id,name){
                app.templates[name] = _.template($('#'+id).html());
            });
            app.createMap();
            app.renderMarkers();
        },

        createMap: function () {
            var mapOptions = {
                center: new google.maps.LatLng(app.data.mapDefaults.lat, app.data.mapDefaults.lng),
                zoom: app.data.mapDefaults.zoom,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            app.map = new google.maps.Map(
                document.getElementById("map_canvas"),
                mapOptions
            );
            app.geocoder = new google.maps.Geocoder();
        },

        renderMarkers: function(){
            var $container = this.$('#address-list');
            $container.empty();
            app.clearMarkers();
            _.each(this.data.items,function(i,index){
                $container.append(app.templates.row({
                    item: i,
                    index: index
                }))
                app.markers.push(new google.maps.Marker({
                    map: app.map,
                    position: new google.maps.LatLng(i.lat, i.lng),
                    draggable: false,
                    title: i.city+', '+ i.address+', '+ i.phone
                }));
            });
            app.refreshMap();
        },

        onResetAll: function(){
            window.location.reload();
        },

        onSaveAll: function(){
            var data;
            app.data.items =_.sortBy(app.data.items,function(i){
                return parseInt(i.order);
            });
            data = {
                section: 'shop',
                name: 'map',
                value: JSON.stringify(app.data)
            }
            $.post('index.php?com=settings&action=save', data, function (r) {
                if (r.result != 'success') {
                    alert(r.message ? r.message : 'Ошибка сохранения!');
                    return;
                }
                window.location.reload();

            })
        },

        onFixMap: function(){
            var center = app.map.getCenter()
            app.data.mapDefaults = {
                lat: center.lat(),
                lng: center.lng(),
                zoom: app.map.getZoom()
            }
        },
        onResetMap: function(){
            var center = new google.maps.LatLng(app.data.mapDefaults.lat, app.data.mapDefaults.lng);
            app.map.setCenter(center);
            app.map.setZoom(app.data.mapDefaults.zoom);
        },

        onFindAddress: function(){
            var $address = $('#new-search-address'),
                address = jQuery.trim($address.val());
            $address.parent().toggleClass('has-error',!address);
            if(!address) return;
            app.geocodeAddress(address,function(location){
                app.onAddressFound(location,address)
            });
        },
        onAddressFound: function(location,address){
            if(app.newMarker){
                app.newMarker.setMap(null);
                delete app.newMarker;
            }
            app.map.setCenter(location);
            app.map.setZoom(17);
            app.newMarker = new google.maps.Marker({
                map: app.map,
                position: location,
                draggable: true,
                animation: google.maps.Animation.DROP,
                title: 'Новый маркер: '+address,
                icon: '/lib/images/gmap_marker_green.png'
            });
        },

        onSaveForm: function(){
            if(!app.newMarker){
                alert('Маркер не установлен!');
                return;
            }
            var $city = $('#new-city'),
                $address = $('#new-address'),
                newItem = {
                    city: jQuery.trim($city.val()),
                    address: jQuery.trim($address.val()),
                    order: jQuery.trim($('#new-order').val()),
                    phone: jQuery.trim($('#new-phone').val())
                },
                markerPosition = app.newMarker.getPosition();
            $address.parent().toggleClass('has-error', !newItem.address);
            $city.parent().toggleClass('has-error',!newItem.city);
            if(!newItem.address || !newItem.city) return;
            newItem.lat = markerPosition.lat();
            newItem.lng = markerPosition.lng();
            app.data.items.push(newItem);
            app.onResetForm();
            app.renderMarkers();
        },

        onResetForm: function(){
            if(app.newMarker){
                app.newMarker.setMap(null);
                delete app.newMarker;
            }
            this.$('#new-item input').val('').parent().removeClass('has-error');
        },

        onItemChange: function(e){
            var $input = $(e.currentTarget),
                $row = $input.closest('tr');
            app.data.items[$row.data('index')][$input.attr('name')]=jQuery.trim($input.val());
        },
        onItemRemove: function(e){
            var $row = $(e.currentTarget).closest('tr');
            if(!confirm('Удалить маркер?')) return;
            app.data.items.splice($row.data('index'),1);
            app.renderMarkers();
        },

        onAddImage: function(e){
            e.preventDefault();
            var that = this,
                $row = $(e.currentTarget).closest('tr');
            app.data.items[$row.data('index')];
            window.KCFinder = {};
            window.KCFinder.callBack = function(url) {
                app.data.items[$row.data('index')].image = url;
                window.KCFinder = null;
                app.renderMarkers();
            };
            window.open('/lib/ckeditor/plugins/kcfinder/browse.php?type=images&dir=images/stores', 'kcfinder_single',"width=600,height=480");
        },

        clearMarkers: function(){
            _.each(app.markers,function(m){
                m.setMap(null);
            });
            app.markers = [];
        },

        refreshMap: function(){
            google.maps.event.trigger(app.map, 'resize');
        },

        geocodeAddress: function (address,cb) {
            app.geocoder.geocode({
                'address': address,
                'region': 'UA'
            }, function (results, status) {
                console.log('GEOCODER',arguments);
                if (status == google.maps.GeocoderStatus.OK) {
                    cb.call(app,results[0].geometry.location);
                } else if(status == google.maps.GeocoderStatus.ZERO_RESULTS){
                    alert("Адрес не найден, попробуйте задать менее точный адрес.");
                } else {
                    alert("Ошибка поиска адреса: " + status);
                }
            });
        }

    }
    window.ShopMap = app;
    $(app.init);
})();