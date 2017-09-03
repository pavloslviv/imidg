/**
 * Created by Sergiy on 14.10.13.
 */
(function () {
    (function (sr) {
        sr.classes.collections.CallbackItems = sr.classes.collections.Base.extend({
            model: sr.classes.models.OrderItem,
            loaded: false,
            fetch: function(cb){

            },
            addItem: function(productId,qty,cb){
                var that = this,
                    data = {
                        order_id: this.parentModel.id,
                        product_id: productId
                    };
                this.trigger('request',this);
                sr.core.apiCall('shop_orders','item_add','POST',data,function(err,r){
                    var model;
                    if(!err){
                        model = new that.model(r.order_item,{parse: true});
                        that.add(model);
                        that.loaded = true;

                        that.trigger('response',that);
                    }
                    if(cb) cb.call(that,err,model);
                });
            }
        });
    })(SerenityShop);
})();