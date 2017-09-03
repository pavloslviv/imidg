/**
 * Created by Sergiy on 14.10.13.
 */
(function (sr) {
    sr.classes.views.UnprocessedProductList = sr.classes.views.ProductList.extend({

        events: {
            'click #product-list .delete-trigger': 'onProductDelete',
            'click #product-list .hide-trigger': 'onProductHide',
            'click #product-list .attach-trigger': 'onAttachClick',
            'change #product-list-file-input': 'onFileSelected',
            'keyup #title-filter-input': 'onFilterType',
            'change .select-product': 'onSelectChange',
            'click #group-items-trigger': 'onGroupClick',
            'click #prev-page': 'onPrevClick',
            'click #next-page': 'onNextClick'
        },

        initialize: function(data){
            var  that = this, removed;
            this.selected = [];
            this.sections = sr.data.sections;
            if(!this.sections){
                this.sections = sr.data.sections = new sr.classes.collections.Sections();
            }
            this.collection = sr.data.unprocessed;
            if(!this.collection){
                this.collection = sr.data.unprocessed = new sr.classes.collections.UnprocessedProducts();
            } else {
                removed = this.collection.filter(function(p){
                    return p.get('active')!==-1;
                });
                console.log('removing ',removed.length);
                this.collection.remove(removed);
            }

            function onSectionsReady(err){
                var preselectedSection;
                if(err) return;
                that.toggleSpinner(false);
                that.render();
                if(that.collection.loaded){
                    that.renderList();
                } else {
                    that.loadProducts();
                }
            }
            if(this.sections.loaded){
                onSectionsReady()
            } else {
                this.toggleSpinner(true);
                this.sections.fetch(onSectionsReady);
            }
        },

        template: sr.core.loadTemplate('tmpl_shop_unprocessed_products'),
        templateRow: sr.core.loadTemplate('tmpl_shop_unprocessed_products_row'),

        render: function(){
            var $scrollContainer;
            this.$el.html(this.template({
                sections: this.sections
            }));
            $scrollContainer = this.$('.scroll-container');
            _.delay(function(){
                $scrollContainer.css('height',($(window).height()-$scrollContainer.offset().top-10)+'px');
            },10);
        },

        renderList: function(){
            var that = this,
                list = '',
                items;
            if(this.collection.titleFilter){
                items = this.collection.filter(function(p){
                    return p.get('title').toLocaleLowerCase().indexOf(that.collection.titleFilter.toLocaleLowerCase())!==-1;
                });
                $('#pagination-container').hide();
                $('#title-filter-input').val(this.collection.titleFilter);
            } else {
                if(this.collection.currentOffset>this.collection.length){
                    this.collection.currentOffset = Math.floor(this.collection.length/100);
                }
                items = this.collection.slice(this.collection.currentOffset,this.collection.currentOffset+99);
                $('#pagination-container #page-range').html(
                    (this.collection.currentOffset+1)+' &mdash; '+
                    (this.collection.currentOffset+100)+' из '+this.collection.length
                );
                $('#pagination-container').show();
            }
            _.each(items,function(p){
                list+=that.templateRow({
                    p: p
                });
            });

            this.$('#product-list').html(list);
            this.resetSelection();
        },

        loadProducts: function(){
            var that = this;
            this.toggleSpinner(true);
            this.collection.reset([]);
            if(this.collection.currentOffset === undefined){
                this.collection.currentOffset=0;
            }
            this.collection.filterData = {
                active: -1
            };
            this.collection.fetch(function(){
                that.toggleSpinner(false);
                that.renderList();
            });
        },

        onFileSelected: function(e){
            var that = this,
                $input = $(e.currentTarget),
                file = e.currentTarget.files[0],extension;
            if(!file) return;
            extension = file.name.split('.').pop().toLocaleLowerCase();
            if(extension!=='xml') {
                alert('Файл должен быть в формате XML!');
                return;
            }
            this.toggleSpinner(true);
            sr.core.apiCall('shop_products','import','POST',{file: file},function(err,r){
                $input.val('');
                if(err){
                    that.toggleSpinner(false);
                    alert(r.message);
                    return;
                }
                alert('Импорт прошел успешно. Добавлено '+ r.data.new+'. Обновлено '+ r.data.update+'. Пропущено '+ r.data.skip+'.');
                that.collection.currentOffset=0;
                that.loadProducts();
            });
        },

        onPrevClick: function(){
            if(this.collection.currentOffset==0) return;
            this.collection.currentOffset=this.collection.currentOffset-100;
            this.renderList();
        },
        onNextClick: function(){
            if(this.collection.currentOffset+100>this.collection) return;
            this.collection.currentOffset=this.collection.currentOffset+100;
            this.renderList();
        },

        onSelectChange: function(e){
            var $checkbox = $(e.currentTarget),
                model = this.collection.get($checkbox.val()),
                $row = $checkbox.closest('tr');
            if(!model) return;
            if($checkbox.is(':checked')){
                this.selected.push({
                    model: model,
                    $row: $row
                });
            } else {
                this.selected = _.reject(this.selected,function(o){
                    return o.model.id==model.id;
                });
            }
            this.$('#group-items-trigger').toggleClass('disabled',this.selected.length<2);
        },

        resetSelection: function(){
            _.each(this.selected,function(o){
                _.delay(function(){
                    o.$row.find('input').removeAttr('checked');
                },1)
            });
            this.selected=[];
            this.$('#group-items-trigger').addClass('disabled');
        },

        onGroupClick: function(e){
            var that= this,$rows,items,newModel;

            if($(e.currentTarget).hasClass('disabled')) return;

            $rows = _.pluck(this.selected,'$row');
            items = _.pluck(this.selected,'model');

            if(items.length<2) return;
            this.toggleSpinner(true);
            newModel = new sr.classes.models.Product();
            newModel.save({
                title: sr.helpers.longestCommonSubstring(_.map(items,function(i){
                    return i.get('title');
                })),
                active: -1
            },function updateModifications(err){
                if(err) {
                    that.toggleSpinner(false);
                    return;
                }
                that.collection.add(newModel);
                _.each(items,function(i){
                    var data = {
                        parent_id: newModel.id,
                        title: i.get('title').replace(newModel.get('title'),''),
                        active: -1
                    };
                    i.set(data);
                    i.save(data);
                    newModel.get('modifications').add(i);
                    that.collection.remove(i);
                });
                that.renderList();
                _.delay(function(){
                    that.toggleSpinner(false);
                },10);
            });
        },

        onAttachClick: function(e){
            var that = this,
                $row = $(e.currentTarget).closest('tr'),
                productId = $row.data('id'),
                modId = $row.data('modification'),
                product;
            if(modId){
                product = this.collection.get(productId).get('modifications').get(modId);
            } else {
                product = this.collection.get(productId);
                $row= this.$('#product-list>tr[data-id="'+productId+'"]');
            }
            if(!product) return;
            var popup = new sr.classes.views.ProductSelector();
            this.listenTo(popup,'selected',function(parentProduct){
                popup.hide();
                that.toggleSpinner(true);
                product.save({
                    parent_id: parentProduct.id
                },function(err){
                    var parent;
                    if(err) return;
                    parent = that.collection.get(parentProduct.id);
                    if(parent){
                        parent.get('modifications').add(product);
                    }
                    that.renderList();
                    that.toggleSpinner(false);
                });
            });
            popup.show().onCreate();
        },

        onProductDelete: function(e){
            var that = this,
                $row = $(e.currentTarget).closest('tr'),
                productId = $row.data('id'),
                modId = $row.data('modification'),
                product;
            if(modId){
                product = this.collection.get(productId).get('modifications').get(modId);
            } else {
                product = this.collection.get(productId);
            }
            if(!product) return;
            this.toggleSpinner(true);
            product.remove(function(err){
                that.renderList();
                that.toggleSpinner(false);
            });
        },

        onProductHide: function(e){
            var that = this,
                $row = $(e.currentTarget).closest('tr'),
                productId = $row.data('id'),
                modId = $row.data('modification'),
                product;
            if(modId){
                product = this.collection.get(productId).get('modifications').get(modId);
            } else {
                product = this.collection.get(productId);
                $row= this.$('#product-list>tr[data-id="'+productId+'"]');
            }
            if(!product) return;
            this.toggleSpinner(true);
            product.save({active: -2},function(err){
                if(err){
                    $row.show();
                } else {
                    that.collection.remove(product);
                    product.get('modifications').each(function(m){
                        m.save({active: -2});
                    });
                    that.renderList();
                }
                that.toggleSpinner(false);
            });
        },

        onFilterType: _.debounce(function(e){
            var $input = $(e.currentTarget);
            this.collection.titleFilter=$input.val();
            this.renderList();
        },1000),

        onCreate: function(){

        },
        onDestroy: function(){

        }
    });
})(SerenityShop);