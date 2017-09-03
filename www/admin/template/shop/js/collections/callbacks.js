/**
 * Created by Sergiy on 14.10.13.
 */
(function () {
    (function (sr) {
        sr.classes.collections.Callbacks = sr.classes.collections.Base.extend({
            model: sr.classes.models.Callback,
            loaded: false,
            comparator: function(o){
                return -o.get('date').format('X');
            },
            initialize: function(){
                this.filter = {
                    status: 1
                }
            },
            fetch: function(cb){
                var that = this;
                sr.core.apiCall('shop_callbacks','list','POST',{filter: this.filter},function(err,r){
                    if(!err){
                        that.set(r.orders,{parse: true});
                        that.loaded = true;
                    }
                    if(cb) cb.call(that,err);
                });
            },
            fetchPage: function(number,cb){
                var that = this;
                this.trigger('request',this);
                sr.core.apiCall('shop_callbacks','list','POST',{filter: this.filter, page: number},function(err,r){
                    if(!err){
                        that.reset(r.orders,{parse: true});
                        that.currentPage = r.currentPage;
                        that.pagesCount = r.pagesCount;
                        that.loaded = true;
                        that.trigger('response',that);
                    }
                    if(cb) cb.call(that,err);
                });
            }
        });
    })(SerenityShop);
})();