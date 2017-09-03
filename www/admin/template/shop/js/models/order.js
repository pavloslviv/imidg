/**
 * Created by Sergiy on 14.10.13.
 */
(function (sr) {
    sr.classes.models.Order = sr.classes.models.Base.extend({
        idAttribute: 'id',
        loaded: false,
        numericAttributes: ['id'],
        dateAttributes: ['date'],
        initialize: function(data){

        },
        parse: function(data){
            var that = this;
            if(!data) return;
            _.each(this.numericAttributes,function(attr){
                if(typeof data[attr] == "undefined") return;
                data[attr]=data[attr]*1;
            });
            _.each(this.dateAttributes,function(attr){
                if(typeof data[attr] == "undefined") return;
                data[attr]=moment(data[attr],'X');
            });
            if(data.items){
                if(!this.attributes.items){
                    this.attributes.items = new sr.classes.collections.OrderItems();
                    this.attributes.items.parentModel = this;
                }
                this.attributes.items.reset(data.items,{parse: true});
                delete data.items;
            }
            if(data.customer){
                if(!this.attributes.customer){
                    this.attributes.customer = new sr.classes.models.Customer();
                }
                this.attributes.customer.set(this.attributes.customer.parse(data.customer));
                delete data.customer;
            }
            if(data.payment_data){
                data.payment_data = JSON.parse(data.payment_data);
            }
            if(data.shipment_data){
                data.shipment_data = JSON.parse(data.shipment_data);
            }
            if(data.status){
                data.editable = (data.status=='new' || data.status=='processing' || data.status=='oneClick') ? true : false;
            }
            return data;
        },

        fetch: function(cb){
            var that = this;
            sr.core.apiCall('shop_orders','get','GET',{id: this.id},function(err,r){
                if(!err){
                    that.set(that.parse(r.order));
                    that.loaded = true;
                }
                if(cb) cb.call(that,err);
            });
        },

        save: function(data,cb){
            var that = this;
            data.id = this.id;
            sr.core.apiCall('shop_orders','save','POST',data,function(err,r){
                if(!err){
                    that.set(that.parse(r.order));
                    that.loaded = true;
                }
                if(cb) cb.call(that,err);
            });
        },

        changeCustomer: function(customerId,cb){
            var that = this;
            sr.core.apiCall('shop_orders','reassign_order','POST',{order_id: this.id, customer_id: customerId},function(err,r){
                if(!err){
                    that.set(that.parse(r.order));
                    that.loaded = true;
                }
                if(cb) cb.call(that,err);
            });
        },

        sendPayment: function(cb){
            var that = this;
            sr.core.apiCall('shop_orders','send_payment','POST',{order_id: this.id},function(err,r){
                var pData;
                if(!err){
                    that.set({
                        uniquid: r.data.uniquid,
                        payment_data: JSON.parse(r.data.payment_data)
                    });
                }
                if(cb) cb.call(that,err);
            });
        },

        setStatus: function(status,cb){
            var that = this,
                data= {
                    id: this.id,
                    status: status
                };
            sr.core.apiCall('shop_orders','status','POST',data,function(err,r){
                if(!err){
                    that.set(that.parse(r.order));
                    that.loaded = true;
                }
                if(cb) cb.call(that,err);
            });
        },
        addItem: function(itemId){

        }
    });
})(SerenityShop);