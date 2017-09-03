/**
 * Created by Sergiy on 14.10.13.
 */
(function (sr) {
    sr.classes.views.CustomerDetails = sr.classes.views.Base.extend({

        events: {
            'click #save-order-trigger': 'onSave',
            'click #reset-order-trigger': 'onReset'
        },

        template: sr.core.loadTemplate('tmpl_shop_customer_details'),
        templateItems: sr.core.loadTemplate('tmpl_shop_customer_details_items'),

        initialize: function(data){
            var  that = this;
            _.bindAll(this,'render','renderOrders');
            if(sr.data.customers && data.customerId){
                this.model = sr.data.customers.get(data.customerId);
            }
            if(!this.model){
                this.model = new sr.classes.models.Customer({id: data.customerId});
            }
            if(!this.model.get('orders')){
                this.model.set({
                    orders: new sr.classes.collections.Orders()
                });
                this.model.get('orders').filter = {customer_id: this.model.id};
            }
            this.toggleSpinner(true);
            function onModelReady(err){
                if(err) return;
                that.render();
                if(that.model.id && !that.model.get('orders').loaded){
                    that.model.get('orders').fetch(that.renderOrders);
                }
            }
            if(this.model.loaded){
                onModelReady();
            } else {
                this.model.fetch(onModelReady);
            }
        },

        render: function(){
            this.$el.html(this.template({
                c: this.model
            }));
            this.renderOrders();
            this.toggleSpinner(false);
        },
        renderOrders: function(){
            if(!this.model.get('orders').loaded) return;
            this.$('#order-list-container').html(this.templateItems({
                items: this.model.get('orders'),
                order: this.model
            }));
            this.$('#orderTotal').html(sr.helpers.formatPrice(this.model.get('total'))+' руб');
            this.toggleSpinner(false);
        },

        onSave: function(e){
            e.preventDefault();
            this.saveAccount();
        },

        saveAccount: function(cb){
            var that = this,
                data = {},
                firstSave = !this.model.id;
            this.toggleSpinner(true);

            this.$('#main-form .form-control').each(function(){
                var $input = $(this);
                data[$input.attr('name')] = $input.val();
            });
            this.model.save(data,function(err){
                if(err) {
                    that.toggleSpinner(false);
                    alert('Ошибка сохранения!');
                    return;
                }
                if(_.isFunction(cb)) {
                    cb(err);

                } else {
                    that.render();
                    that.toggleSpinner(false);
                    if(firstSave && sr.data.customers){
                        sr.data.customers.add(that.model);
                        that.model.get('orders').filter = {customer_id: that.model.id};
                    }
                }
            });
        },


        onReset: function(e){
            this.toggleSpinner(true);
            this.model.fetch(this.render);
        },

        onCreate: function(e){
        },
        onDestroy: function(e){
        }
    });
})(SerenityShop);