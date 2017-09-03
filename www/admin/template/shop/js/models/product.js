/**
 * Created by Sergiy on 14.10.13.
 */
(function (sr) {
    sr.classes.models.Product = sr.classes.models.Base.extend({
        idAttribute: 'id',
        comparator: 'order',
        defaults: {
            id: 0,
            order: 0,
            price: "0.00",
            stock: 0,
            title: "",
            description: "",
            active: 0,
            home: 0,
            new: 0
        },
        loaded: false,
        numericAttributes: ['id','section_id','parent_id','order','stock','active','home','new','hit','local_brand','sale'],
        initialize: function(){
            if(!this.attributes.modifications || !this.attributes.modifications.model){
                this.attributes.modifications = new sr.classes.collections.Products();
            }
        },
        parse: function(data){
            _.each(this.numericAttributes,function(attr){
                if(typeof data[attr] == "undefined") return;
                data[attr]=data[attr]*1;
            });
            if(data.section){
                if(this.attributes.section){
                    this.attributes.section.set(this.attributes.section.parse(data.section));
                } else {
                    this.attributes.section = new sr.classes.models.Section(data.section);
                }
                delete data.section;
            }
            if(data.top_section){
                if(this.attributes.top_section){
                    this.attributes.top_section.set(this.attributes.top_section.parse(data.top_section));
                } else {
                    this.attributes.top_section = new sr.classes.models.Section(data.top_section);
                }
                delete data.top_section;
            }
            if(data.modifications){
                if(this.attributes.modifications){
                    this.attributes.modifications.set(data.modifications,{parse: true});
                } else {
                    this.attributes.modifications = new sr.classes.collections.Products(data.modifications,{parse: true});
                }
                delete data.modifications;
            }

            return data;
        },

        fetch: function(cb){
            var that = this;
            sr.core.apiCall('shop_products','get','GET',{id: this.id},function(err,r){
                if(!err){
                    that.set(that.parse(r.product));
                    that.get('modifications').each(function (m) {
                        m.loaded = true;
                    });
                    that.loaded = true;
                }
                if(cb) cb.call(that,err);
            });
        },

        save: function(data,cb){
            var that = this,
                reqData;
            reqData = _.extend();
            data.id = this.id;
            if(data.price){
                data.price = data.price.replace(',','.');
            }
            if(data.sale_price){
                data.sale_price = data.sale_price.replace(',','.');
            }
            sr.core.apiCall('shop_products','save','POST',data,function(err,r){
                if(!err){
                    that.set(that.parse(r.product));
                    that.get('modifications').each(function(m){
                        if(m.get('active')>that.get('active')){
                            m.save({active: that.get('active')});
                        }
                    });
                    that.loaded = true;
                }
                if(cb) cb.call(that,err);
            });
        },

        saveOptions: function(options,cb){
            var that = this,
                data = {
                    id: this.id,
                    options: options
                };
            sr.core.apiCall('shop_products','save_options','POST',data,function(err,r){
                if(!err){
                    that.set({options: r.options});
                }
                if(cb) cb.call(that,err);
            });
        },
        uploadImage: function(file,cb,progressCallback){
            var that = this,
                data = {
                    id: this.id,
                    file: file
                };
            sr.core.apiCall('shop_products','thumb','POST',data,function(err,r){
                if(!err){
                    that.set({image: r.image});
                }
                if(cb) cb.call(that,err);
            },function(e){
                if(progressCallback) progressCallback.call(that);
            });
        },
        deleteImage: function(cb){
            var that = this,
                data = {
                    id: this.id
                };
            sr.core.apiCall('shop_products','remove_thumb','POST',data,function(err,r){
                if(!err){
                    that.set({image: ''});
                }
                if(cb) cb.call(that,err);
            });
        },

        remove: function(cb){
            var that = this;
            sr.core.apiCall('shop_products','delete','POST',{id: this.id},function(err,r){
                if(!err){
                    that.clear();
                    if(that.collection){
                        that.collection.remove(that);
                    }
                    that.loaded = true;
                }
                if(cb) cb.call(that,err);
            });
        }

    });
})(SerenityShop);