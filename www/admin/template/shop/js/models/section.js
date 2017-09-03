/**
 * Created by Sergiy on 14.10.13.
 */
(function (sr) {
    sr.classes.models.Section = sr.classes.models.Base.extend({
        idAttribute: 'id',
        numericAttributes: ['id','cat_level','cat_left','cat_right'],
        parse: function(data){
            _.each(this.numericAttributes,function(attr){
                if(typeof data[attr] == "undefined") return;
                data[attr]=data[attr]*1;
            });
            return data;
        }
    });
})(SerenityShop);