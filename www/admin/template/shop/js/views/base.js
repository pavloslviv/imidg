/**
 * Created by Sergiy on 16.10.13.
 */
(function (sr) {
    sr.classes.views.Base = Backbone.View.extend({
        render: function(){
            this.$el.html(this.template);
        },

        toggleSpinner: function(flag){
            this.$el.children('.ajax-loader').remove();
            if(flag){
                this.$el.append('<div class="ajax-loader"></div>');
            }
        },

        destroy: function(){
            if(this.onDestroy) this.onDestroy();
            if(this.partials){
                _.each(this.partials,function(p){
                    p.destroy();
                });
            }
            this.remove();
        },

        onCreate: function(){

        },
        onDestroy: function(){

        }
    });
})(SerenityShop);