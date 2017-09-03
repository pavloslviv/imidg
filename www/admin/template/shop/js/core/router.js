/**
 * Created by Sergiy on 16.10.13.
 */
(function (sr) {
    sr.classes.Router = Backbone.Router.extend({

        routes: {
            "": "dashboard",
            "products(/:sectionId)(/:page)": "products",
            "product/:productId": "product",
            "orders(/:status)(/:page)": "orders",
            "order/:orderId": "order",
            "customers(/:page)": "customers",
            "customer/:customerId": "customer",
            "callbacks(/:status)(/:page)": "callbacks",
            "callback/:id": "callback"
        },

        dashboard: function() {
            console.log('Dasboard init');
        },

        products: function(sectionId,page) {
            if (sectionId < 0) {
                sr.core.switchToView(sr.classes.views.DeletedProductList, {page: page});
            } else if (sectionId > 0) {
                sr.core.switchToView(sr.classes.views.ProductList,{sectionId: sectionId, page: page});
            } else {
                sr.core.switchToView(sr.classes.views.UnprocessedProductList,{page: page});
            }

        },

        product: function(productId) {
            sr.core.switchToView(sr.classes.views.ProductDetails,{productId: productId});
        },

        orders: function(status,page) {
            console.log('Orders init',arguments);
            sr.core.switchToView(sr.classes.views.OrderList,{status: status, page: page});
        },

        order: function(orderId) {
            console.log('Order page init',arguments);
            sr.core.switchToView(sr.classes.views.OrderDetails,{orderId: orderId});
        },

        customers: function(page) {
            console.log('Customers list init',arguments);
            sr.core.switchToView(sr.classes.views.CustomerList,{page: page});
        },

        customer: function(customerId) {
            console.log('Customer page init',arguments);
            sr.core.switchToView(sr.classes.views.CustomerDetails,{customerId: customerId});
        },

        callbacks: function(status,page) {
            console.log('Callback init');
            sr.core.switchToView(sr.classes.views.CallbacksList,{status: status, page: page});
        },

        callback: function(id) {
            console.log('Callback page init',arguments);
            sr.core.switchToView(sr.classes.views.CallbackDetails,{id: id});
        },

    });
})(SerenityShop);