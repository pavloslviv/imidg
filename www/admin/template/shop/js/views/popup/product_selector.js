/**
 * Created by Sergiy on 16.10.13.
 */
(function (sr) {
    sr.classes.views.ProductSelector = sr.classes.views.Popup.extend({

        events: _.extend({},sr.classes.views.Popup.prototype.events,{
            'click .select-product-trigger': 'onProductSelected',
            'submit #product-selector-search': 'onProductSearch'
        }),

        template: sr.core.loadTemplate('tmpl_product_selector'),
        initialize: function(initObj){
            _.bindAll(this,'destroy','show','hide');
            /*this.collection = sr.data.productSelectorList;
            if(!this.collection) {
                this.collection = sr.data.productSelectorList = new sr.classes.collections.Products();
            }*/
            this.collection = new sr.classes.collections.Products();
            this.render();
        },

        render: function(){
            this.$el.html(this.template({
                list: this.collection,
                query: this.searchQuery
            }));
            return this;
        },

        onProductSearch: function(e){
            var that = this;
            e.preventDefault();
            this.searchQuery = jQuery.trim(this.$('#productTitleInput').val());
            this.toggleSpinner(true);
            this.collection.search(this.searchQuery,function(err){
                that.toggleSpinner(false);
                if(err) return;
                that.render();
            });
        },

        onProductSelected: function(e){
            e.preventDefault();
            var $link = $(e.currentTarget),
                productId = $link.data('prod'),
                modId = $link.data('mod'),
                product = this.collection.get(productId);
            if(modId){
                product = product.get('modifications').get(modId);
            }
            if(product){
                this.trigger('selected',product);
            }
        },

        onCreate: function(){
            this.listenTo(this.collection, 'add remove', this.render);
            this.listenTo(this.collection, 'request', function(){
                this.toggleSpinner(true);
            });
             this.listenTo(this.collection, 'response', function(){
                this.toggleSpinner(false);
            });
        },
        onDestroy: function(){

        }
    });
})(SerenityShop);