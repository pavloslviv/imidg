/**
 * Created by Sergiy on 14.10.13.
 */
(function (sr) {
    sr.classes.views.CustomerList = sr.classes.views.Base.extend({

        events: {
            /*'click #section-nav a': 'onSectionClick',
            'click .add-product-trigger': 'onNewProductClick',
            'click #product-list .copy-trigger': 'onProductCopy',
            'click #product-list .delete-trigger': 'onProductDelete',*/
            'change #card-list-file-input': 'uploadDiscountList'
        },

        initialize: function(data){
            var  that = this;

            this.collection = sr.data.customers;
            if(!this.collection){
                this.collection = sr.data.customers = new sr.classes.collections.Customers();
            }
            this.render();

            this.toggleSpinner(true);
            if(this.collection.loaded){
                this.renderList();
            } else {
                this.collection.fetch(function(err){
                    if(!err){
                        that.renderList();
                    }
                });
            }
        },

        template: sr.core.loadTemplate('tmpl_shop_customer_list'),
        templateRow: sr.core.loadTemplate('tmpl_shop_customer_list_row'),

        render: function(){
            this.$el.html(this.template({
                sections: this.sections
            }));
        },
        renderList: function(){
            var that = this,
                list = '';
            this.collection.each(function(c){
                list+=that.templateRow({
                    c: c
                });
            });
            this.$('#customer-list').html(list);
            this.toggleSpinner(false);
        },

        uploadDiscountList: function (e) {
            var that = this,
                $input = $(e.currentTarget),
                file = e.currentTarget.files[0],
                formData = new FormData(),
                inputCode,
                $inputWrapper = $input.parent();
            formData.append('file',file);
            this.toggleSpinner(true);
            function onSuccess(r){
                $('.ajax-loader').remove();
                if(r.result!=='success'){
                    alert(r.message ? r.message : 'Ошибка загрузки файла!');
                    return;
                }
                inputCode = $inputWrapper.html();
                $input.remove();
                that.toggleSpinner(false);
                _.delay(function(){
                    alert('Импорт прошел успешно. Добавлено '+ r.data.new+'. Обновлено '+ r.data.update+'.');
                    $inputWrapper.html(inputCode);
                },1);
            }

            function onError(){
                $('.ajax-loader').remove();
                that.toggleSpinner(false);
                alert('Ошибка загрузки файла!');
            }

            $.ajax({
                url: 'index.php?com=customers&action=import',
                dataType: 'json',
                data: formData,
                success: onSuccess,
                error: onError,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            });
        },

        onCreate: function(){

        },
        onDestroy: function(){

        }
    });
})(SerenityShop);