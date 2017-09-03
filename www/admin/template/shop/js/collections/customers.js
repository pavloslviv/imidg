/**
 * Created by Sergiy on 14.10.13.
 */
(function () {
    (function (sr) {
        sr.classes.collections.Customers = sr.classes.collections.Base.extend({
            comparator: function(c){
                if(c.id==-1){
                    return 0;
                }
                return (c.get('name') || c.get('mail')).toLocaleLowerCase();
            },
            model: sr.classes.models.Customer,
            loaded: false,
            initialize: function(){
                this.filter = {
                    status: 'new'
                }
            },
            fetch: function(cb){
                var that = this;
                sr.core.apiCall('customers','list','POST',{filter: this.filter},function(err,r){
                    if(!err){
                        that.reset(r.customers,{parse: true});
                        that.loaded = true;
                    }
                    if(cb) cb.call(that,err);
                });
            }
        });
    })(SerenityShop);
})();