/**
 * Created by Sergiy on 14.10.13.
 */
(function (sr) {
    sr.classes.models.OrderItem = sr.classes.models.Base.extend({
        idAttribute: 'id',
        defaults: {
            id: 0,
            order_id: 0,
            product_id: 0,
            code: '',
            title: '',
            qty: '',
            price: ''
        },
        loaded: false,
        initialize: function(attr){
            this.set(this.parse(attr));
        },
        numericAttributes: ['id','order_id','product_id','qty'],
        parse: function(data){
            if(!data) return data;
            _.each(this.numericAttributes,function(attr){
                if(typeof data[attr] == "undefined") return;
                data[attr]=data[attr]*1;
            });
            return data;
        },

        save: function(cb){
            var that = this,
                data = this.toJSON();
            sr.core.apiCall('shop_orders','item_update','POST',data,function(err,r){
                if(!err){
                    console.log(that.collection, that.collection.parentModel)
                    if(that.collection && that.collection.parentModel){
                        that.collection.parentModel.set({'total': r.total});
                    }
                    if(that.get('qty')==0 && that.collection){
                        that.collection.remove(that);
                    } else {
                        that.set(that.parse(r.order_item));
                        that.loaded = true;
                    }
                }
                if(cb) cb.call(that,err);
            });
        }
    });
})(SerenityShop);