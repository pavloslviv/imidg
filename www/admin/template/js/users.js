(function (app) {
    app.loadTemplates = function () {
        app.userEditTemplate = _.template($('#user_edit_template').html());
        app.userRowTemplate = _.template($('#user_row_template').html());
        app.uploadFormTemplate = _.template($('#upload_form').html());
    }
    app.extendChangeEvent = function () {
        $(document).on('focus','.paramlist input[type="text"]',
            function () {
                var $this = $(this);
                $this.data('before', $this.val());
                return $this;
            }).on('blur keyup paste','.paramlist input[type="text"]',
            function () {
                var $this = $(this);
                if ($this.data('before') !== $this.val()) {
                    $this.data('before', $this.val());
                    $this.trigger('change');
                }
                return $this;
            });
    }
    app.showUploadForm = function (e) {
        var $button, $form;
        e.preventDefault();
        $button = $(e.currentTarget);
        $form = $(app.uploadFormTemplate({user_id:$button.attr('data-id')}))
        $($form).modal('show');
        $form.find('input[type="file"]').bind('change', app.doUpload);
    }
    app.showEditForm = function (e) {
        var $button=$(e.currentTarget),
            $form,
            userId = $button.attr('data-id'),
            user=app.userList[userId];
        if (!user){
            user= {id:0,full_name:'',username:'',pass:'',mail:'',phone:'',skype:''};
        }
        $form = $(app.userEditTemplate({u: user}));
        $($form).modal('show');
        $form.find('form').bind('submit', app.saveUser);
    }
    app.saveUser = function(e){
        e.preventDefault();
        var $form =$(e.currentTarget),
            data = {};
        console.log('form',$form);
        _.each($form.serializeArray(),function(item){
            data[item.name]=item.value;
        });
        $.post('index.php?com=users&action=save', data, function (r) {
            if (r.result != 'success') {
                alert('Ошибка сохранения!');
                return;
            }
            if (data.id == r.data.id) {
                $('tr[data-id="'+ r.data.id +'"]').replaceWith(app.userRowTemplate({user: r.data}));
            } else {
                $('#user_list tbody').append(app.userRowTemplate({user: r.data}));
            }
            app.userList[r.data.id]=r.data;
            $form.closest('.modal').modal('hide');
            $form.remove();
        }, 'json');
    }
    app.doUpload = function (e) {
        var file, $p, $progress, id;
        if (e.currentTarget.files.length < 1) return;
        $p = $(e.currentTarget).parent();
        file = e.currentTarget.files[0];
        id = $p.find('input[name="user_id"]').val();
        $(e.currentTarget).remove();
        $p.html('<div class="progress progress-striped active"><div class="bar" style="width: 0%;"></div></div>');
        $progress = $p.find('.bar');
        Helpers.html5Upload('index.php?com=users&action=upload', file, {id: id}, function (r) {
            var data, $uploadBtn, cacheBreaker=+new Date();
            if(r.status=='uploading') {
                $progress.css('width', r.progress+'%');
                return;
            }
            if (r.status=='error') {
                alert('Ошибка загрузки');
                return
            }
            data = jQuery.parseJSON(r.response);
            if (data.result=='error') {
                alert('Ошибка загрузки');
                return
            }
            $p.html('<div class="thumbnail" style="margin-bottom: 15px"><img src="'+data.url+'?cbr='+cacheBreaker+'"/></div>'+
                '</div><div class="alert alert-success">Фото успешно загружено!</div>');
            $uploadBtn=$('button[data-id="'+id+'"]');
            $uploadBtn.parent().find('.thumbnail').remove();
            $uploadBtn.before('<div class="thumbnail" style="display: inline-block;"><img src="'+data.thumb_url+'?cbr='+cacheBreaker+'"/></div><br />');
        });

    }
    app.init = function () {
        app.loadTemplates();
        app.extendChangeEvent();
        $(document).on('change','.paramlist tr', function () {
            $('.save', this).removeClass('disabled');
        });
        $(document).on('click','.upload', app.showUploadForm);
        $(document).on('click','.edit', app.showEditForm);
        $(document).on('click','.delete', function (e) {
            var $this = $(this),
                $row = $this.parents('tr');
            e.preventDefault();
            if (!confirm('Вы уверенны что хотите удалить пользователя?')) return;
            $.post($this.attr('href'), {mode:'ajax'}, function (r) {
                if (r.result != 'success') {
                    alert('Ошибка удаления!');
                    return;
                }
                $row.remove();
            }, 'json');
        });
    }
    window.App = app;
})({});
$(App.init);