/**
 * Created by Sergiy on 14.10.13.
 */
(function (sr) {
    sr.classes.views.CallbackDetails = sr.classes.views.Base.extend({

        events: {
            'click #save-order-trigger': 'onSave',
            'click #reset-order-trigger': 'onReset',
            'click #add-product-popup-trigger': 'onAddProductPopup',
            'click #reset-trigger': 'onAddProductPopup',
            'change #inputShipment': 'onShipmentMethodChange',
            'change #inputPayment': 'onPaymentMethodChange',
            'change #inputStatus': 'onStatusChange',
            'click .delete-item-trigger': 'onItemRemove',
            'click .save-item-trigger': 'onItemSave',
            'keyup #order-items-container input': 'onItemChange',
            'change #order-items-container input': 'onItemChange',
            //'click #send-payment-trigger': 'onSendPayment',
            'typeahead:selected #inputCustomerAccount': 'onCustomerSelected',
            'submit #linkOrderToCustomer': 'onLinkOrderToCustomer'

        },

        template: sr.core.loadTemplate('tmpl_shop_callback_details'),
        templateItems: sr.core.loadTemplate('tmpl_shop_callback_details_items'),
        templateShipment: sr.core.loadTemplate('tmpl_shipment_form'),
        templatePayment: sr.core.loadTemplate('tmpl_payment_form'),

        initialize: function(data){
            var  that = this;
            _.bindAll(this,'render'/*'renderItems'*/);
            /*if(sr.data.callbacks){
                this.model = sr.data.callbacks.get(data.id);
            }*/
            console.log(data);
            if(!this.model){
                this.model = new sr.classes.models.Callback({id: data.id});
            }
            function onModelReady(err){
                if(err) return;
                //that.listenTo(that.model.get('items'),'add remove',that.renderItems);
                that.render();
            }
            if(this.model.loaded){
                onModelReady();
            } else {
                this.model.fetch(onModelReady);
            }
        },

        render: function(){
            console.log(this.model);
            this.$el.html(this.template({
                o: this.model
            }));
            //this.renderItems();
            //this.renderShipment();
            //this.renderPayment();
            //this.toggleSpinner(false);
            //this.initTypeahead();
        },
        renderItems: function(){
            this.$('#callback-items-container').html(this.templateItems({
                items: this.model.get('items'),
                order: this.model
            }));
            this.$('#orderTotal').html(sr.helpers.formatPrice(this.model.get('total'))+' грн');
            this.toggleSpinner(false);
        },

        renderShipment: function(){
            this.$('#shipment-container').html(this.templateShipment({
                method: this.model.get('shipment'),
                data: this.model.get('shipment_data')
            }));
        },
        renderPayment: function(){
            this.$('#payment-container').html(this.templatePayment({
                method: this.model.get('payment'),
                data: this.model.get('payment_data'),
                uniquid: this.model.get('uniquid')
            }));
        },

        initTypeahead: function () {
            var $input = this.$('#inputCustomerAccount');
            if(!$input.length) return;
            $input.typeahead({
                    minLength: 3,
                    highlight: true
                },
                {
                    name: 'account-dataset',
                    source: function (query,cb) {
                        console.log('SEARCH',arguments);
                        sr.core.apiCall('customers','search','GET',{query: query},function(err,r){
                            if(err){
                                alert('Ошибка поиска клиентов!');
                                return;
                            }
                            if(cb) cb(r.customers);
                        });
                    },
                    displayKey: function(item){
                        return item.name;
                    },
                    templates: {
                        suggestion: function (item) {
                            return '<p><strong>'+item.name+'</strong><br/>'+item.mail+' '+item.phone+'</p>';
                        }
                    }
                });
        },

        onCustomerSelected: function (e,item,datasetName) {
            this.$('#selectedAcountInfo').html('<p><strong>'+item.name+'</strong><br/>'+item.mail+' '+item.phone+'</p>');
            this.$('#linkOrderToCustomer button').removeAttr('disabled');
            this.$('#linkOrderToCustomer').data('selectedCustomer',item);
        },

        onLinkOrderToCustomer: function (e) {
            e.preventDefault();
            var customer = this.$('#linkOrderToCustomer').data('selectedCustomer');
            if(!customer) return;
            this.model.changeCustomer(customer.id,this.render);
        },

        onShipmentMethodChange: function(e){
            var $select = $(e.currentTarget);
            this.model.set({shipment: $select.val()});
            this.renderShipment();
        },

        onPaymentMethodChange: function(e){
            var $select = $(e.currentTarget);
            this.model.set({payment: $select.val()});
            this.renderPayment();
        },

        onStatusChange: function(e){
            var $select = $(e.currentTarget);
            if($select.val()==this.model.get('status')) return;
            if(!confirm('Сменить статус?')) {
                $select.find('option:selected').removeAttr('selected');
                $select.find('option[value="'+this.model.get('status')+'"]').attr('selected','selected');
                return;
            }
            this.toggleSpinner(true);
            this.model.setStatus($select.val(),this.render);
        },

        onSave: function(e){
            e.preventDefault();
            this.saveOrder();
        },

        saveOrder: function(cb){
            var that = this,
                data;
            this.toggleSpinner(true);

            this.readShipment();
            data = {
                payment: this.model.get('payment'),
                shipment: this.model.get('shipment'),
                shipment_data: this.model.get('shipment_data')
            }
            this.$('#main-form .form-control').each(function(){
                var $input = $(this);
                data[$input.attr('name')] = $input.val();
            });
            this.model.save(data,function(err){
                if(err) return;
                if(_.isFunction(cb)) {
                    cb(err);
                } else {
                    that.render();
                    that.toggleSpinner(false);
                }
            });
        },

        onSendPayment: function(){
            var that = this;
            if(!confirm('Отправить клиенту ссылку на оплату заказа?')) return;
            this.saveOrder(function(err){
                that.model.sendPayment(function(err){
                    that.render();
                });
            });
        },
        
        readShipment: function(){
            var currentData = this.model.get('shipment_data'),
                data = {};
            this.$('#shipment-container .form-control').each(function(){
                var $input = $(this);
                data[$input.attr('name')] = $input.val();
            });
            this.model.set({shipment_data: _.extend(currentData,data)});
        },

        onReset: function(e){
            this.toggleSpinner(true);
            this.model.fetch(this.render);
        },

        onAddProductPopup: function(e){
            e.preventDefault();
            var popup = new sr.classes.views.ProductSelector();
            this.listenTo(popup,'selected',function(product){
                popup.hide();
                this.onNewProductSelected(product);
            });
            popup.show().onCreate();
        },

        onNewProductSelected: function(product){
            var that = this,
                items = this.model.get('items'),
                duplicate = items.findWhere({product_id: product.id});
            if(duplicate) {
                if(confirm('Данный товар уже есть в заказе. Увеличить его количество на 1?')){
                    duplicate.set({qty: duplicate.get('qty')+1});
                    duplicate.save();
                }
                return;
            }
            this.model.get('items').addItem(product.id);
        },

        onItemRemove: function(e){
            e.preventDefault();
            var that = this,
                $row = $(e.currentTarget).closest('tr'),
                itemId = $row.data('id'),
                items = this.model.get('items'),
                item = items.get(itemId);
            if(!item) return;
            if(!confirm('Удалить '+item.get('title')+' из заказа?')) return;
            item.set({qty: 0});
            item.save();
            this.toggleSpinner(true);
        },

        onItemSave: function(e){
            e.preventDefault();
            var that = this,
                $link = $(e.currentTarget),
                $row = $link.closest('tr'),
                itemId = $row.data('id'),
                items = this.model.get('items'),
                item = items.get(itemId),
                $inputs = $row.find('input'),
                data = {};
            if(!item) return;
            if($link.hasClass('disabled')) return;
            this.toggleSpinner(true);
            $inputs.each(function(){
                var $input = $(this);
                data[$input.attr('name')] = $input.val().replace(',','.');
            });
            item.set(data);
            item.save(function(err){
                that.toggleButtonState($link,false);
                that.toggleSpinner(false);
                that.$('#orderTotal').html(sr.helpers.formatPrice(that.model.get('total'))+' грн');
            });
        },

        onItemChange: function(e){
            this.toggleButtonState($(e.currentTarget).closest('tr').find('.save-item-trigger'),true);
        },

        toggleButtonState: function($button,isEnabled){
            $button.toggleClass('disabled',!isEnabled);
            if(isEnabled){
                $button.find('.glyphicon')
                    .removeClass('glyphicon-floppy-saved')
                    .addClass('glyphicon-floppy-save');
            } else {
                $button.find('.glyphicon')
                    .removeClass('glyphicon-floppy-save')
                    .addClass('glyphicon-floppy-saved');
            }

        },

        onCreate: function(e){
        },
        onDestroy: function(e){
        }
    });
})(SerenityShop);