/**
 * Created by Sergiy on 14.10.13.
 */
(function (sr) {
    sr.classes.models.Customer = sr.classes.models.Base.extend({
        idAttribute: 'id',
        loaded: false,
        numericAttributes: ['id'],
        parse: function(data){
            _.each(this.numericAttributes,function(attr){
                if(typeof data[attr] == "undefined") return;
                data[attr]=data[attr]*1;
            });
            return data;
        },
        fetch: function(cb){
            var that = this;
            sr.core.apiCall('customers','edit','GET',{id: this.id},function(err,r){
                if(!err){
                    that.set(that.parse(r.customer));
                    that.loaded = true;
                }
                if(cb) cb.call(that,err);
            });
        },

        save: function(data,cb){
            var that = this;
            sr.core.apiCall('customers','save','POST',{id: this.id, customer: data},function(err,r){
                if(err || !r.success){
                    if(cb) cb.call(that,true);
                    return;
                }
                that.set(that.parse(r.customer));
                that.loaded = true;
                if(cb) cb.call(that,err);
            });
        }
    });
})(SerenityShop);