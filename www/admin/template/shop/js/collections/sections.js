/**
 * Created by Sergiy on 14.10.13.
 */
(function () {
    (function (sr) {
        sr.classes.collections.Sections = sr.classes.collections.Base.extend({
            comparator: 'cat_left',
            model: sr.classes.models.Section,
            loaded: false,
            fetch: function(cb){
                var that = this;
                sr.core.apiCall('shop_sections','list','GET',null,function(err,r){
                    if(!err){
                        r.sections = _.filter(r.sections,function(s){ return s.cat_level!='0'; });
                        that.set(r.sections,{parse: true});
                        that.loaded = true;
                    }
                    if(cb) cb.call(that,err);
                });
            },
            getChildren: function(catId,includeParent){
                var parent = this.get(catId);
                var result = new sr.classes.collections.Sections();
                if(includeParent){
                    result.add(parent);
                }
                this.each(function (s) {
                    if(s.get('cat_left')>parent.get('cat_left') && s.get('cat_right')<parent.get('cat_right')){
                        result.add(s);
                    }
                });
                return result;
            }
        });
    })(SerenityShop);
})();