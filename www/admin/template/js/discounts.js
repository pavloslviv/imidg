/**
 * Created by Sergiy on 31.12.13.
 */
(function () {
    var app = {
        data: [],
        events: {
            'click@#reset-trigger': 'onResetAll',
            'click@#save-trigger': 'onSaveAll',
            'click@#save-item-trigger': 'onSaveForm',
            'click@#reset-item-trigger': 'onResetForm',
            'change@#item-list input': 'onItemChange',
            'click@#item-list .remove-item-trigger': 'onItemRemove'
        },
        templates: {
            row: 'tmpl_address_row'
        },
        init: function () {
            app.$el = $('#main-content');
            app.$ = function(selector) {
                return app.$el.find(selector);
            }
            _.each(app.events, function (handler, selector) {
                selector = selector.split('@');
                if (!_.isFunction(app[handler])) return;
                app.$el.on(selector[0], selector[1], function () {
                    app[handler].apply(app, arguments);
                });
            });
            _.each(app.templates,function(id,name){
                app.templates[name] = _.template($('#'+id).html());
            });
            app.renderList();
        },


        renderList: function(){
            var $container = this.$('#item-list');
            $container.empty();
            _.each(this.data,function(i,index){
                $container.append(app.templates.row({
                    item: i,
                    index: index
                }))
            });
        },

        onResetAll: function(){
            window.location.reload();
        },

        onSaveAll: function(){
            var data;
            app.data =_.sortBy(app.data,function(i){
                return parseInt(i.amount);
            });
            data = {
                section: 'shop',
                name: 'discounts',
                isJSON: true,
                value: JSON.stringify(app.data)
            }
            $.post('index.php?com=settings&action=save', data, function (r) {
                if (r.result != 'success') {
                    alert(r.message ? r.message : 'Ошибка сохранения!');
                    return;
                }
                window.location.reload();

            })
        },

        onSaveForm: function(){
            var $amount = $('#new-amount'),
                $percent = $('#new-percent'),
                newItem = {
                    amount: parseInt(jQuery.trim($amount.val())),
                    percent: parseInt(jQuery.trim($percent.val()))
                };
            $percent.parent().toggleClass('has-error', isNaN(newItem.percent));
            $amount.parent().toggleClass('has-error',isNaN(newItem.amount));
            if(isNaN(newItem.percent) || isNaN(newItem.amount)) return;
            app.data.push(newItem);
            app.onResetForm();
            app.renderList();
        },

        onResetForm: function(){
            this.$('#new-item input').val('').parent().removeClass('has-error');
        },

        onItemChange: function(e){
            var $input = $(e.currentTarget),
                $row = $input.closest('tr'),
                value = parseInt(jQuery.trim($input.val()));
            if(isNaN(value)){
                $input.val(app.data[$row.data('index')][$input.attr('name')]);
            } else {
                app.data[$row.data('index')][$input.attr('name')]=value;
            }

        },
        onItemRemove: function(e){
            var $row = $(e.currentTarget).closest('tr');
            if(!confirm('Удалить элемент?')) return;
            app.data.splice($row.data('index'),1);
            app.renderList();
        }
    }
    window.ShopMap = app;
    $(app.init);
})();