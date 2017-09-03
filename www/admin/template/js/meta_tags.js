(function (app) {
    app.data = {
        list:null
    }
    app.tpl = {
        metaEditPopup:_.template($('#tmpl_edit_popup').html()),
        textEditPopup:_.template($('#tmpl_text_edit').html()),
        listRow:_.template($('#tmpl_list_row').html())
    }
    app.events = {
        'click@.edit_text': 'editText',
        'click@.edit_meta': 'editMeta',
        'submit@#new_url': 'addNewUrl',
        'submit@#edit_text_form': 'saveText',
        'submit@#edit_meta_form': 'saveText',
        'click@#ping_urls': 'pingAll',
        'click@.del_item': 'delItem'
    };

    app.addEditor = function($object){
        CKEDITOR.replace($object.get(0));
    }
    app.addSourceEditor = function($object){
        console.log('ADD SOURCE EDITOR')
        var range,
            editor = CodeMirror.fromTextArea($object.get(0), {
            mode: 'text/html',
            tabMode: 'indent',
            onChange: function() {
            }
        });
        /*CodeMirror.commands["selectAll"](editor);
        editor.autoFormatRange(editor.getCursor(true), editor.getCursor(false));
        editor.setSelection({line:0,ch:0},{line:0,ch:0});*/
    }

    app.renderList = function(){
        var list = '',
            $listContainer = $('#tags_list tbody');
        $listContainer.empty();
        _.each(App.data.list,function(item){
            list+=app.tpl.listRow(item);
        });
        $listContainer.html(list);
    }

    app.addNewUrl = function(e){
        e.preventDefault();
        var $form = $(e.currentTarget);
        var newUrl = $form.find('[name="new_url"]').val();
        $.post('index.php?com=meta_tags&action=add',{new_url: newUrl},function(r){
            if(r.result!='success'){
                alert('Ошибка добавления мета-тега.');
                return;
            }
            app.data.list[r.data.id]= r.data;
            /*app.data.list = _.sortBy(app.data.list,function(item){
                return item.url;
            });*/
            app.renderList();
        });
    }

    app.editText = function(e){
        var itemId = $(e.currentTarget).parents('tr').attr('data-id');
        var mode = $(e.currentTarget).attr('data-type');
        $.get('index.php?com=meta_tags&action=edit',{id: itemId},function(r){
            var $popup;
            if(r.result!='success'){
                alert('Ошибка добавления мета-тега.');
                return;
            }
            $popup = $(app.tpl.textEditPopup(r.data));
            $popup.find('.cancel_popup').on('click',function(){
                $popup.modal('hide');
            });
            $popup.on('hidden.bs.modal',function(){
                $(this).remove();
            });
            $popup.on('shown.bs.modal',function(){
                if(mode=='source'){
                    app.addSourceEditor($popup.find('textarea'));
                } else {
                    app.addEditor($popup.find('textarea'));
                }
            });
            $popup.modal('show');
        });

    }

    app.editMeta = function(e){
        var itemId = $(e.currentTarget).parents('tr').attr('data-id');
        $.get('index.php?com=meta_tags&action=edit',{id: itemId},function(r){
            var $popup;
            if(r.result!='success'){
                alert('Ошибка добавления мета-тега.');
                return;
            }
            $popup = $(app.tpl.metaEditPopup(r.data));
            $popup.find('.cancel_popup').on('click',function(){
                $popup.modal('hide');
            });
            $popup.on('hidden.bs.modal',function(){
                $(this).remove();
            });
            $popup.modal('show');
        });

    }
    app.delItem = function(e){
        var itemId = $(e.currentTarget).parents('tr').attr('data-id');
        if(!confirm('Удалить мета-тег?')){
            return;
        }
        $.get('index.php?com=meta_tags&action=delete',{id: itemId},function(r){
            var $popup;
            if(r.result!='success'){
                alert('Ошибка удаления мета-тега.');
                return;
            }
            delete app.data.list[itemId];
            app.renderList();
        });
    }

    app.saveText = function(e){
        e.preventDefault();
        var $form = $(e.currentTarget);
        var newUrl = $form.find('[name="new_url"]').val();
        var data = $form.serializeArray();
        var reqData = {};
        _.each(data,function(item){
            reqData[item.name]=item.value;
        });
        $.post('index.php?com=meta_tags&action=save',{
            meta: reqData
        },function(r){
            var item;
            if(r.result!='success'){
                alert('Ошибка обновления.');
                return;
            }
            $form.modal('hide');
            app.data.list[r.data.id]= r.data;
            /*if ('url' in reqData){
                app.data.list = _.sortBy(app.data.list,'url');
            }*/
            if ('url' in reqData || 'title' in reqData){
                app.renderList();
            }
        });
    }

    app.pingAll = function(e){
        var $pingStatus = $('#ping_status');
        var pingList = _.map(app.data.list,function(item){
            return {
                id: item.id,
                url: item.url
            }
        });
        var total = pingList.length,
            current=1;
        $('tr.error,tr.success').removeClass('error success');
        $pingStatus.children('.bar').html('').css('width',0);
        $pingStatus.fadeIn();
        function checkItem(){
            var item;
            if(!pingList.length){
                $pingStatus.children('.bar').html('Все ссылки проверены');
                return;
            }
            item = pingList.shift();
            setTimeout(function(){
                app.pingURL(item.url,item.id,function(){
                    $pingStatus.children('.bar').css('width',Math.round(current/total*100)+'%');
                    current++;
                    checkItem();
                });
            },1000);
        }
        checkItem();
    }

    app.pingURL = function(url,id,cb){
        $.ajax({
            url: url,
            success: function(){
                $('#item_'+id).addClass('success');
                cb(true);
            },
            error: function(){
                $('#item_'+id).addClass('error');
                cb(false);
            },
            dataType: 'text'
        });
    }


    app.init = function () {
        _.each(app.events,function(handler,event){
            var evArray = event.split('@');
            $(document).on(evArray[0],evArray[1], app[handler]);
        });
        app.renderList();
    }

    window.App = app;
})({});
$(App.init);

App.data.editorConfig = {
    // Location of TinyMCE script
    script_url:'lib/tiny_mce/tiny_mce.js',

    // General options
    theme:"advanced",
    plugins:"autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

    // Theme options
    theme_advanced_buttons1:"save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2:"cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,|,insertdate,inserttime,preview,|,forecolor,backcolor",
    theme_advanced_buttons3:"tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
    theme_advanced_buttons4:"insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
    theme_advanced_toolbar_location:"top",
    theme_advanced_toolbar_align:"left",
    theme_advanced_statusbar_location:"bottom",
    theme_advanced_resizing:true

    // Example content CSS (should be your site CSS)
    //content_css:"css/content.css",

    // Drop lists for link/image/media/template dialogs
   /* template_external_list_url:"lists/template_list.js",
    external_link_list_url:"lists/link_list.js",
    external_image_list_url:"lists/image_list.js",
    media_external_list_url:"lists/media_list.js",*/

    // Replace values for the template plugin
    /*template_replace_values:{
        username:"Some User",
        staffid:"991234"
    }*/
};