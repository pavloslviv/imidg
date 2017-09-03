(function(app){
    app.tpl = {
        userSelect: null
    }
    app.data = {
        contacts: [],
        contSelected: [],
        contAvailable: [],
        contFiltered: [],
        filter: {
            field: '',
            text: ''
        }
    }

    app.onStartMailFlow = function(e){
        app.data.letterId = $(e.currentTarget).data('id');
        $.fancybox({
            content: app.$popup
        });
        $.get('index.php?com=mailman&action=get_contacts',function(r){
                console.log('response',r)
            if (r.result!='success'){
                alert(r.message);
                return;
            }
            app.data.contacts = app.data.contAvailable = r.data;
            app.data.contFiltered = app.data.contAvailable;
            app.renderContacts();
        });
    }

    app.renderContacts = function(){
        app.$popup.html(this.tpl.userSelect({
            filter: app.data.filter,
            contAvailable: app.data.contFiltered,
            contSelected: app.data.contSelected
        }));
    }

    app.applyFilter = function(){
        var needle = app.data.filter.text.toLocaleLowerCase();
        if(!app.data.filter.field){
            app.data.contFiltered = app.data.contAvailable;
            return;
        }
        app.data.contFiltered = app.data.contAvailable.filter(function(cont){
            var string;
            string = cont[app.data.filter.field];
            if(!string){
                return false;
            }
            string = string.toLocaleLowerCase();
            return string.indexOf(needle)!=-1;
        });
    }

    app.onFilterClick =function(e){
        e.preventDefault();
        app.data.filter = {
            field: $('#filter_field').val(),
            text: $.trim($('#filter_text').val())
        }
        app.applyFilter();
        app.renderContacts();
    }

    app.onAddContact = function(e){
        e.preventDefault();
        var $row = $(e.currentTarget).closest('tr');
        var contId = $row.data('id');
        var contact;
        $row.detach();
        app.data.contAvailable = app.data.contAvailable.filter(function(cont){
            if(cont.id==contId){
                contact = cont;
                return true;
            }
            return false;
        });
        app.data.contSelected.push(contact);
        app.data.contFiltered = _.without(app.data.contFiltered, contact);
        $row.find('img').attr('src','/lib/images/delete.png');
        $('#selected_contacts').append($row);
    }
    app.onRemoveContact = function(e){
        e.preventDefault();
        var $row = $(e.currentTarget).closest('tr');
        var contId = $row.data('id');
        $row.detach();
        app.data.contSelected = app.data.contSelected.filter(function(cont){
            if(cont.id==contId){
                app.data.contAvailable.push(cont);
                app.data.contAvailable.push(cont);
                return true;
            }
            return false;
        });
        app.applyFilter();
        $row.find('img').attr('src','/lib/images/add.png');
        $('#available_contacts').append($row);
    }
    app.selectAll = function(e){
        e.preventDefault();
        app.data.contSelected.push.apply(app.data.contSelected, app.data.contFiltered);
        app.data.contAvailable = _.difference(app.data.contAvailable, app.data.contFiltered);
        app.applyFilter();
        app.renderContacts();
    }
    app.removeAll = function(e){
        e.preventDefault();
        app.data.contAvailable.push.apply(app.data.contAvailable, app.data.contSelected);
        app.data.contSelected.length = 0;
        app.applyFilter();
        app.renderContacts();
    }

    app.onSendMail = function(e){
        e.preventDefault();
        var $popup, $table, subscribers,startTime, failCounter= 0,$progress,totalCount=0,currentCount=0;
        if (!confirm('Разослать письмо?')){
            return;
        }
        subscribers = app.data.contSelected;
        totalCount = subscribers.length;
        startTime= new Date();
        $popup = app.$popup;
        $popup.empty().append('<div class="progress"></div><table class="itemlist">');
        $table = $popup.find('table');
        $progress = $popup.find('.progress');
        processBlock();
        function processBlock(){
            var list;
            if (!subscribers.length){
                alert('Все письма отправлены! Время выполнения '+Math.round(((new Date())-startTime)/1000)+' сек. Ошибок: '+failCounter);
                $.post('index.php?com=mailman&action=finish_sending',{id : app.data.letterId});
                $(e.currentTarget).remove();
                return;
            }
            list = subscribers.splice(0,5);
            $.post('index.php?com=mailman&action=send',{id : app.data.letterId, subscribers: list},function(r){
                if(r.data){
                    jQuery.each(r.data,function(index,item){
                        if(!item.ok){
                            failCounter++;
                        }
                        $table.append('<tr><td>'+item.name+'</td><td>'+item.mail+'</td><td>'+(item.ok?'OK':'<span style="-moz-border-bottom-colors: red">Ошибка</span> ')+'</td></tr>');
                        currentCount++;
                    });
                }
                $progress.html('Обработано '+Math.round(currentCount/totalCount*100)+'%');
                setTimeout(processBlock,5000);
            });
        }
    }

    app.init = function(){
        var $popup;
        app.tpl.userSelect = _.template($('#tmpl_contact_list').html());
        $('.send_letter_tigger').on('click',app.onStartMailFlow);
        $popup = app.$popup = $('<div id="mail_sender" class="container">');
        $popup.on('click','#available_contacts .move_contact',app.onAddContact);
        $popup.on('click','#selected_contacts .move_contact',app.onRemoveContact);
        $popup.on('click','.add_all',app.selectAll);
        $popup.on('click','.remove_all',app.removeAll);
        $popup.on('click','#filter_apply',app.onFilterClick);
        $popup.on('click','#send_mail',app.onSendMail);
    }
    window.Mailman = app;
})({});

$(Mailman.init);