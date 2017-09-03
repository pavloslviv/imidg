/**
 * Created by Sergiy on 02.07.2014.
 */
(function () {
    var h = window.AutoTranslator = {
        loadAPI: function (cb) {
            if(!gapi.client.language){
                gapi.client.load('translate', 'v2', cb);
            } else {
                if(cb) cb();
            }
        },

        translate: function (string, cb) {
            h.loadAPI(function(){
                h._translate(string, cb)
            });
        },
        _translate: function(string, cb){
            //gapi.client.language.translations.list
            var request = gapi.client.language.translations.list({
                format: 'html',
                q: [string],
                source: h.currentLang=='uk' ? 'ru' : 'uk',
                target: h.currentLang=='uk' ? 'uk' : 'ru'
            });
            if(!cb) cb = function(){};
            request.execute(function(response) {
                var valid = response.data.translations && response.data.translations.length;
                if(!valid){
                    console.log('translations not found');
                    return cb('');
                }
                cb(response.data.translations[0].translatedText);

            });
        },

        translateInputs: function(e){
            e && e.preventDefault();
            var tasks = [], executeTask;
            $('input.translated-field,textarea.translated-field').each(function () {
                var task, $input = $(this);
                if(!$input.is(':visible')){
                    return;
                }
                task = function(cb){
                    $input.attr('disabled','disabled');
                    h.translate($input.val(),function(str){
                        $input.removeAttr('disabled');
                        if(!str) return cb();
                        $input.val(str);
                        if(cb) cb();
                    });
                }
                tasks.push(task);
            });
            if(CKEDITOR){
                _.each(CKEDITOR.instances,function(instance){
                    var task = function(cb){
                        h.translate(instance.getData(),function(str){
                            if(!str) return cb();
                            instance.setData(str);
                            if(cb) cb();
                        });
                    }
                    tasks.push(task);
                });
            }
            executeTask = function(){
                var task;
                if(!tasks.length){
                    return;
                }
                task = tasks.shift();
                task(executeTask);
            }
            executeTask();
        },

        init: function () {
            var $button = $('<li><a id="auto-translate-trigger" href="#">Перевести поля на '+(h.currentLang=='uk' ? 'Украинский' : 'Русский')+'</a></li>');
            if($('.lang-label').length || window.SerenityShop){
                $('.lang_selector').append($button);
                $button.on('click', h.translateInputs);
            }
        }
    };
    $(h.init);
})();