/**
 * Created by Sergiy on 14.10.13.
 */
(function (sr) {
    sr.classes.views.ProductList = sr.classes.views.Base.extend({

        events: {
            /*'click #section-nav a': 'onSectionClick',*/
            'click #add-product-trigger': 'onNewProductClick',
            'click #product-list .delete-trigger': 'onProductDelete',
            'click #save-order-trigger': 'onSaveOrderClick',
            'click .toggle-sorting-trigger': 'onSortingToggle'
        },
        currentPage: 1,
        initialize: function(data){
            var  that = this;

            this.sections = sr.data.sections;
            if(!this.sections){
                this.sections = sr.data.sections = new sr.classes.collections.Sections();
            }
            if(data.page){
                this.currentPage = data.page;
            }
            this.collection = sr.data.products;
            if(!this.collection){
                this.collection = sr.data.products = new sr.classes.collections.Products();
            }
            function onSectionsReady(err){
                var preselectedSection;
                if(err) return;
                that.toggleSpinner(false);
                that.render();
                if(data.sectionId){
                    preselectedSection = that.sections.get(data.sectionId);
                } else if(that.collection.filterData.section_id){
                    preselectedSection = that.sections.get(that.collection.filterData.section_id);
                }
                if(!preselectedSection) {
                    that.selectSection(that.sections.at(0));
                    that.loadSectionProducts();
                } else {
                    that.selectSection(preselectedSection);
                    that.loadSectionProducts();
                }
            }
            if(this.sections.loaded){
                onSectionsReady()
            } else {
                this.toggleSpinner(true);
                this.sections.fetch(onSectionsReady);
            }
        },

        template: sr.core.loadTemplate('tmpl_shop_products'),
        templateRow: sr.core.loadTemplate('tmpl_shop_products_row'),

        render: function(){
            this.$el.html(this.template({
                sections: this.sections
            }));
        },

        renderPagination: function(){
            var $container = this.$('.list-pagination'), dom;
            $container.empty();
            for (var i = 1; i<=this.collection.pagesCount; i++){
                dom = '<a href="#products/'+this.currentSection.id+'/'+i+'">'+i+'</a>';
                $container.append('<li '+(i==this.collection.currentPage ? 'class="active"' : '')+'>'+dom+'</li>');
            }
        },

        renderList: function(isSortMode){
            var that = this,
                list = '';
            this.collection.each(function(p){
                list+=that.templateRow({
                    p: p,
                    sortMode: !!isSortMode
                });
            });
            this.$('#product-list').html(list);
            this.renderPagination();
        },

        onSectionClick: function(e){

            var sectionId = $(e.currentTarget).data('id'),section;
            if(sectionId==0){
                return;
            } else {
                e.preventDefault();
            }
            section = this.sections.get(sectionId);
            this.selectSection(section);
            this.loadSectionProducts();
        },

        selectSection:function(section) {
            var that = this;
            if(!section) return;
            this.currentSection = section;
            this.$('#section-nav li').removeClass('active')
                .find('a[data-id="'+section.id+'"]').parent().addClass('active');
        },
        loadSectionProducts: function(s){
            var that = this,
                section = s || this.currentSection;
            if(!section) return;
            this.toggleSpinner(true);
            this.collection.reset([]);
            this.collection.filterData = {
                section_id: section.id
            };
            this.collection.fetchPage(this.currentPage,function(){
                that.toggleSpinner(false);
                that.renderList();
            });
        },

        onNewProductClick: function(e){
            e.preventDefault();
            if(!this.currentSection) return;
            var that = this,
                newProduct = new sr.classes.models.Product();
            this.toggleSpinner(true);
            newProduct.save({section_id: this.currentSection.id},function(err){
                if(err) return;
                that.collection.add(newProduct);
                sr.core.goTo('product/'+newProduct.id);
            });
        },

        onProductDelete: function(e){
            var $row = $(e.currentTarget).closest('tr'),
                productId = $row.data('id'),
                modId = $row.data('modification'),
                product;
            if(modId){
                product = this.collection.get(productId).get('modifications').get(modId);
            } else {
                product = this.collection.get(productId);
                $row= this.$('#product-list tr[data-id="'+productId+'"]');
            }
            if(!product) return;
            $row.hide();
            product.remove(function(err){
                if(err){
                    $row.show();
                } else {
                    $row.remove();
                }
            });
        },

        onSaveOrderClick: function(e){
            var that = this,
                data = {};
            if(e) e.preventDefault();
            this.toggleSpinner(true);
            this.$('#product-list tr').each(function(){
                var $row = $(this),
                    id = $row.data('id'),
                    order = parseInt($row.find('input').val());
                data[id]= _.isNaN(order) ? 0 : order;
            });
            this.collection.updateItemOrder(data,function(err){
                if(!err) that.loadSectionProducts();
            });
        },

        onSortingToggle: function(e){
            var that = this,
                $button = $(e.currentTarget),
                $list;
            if(!$button.hasClass('sort-on')){
                $button
                    .addClass('sort-on')
                    .html('<i class="glyphicon glyphicon-floppy-disk"></i> Сохранить порядок');
                this.toggleSpinner(true)
                this.collection.reset([]);
                this.sortMode = true;
                this.collection.fetch(function(){
                    that.renderList(true);
                    that.$('#product-list').sortable();
                    that.toggleSpinner(false);
                },true);

                this.$('.save-order-trigger').hide();
            } else {
                this.toggleSpinner(true);
                $list = this.$('#product-list');
                $list.sortable('destroy');
                $list.children('tr').each(function(index){
                    var $row = $(this);
                    $row.find('input').val((index+1)*10);
                });
                $button
                    .removeClass('sort-on')
                    .html('<i class="glyphicon glyphicon-sort"></i> Режим сортировки');
                this.$('.save-order-trigger').show();
                this.onSaveOrderClick();
            }
        },

        onCreate: function(){

        },
        onDestroy: function(){

        }
    });
})(SerenityShop);