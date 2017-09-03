/**
 * Created by Sergiy on 14.10.13.
 */
(function () {
    (function (sr) {
        sr.classes.collections.Products = sr.classes.collections.Base.extend({
            model: sr.classes.models.Product,
            loaded: false,
            comparator: 'order',
            initialize: function(){
                this.filterData = {};
            },
            fetch: function(cb,simple){
                var that = this;
                this.trigger('request',this);
                this.pagesCount = 0;
                sr.core.apiCall('shop_products','list','GET',{filter: this.filterData, simple: !!simple},function(err,r){
                    if(!err){
                        that.set(r.products,{parse: true});
                        that.loaded = true;
                        that.trigger('response',that);
                    }
                    if(cb) cb.call(that,err);
                });
            },
            fetchPage: function(number,cb){
                var that = this;
                this.trigger('request',this);
                sr.core.apiCall('shop_products','list','GET',{filter: this.filterData, page: number},function(err,r){
                    if(!err){
                        that.reset(r.products,{parse: true});
                        that.currentPage = r.currentPage;
                        that.pagesCount = r.pagesCount;
                        that.loaded = true;
                        that.trigger('response',that);
                    }
                    if(cb) cb.call(that,err);
                });
            },
            search: function(query,cb){
                var that = this;
                this.trigger('request',this);
                sr.core.apiCall('shop_products','find','GET',{query: query},function(err,r){
                    if(!err){
                        that.reset(r.products,{parse: true});
                        that.loaded = true;
                        that.trigger('response',that);
                    }
                    if(cb) cb.call(that,err);
                });
            },
            updateItemOrder: function(data,cb){
                var that = this;
                this.trigger('request',this);
                sr.core.apiCall('shop_products','order','POST',{items: data},function(err){
                    if(!err){
                        _.each(data,function(order,id){
                            that.get(id).set({order: order});
                        });
                        that.sort();
                        that.loaded = true;
                        that.trigger('response',that);
                    }
                    if(cb) cb.call(that,err);
                });
            }
        });
        sr.classes.collections.UnprocessedProducts = sr.classes.collections.Products.extend({
            comparator: 'title',
            initialize: function(){
                this.filterData = {};
            }
        });
        sr.classes.collections.DeletedProducts = sr.classes.collections.Products.extend({
            comparator: 'title',
            initialize: function(){
                this.filterData = {};
            }
        });
    })(SerenityShop);
})();