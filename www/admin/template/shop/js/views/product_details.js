/**
 * Created by Sergiy on 14.10.13.
 */
(function (sr) {
    sr.classes.views.ProductDetails = sr.classes.views.Base.extend({

        events: {
            'submit #move-product-to-section': 'onSectionFormSubmit',
            'submit #product-detail-form': 'onMainSubmit',
            'change #product-image-input': 'onImageSelected',
            'click .color-select-trigger': 'onColorSampleClick',
            'click #add-modification-trigger': 'onAddModification'
        },

        template: sr.core.loadTemplate('tmpl_shop_product_details'),
        templateNoSection: sr.core.loadTemplate('tmpl_shop_product_details_no_section'),
        templateOption: sr.core.loadTemplate('tmpl_shop_product_option'),

        initialize: function(data){
            var  that = this;
            _.bindAll(this, 'createModificationPartial');
            this.partials = {};
            if(sr.data.products){
                this.model = sr.data.products.get(data.productId);
            }

            if(!this.model && sr.data.unprocessed){
                this.model = sr.data.unprocessed.get(data.productId);
            }

            function onModelReady(){
                if(that.model.get('section').id){
                    that.backLink = '#products/'+that.model.get('section').id;
                    that.loadSections(function onSectionsReady(err){
                        that.render();
                        that.toggleSpinner(false);
                    });
                } else {
                    that.backLink = '#products/0';
                    that.loadSections(function onSectionsReady(err){
                        that.renderNoSection();
                        that.toggleSpinner(false);
                    });
                }
                //
            }
            if(!this.model){
                this.model = new sr.classes.models.Product({id: data.productId});
            }
            if(this.model.loaded){
                onModelReady()
            } else {
                this.toggleSpinner(true);
                this.model.fetch(function(err){
                    if(!err){
                        onModelReady();
                    }
                });
            }
        },

        loadSections: function(onSectionsReady){
            var that = this;
            this.sections = sr.data.sections ;
            if(!this.sections){
                this.sections = sr.data.sections = new sr.classes.collections.Sections();
            }
            if(this.sections.loaded){
                onSectionsReady()
            } else {
                this.toggleSpinner(true);
                this.sections.fetch(onSectionsReady);
            }
        },

        renderNoSection: function(){
            this.$el.html(this.templateNoSection({
                p: this.model,
                sections: this.sections,
                backLink: this.backLink
            }));
        },

        render: function(){
            var that = this;
            this.$el.html(this.template({
                p: this.model,
                optionTmpl: this.templateOption,
                backLink: this.backLink,
                sections: this.sections//.getChildren(this.model.get('top_section').id,true)
            }));
            this.editor = sr.core.createEditor(this.$('#description-editor'));
            this.removeModificationPartials();
            this.model.get('modifications').each(this.createModificationPartial);
            _.delay(function(){
                if(that.forceModificationsSave){
                    delete that.forceModificationsSave;
                    that.$('form.product-modification-form').trigger('submit');
                }
            },10);

        },

        onSectionFormSubmit: function(e){
            var that = this,
                sectionId = $(e.currentTarget).find('select').val();
            e.preventDefault();
            this.model.save({section_id: sectionId},function(err){
                if(err) return;
                that.forceModificationsSave = true;
                that.render();
            });
        },

        onMainSubmit: function(e){
            var $form = $(e.currentTarget),
                $inputs = $form.find('.product-property'),
                $flags = $form.find('.product-flag'),
                data = {
                    options: []
                },
                that = this;
            e.preventDefault();
            $inputs.each(function(){
                var $input = $(this);
                data[$input.attr('name')]=jQuery.trim($input.val());
            });
            $flags.each(function(){
                var $input = $(this);
                data[$input.attr('name')]=$input.is(':checked') ? 1 : 0;
            });

            $form.find('.product-option').each(function(){
                var $input = $(this);
                data.options.push({
                    option_id: $input.data('option_id'),
                    value_id: $input.data('value_id'),
                    value: jQuery.trim($input.val())
                });
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

        onColorSampleClick: function (e) {
            var $button = $(e.currentTarget);
            var popup = new sr.classes.views.ColorSelector({
                onSelected: function(color){
                    $button.find('span').css('background-color','#'+color);
                    $button.closest('.color-picker-container').find('input').val(color);
                    popup.hide();
                }
            });
            popup.show();
        },

        onAddModification: function(e){
            var that = this;
            var mod = new sr.classes.models.Product();
            mod.save({
                parent_id: this.model.id,
                section_id: this.model.get('section').id
            },function(err){
                if(!err) {
                    that.createModificationPartial(mod);
                    that.model.get('modifications').add(mod);
                }
            });
        },
        removeModificationPartials: function(){
            _.each(this.partials,function(p){
                p.destroy();
            });
        },

        createModificationPartial: function(model){
            var partial = new sr.classes.views.partial.Modification({
                model: model
            });
            this.partials['mod_'+model.id]=partial;
            this.$("#modifications-list").append(partial.$el);
        },

        onCreate: function(e){
        },
        onDestroy: function(e){
        }
    });
})(SerenityShop);