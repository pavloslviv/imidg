/**
 * Created by Sergiy on 14.10.13.
 */
(function (sr) {
    sr.classes.views.partial.Modification = sr.classes.views.Base.extend({
        className: 'col-md-6',
        events: {
            'submit .product-modification-form': 'onMainSubmit'
        },

        template: sr.core.loadTemplate('tmpl_shop_product_modification'),
        templateOption: sr.core.loadTemplate('tmpl_shop_product_option'),

        initialize: function(data){
            var  that = this;
            if(this.model.loaded){
                this.render();
            } else {
                this.toggleSpinner(true);
                this.model.fetch(function(err){
                    if(!err){
                        that.render();
                        that.toggleSpinner(false);
                    }
                });
            }
        },

        render: function(){
            var that = this;
            this.$el.html(this.template({
                p: this.model,
                optionTmpl: this.templateOption
            }));
        },

        onMainSubmit: function(e){
            var $form = $(e.currentTarget),
                $inputs = $form.find('.product-property'),
                data = {
                    options: []
                },
                that = this;
            e.preventDefault();
            $inputs.each(function(){
                var $input = $(this);
                data[$input.attr('name')]=jQuery.trim($input.val());
            });

            $form.find('.product-option').each(function(){
                var $input = $(this),
                    option = {
                        option_id: $input.data('option_id'),
                        value_id: $input.data('value_id'),
                        value: jQuery.trim($input.val())
                    };
                if(_.contains(sr.data.modificatorOptions,parseInt(option.option_id))){
                    if(option.value==''){
                        option.value = data.title;
                    } else if(data.title==''){
                        data.title = option.value;
                    }
                }
                data.options.push(option);
            });
            this.toggleSpinner(true);
            this.model.save(data,function(err){
                if(err) return;
                that.render();
                that.toggleSpinner(false);
            });
        },

        onImageSelected: function(e){
            var that = this,
                file = e.currentTarget.files[0];
            if(!file) return;
            this.model.uploadImage(file,function(){
                that.$('.product-thumb img').remove();
                that.$('.product-thumb').append('<img src="/media/product/'+that.model.id+'_small.'+that.model.get('image')+'">');
            },function(){

            });
        },

        onImageDelete: function(e){
            var that = this;
            if(!file) return;
            this.model.deleteImage(function(){

            });
        },

        onCreate: function(e){
        },
        onDestroy: function(e){
        }
    });
})(SerenityShop);