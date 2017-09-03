/**
 * Created by Sergiy on 14.10.13.
 */
(function (sr) {
    sr.classes.views.OrderList = sr.classes.views.Base.extend({

        events: {
            'change #status-filter': 'onStatusFilterChange',
            'click #add-order-trigger': 'onNewOrderClick'
        },

        currentPage: 1,

        initialize: function(data){
            var  that = this;
            _.bindAll(this,'renderList');
            this.collection = sr.data.orders;
            if(!this.collection){
                this.collection = sr.data.orders = new sr.classes.collections.Orders();
            }
            if(data.page){
                this.currentPage = data.page;
            }
            if(data.status){
                if(this.collection.filter.status!=data.status){
                    this.collection.loaded = false;
                }
                this.collection.filter.status = data.status;
            }

            this.render();
            if(this.collection.loaded && this.collection.currentPage==this.currentPage){
                this.renderList();
            } else {
                this.collection.fetchPage(this.currentPage,function(err){
                    if(!err){
                        that.renderList();
                    }
                });
            }
        },

        template: sr.core.loadTemplate('tmpl_shop_order_list'),
        templateRow: sr.core.loadTemplate('tmpl_shop_order_list_row'),

        render: function(){
            this.$el.html(this.template({
                filter: this.collection.filter
            }));
        },

        renderPagination: function(){
            var $container = this.$('.list-pagination'), dom;
            $container.empty();
            for (var i = 1; i<=this.collection.pagesCount; i++){
                dom = '<a href="#orders/'+this.collection.filter.status+'/'+i+'">'+i+'</a>';
                $container.append('<li '+(i==this.collection.currentPage ? 'class="active"' : '')+'>'+dom+'</li>');
            }
        },

        renderList: function(){
            var that = this,
                list = '';
            this.collection.each(function(o){
                list+=that.templateRow({
                    o: o
                });
            });
            this.$('#order-list').html(list);
            this.toggleSpinner(false);
            this.renderPagination();
        },

        onNewOrderClick: function(e){
            e.preventDefault();
            var that = this,
                newOrder = new sr.classes.models.Order();
            this.toggleSpinner(true);
            newOrder.save({date: Math.round(Date.now()/1000)},function(err){
                if(err) return;
                sr.core.goTo('order/'+newOrder.id);
            });
        },

        onStatusFilterChange: function(e){
            var $select = $(e.currentTarget);
            sr.core.goTo('orders/'+$select.val()+'/1');
        },

        onCreate: function(){

        },
        onDestroy: function(){

        }
    });
})(SerenityShop);