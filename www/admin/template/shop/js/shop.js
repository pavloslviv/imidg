(function(){
    var sr = window.SerenityShop = {
        data: {
            orderStatuses: {
                'new': 'Новый',
                'processing': 'Обрабатывается',
                'shipped': 'Отправлен',
                'cancelled': 'Отменен',
                'done': 'Выполнен',
                'oneClick': 'В один клик',
            },
            paymentMethods: {
                'bank':'Оплата через банк',
                'cash':'Наличными',
                'privat24':'Приват24',
                'liqpay':'LiqPay',
            },
            callbackStatuses: {
                1 : 'Новый',
                2 : 'Выполнен',
                3 : 'Отменен'
            }
        },
        classes: {
            views: {
                partial: {},
                popup: {}
            },
            collections: {},
            models: {}
        },
        core: {
            apiCall: function (com,action, type, data, callback, progressCallback) {

                var requestObj, apiURL = '/admin/index.php?com='+com+'&action='+action+'&json=true';
                if (!data) data = {};
                function onSuccess(responseObj, textStatus, jqXHR){
                    if (callback) callback(false,responseObj);
                }
                function onError(jqXHR, textStatus, errorThrown){
                    if (jqXHR.responseText){
                        //try to convert responseText into error json
                        try {
                            var errorObj = JSON.parse(jqXHR.responseText);
                            if (callback) callback(true,errorObj);
                        } catch(er){
                            console.warn("apiCall:error unable to parse error response json");
                            if (callback) callback(true);
                        }
                    } else {
                        if (callback) callback(true);
                    }
                }
                console.log("apiCall url: "+apiURL);
                requestObj = {
                    url: apiURL,
                    dataType: 'json',
                    type: type,
                    data: data,
                    success: onSuccess,
                    error: onError
                }
                if (data.file){
                    requestObj.cache = false;
                    requestObj.contentType = false;
                    requestObj.processData = false;
                    requestObj.type = 'POST';
                    var formData = new FormData();
                    for (var i in data){
                        formData.append(i,data[i]);
                    }
                    requestObj.data = formData;
                    if (progressCallback){
                        requestObj.xhr = function() {
                            var myXhr = $.ajaxSettings.xhr();
                            if(myXhr.upload){
                                myXhr.upload.addEventListener('progress',progressCallback, false);
                            }
                            return myXhr;
                        }
                    }
                }
                return $.ajax(requestObj);
            },
            loadTemplate: function(id,templateData){
                var tmpl = $('#' + id).html();
                if (tmpl === null) {
                    console.warn('#' + id + ' template doesn\'t exist');
                    return function(){};
                }
                //console.log(id);
                return _.template(tmpl, templateData);
            },
            goTo: function(url){
                window.location.hash = '#'+url;
            },
            switchToView: function(Class,args){
                if (sr.currentView){
                    sr.currentView.destroy();
                }
                sr.currentView = new Class(args);
                sr.$appContainer.append(sr.currentView.el);
            },

            createEditor: function($el){
                return CKEDITOR.replace($el.get(0));
            },

            storage: {
                set: function(key,data){
                    if(this.enabled)
                    {
                        if (typeof data === 'string') window.localStorage[key]=data;
                        if (typeof data === 'object') window.localStorage[key]=JSON.stringify(data);
                    }
                    else
                    {
                        return false;
                    }
                },
                get: function(key){
                    if(this.enabled)
                    {
                        var data;
                        try{
                            data = JSON.parse(window.localStorage[key]);
                        }
                        catch (e){
                            console.log('Local storage: return as string');
                            data = window.localStorage[key];
                        }
                        return data;
                    }
                    else
                    {
                        return false;
                    }
                },
                enabled: typeof(Storage)!=="undefined" ? true : false
            },
            router: null
        },
        helpers: {},
        init: function(){
            sr.core.router = new sr.classes.Router();
            sr.$appContainer = $('#shop-container');
            sr.$appContainer.on('click','a',function(e){
                if($(this).attr('href')=='#') e.preventDefault();
            });
            Backbone.history.start();
        }
    };
    $(sr.init);

})();
