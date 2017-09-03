/**
 * Created by Sergiy on 16.10.13.
 */
(function (sr) {
    sr.classes.views.Popup = sr.classes.views.Base.extend({
        events: {
            'hidden.bs.modal':'destroy'
        },

        className: 'modal fade',
        initialize: function(initObj){
            _.bindAll(this,'destroy','show','hide')
        },

        render: function(){
            this.$el.html(this.template);
            return this;
        },

        show: function(){
            this.render();
            this.$el.modal('show');
            return this;
        },

        center: function(){
            var $popup = this.$('.popup'),
                leftMargin = $popup.width()/2,
                topMargin = $popup.height()/2;
            $popup.css('margin-left','-'+leftMargin+'px');
            $popup.css('margin-top','-'+topMargin+'px');
            return this;
        },

        hide: function(){
            this.$el.modal('hide');
            _.delay(this.destroy,300);
        },

        toggleSpinner: function(flag){
            this.$('.ajax-loader').remove();
            if(flag){
                this.$('modal-body').append('<div class="ajax-loader"></div>');
            }
        },

        onCreate: function(){

        },
        onDestroy: function(){

        }
    });
})(SerenityShop);