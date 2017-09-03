(function (app) {
    app.data = {
        options: []
    };

    app.templates = {
        optionRow: 'tmpl-option-row',
        newOptionRow: 'tmpl-new-option-row'
    };

    app.events = {
        'keyup@form input': 'onFormChange',
        'keyup@form textarea': 'onFormChange',
        'reset@form': 'onFormReset',
        'reset@#description-form': 'onDescrReset',
        'submit@#main-form': 'onMainFormSubmit',
        'submit@#description-form': 'onDescrFormSubmit',
        'keyup@#option-list .title-input': 'onOptionChange',
        'change@#option-list .option-row .filter-input': 'onOptionChange',
        'click@#option-list .add-option-trigger': 'onOptionAddClick',
        'click@#option-list .save-option-trigger': 'onOptionSaveClick',
        'click@#option-list .delete-option-trigger': 'onOptionDeleteClick'
    };

    app.init = function () {
        app.$el = $('#app-container');
        app.sectionId = $('#section-id').val();
        _.each(app.templates, function (tmplId, name) {
            app.templates[name] = _.template($('#' + tmplId).html());
        });

        _.each(app.events, function (handler, evt) {
            evt = evt.split('@');
            if (!app[handler]) return;
            $(document).on(evt[0], evt[1], app[handler]);
        });
        app.descriptionEditor = CKEDITOR.replace($('#description-editor').get(0));
        app.descriptionEditor.on('change',app.onDescrChange);
        if(app.$el.find('#option-list').length){
            app.loadOptions();
        }
    };
    window.AdminPage = app;

    app.loadOptions = function(){
        $.get('index.php?com=shop_sections&action=list_options',{id: app.sectionId},function(r){
            if(!r.success){
                alert('Error loading section options!');
                return;
            }
            _.each(r.options,function(o){
                o.is_filter=o.is_filter*1;
            });
            app.data.options = r.options;
            app.renderOptions();
        });
    };

    app.renderOptions = function(){
        var $tbody = app.$el.find('#option-list tbody');
        _.each(app.data.options,function(o){
            $tbody.append(app.templates.optionRow(o));
        });
        $tbody.append(app.templates.newOptionRow());
    };

    app.onFormChange = _.debounce(function (e) {
        e.preventDefault();
        $(e.currentTarget).closest('form').find('.form-buttons button').removeAttr('disabled');
    },200);

    app.onFormReset = function (e) {
        $(e.currentTarget).find('.form-buttons button').attr('disabled', 'disabled');
    };

    app.onMainFormSubmit = function (e) {
        e.preventDefault();
        var $form = $(e.currentTarget),
            data = $form.serializeObject();
        $.post('index.php?com=shop_sections&action=save&id=' + app.sectionId, {section: data}, function (r) {
            if (!r.success) {
                alert('Ошибка сохранения!');
                return;
            }
            _.each(r.section, function (value, name) {
                var $input = $form.find('[name="' + name + '"]');
                if($input.prop('tagName')=="TEXTAREA"){
                    $input.html(value);
                } else {
                    $input.attr('value',value);
                }
            });
            $(e.currentTarget).find('.form-buttons button').attr('disabled', 'disabled');
        });
    };

    app.onDescrChange = function (e) {
        $('#description-form .form-buttons button').removeAttr('disabled');
    };

    app.onDescrReset = function (e) {
        app.descriptionEditor.setData($('#description-editor').val());
    };

    app.onDescrFormSubmit = function (e) {
        e.preventDefault();
        var $form = $(e.currentTarget),
            data = $form.serializeObject();
        $.post('index.php?com=shop_sections&action=save_descr&id=' + app.sectionId, data, function (r) {
            if (!r.success) {
                alert('Ошибка сохранения!');
                return;
            }
            _.each(r.section, function (value, name) {
                $form.find('[name="' + name + '"]').val(value);
            });
            $(e.currentTarget).find('.form-buttons button').attr('disabled', 'disabled');
        });
    };

    app.onOptionChange = _.debounce(function(e){
        var $input = $(e.currentTarget),
            $row = $input.closest('tr');
        console.log('eeeee',e,$input.val());
        if(jQuery.trim($input.val())=='') return;
        $row.find('button').removeAttr('disabled');
    },100);

    app.onOptionAddClick = function(e){
        e.preventDefault();
        var $button = $(e.currentTarget),
            $row = $button.closest('tr'),
            data = {
                section_id: app.sectionId,
                title: jQuery.trim($row.find('.title-input').val()),
                is_filter: $row.find('.filter-input').prop('checked') ? 1 : 0
            };
            if(!data.title){
                $button.attr('disabled','disabled');
                return;
            }
        $.post('index.php?com=shop_sections&action=save_option',{option: data},function(r){
            if (!r.success) {
                alert('Ошибка сохранения!');
                return;
            }
            r.option.is_filter = r.option.is_filter*1;
            $row.before(app.templates.optionRow(r.option));
            $row.replaceWith(app.templates.newOptionRow());
        });

    }

    app.onOptionSaveClick = function(e){
        e.preventDefault();
        var $button = $(e.currentTarget),
            $row = $button.closest('tr'),
            data = {
                title: jQuery.trim($row.find('.title-input').val()),
                is_filter: $row.find('.filter-input').prop('checked') ? 1 : 0
            };
            if(!data.title){
                $button.attr('disabled','disabled');
                return;
            }
        $.post('index.php?com=shop_sections&action=save_option&id='+$row.data('id'),{option: data},function(r){
            if (!r.success) {
                alert('Ошибка сохранения!');
                return;
            }
            $button.attr('disabled','disabled');
        });

    }

    app.onOptionDeleteClick = function(e){
        e.preventDefault();
        var $button = $(e.currentTarget),
            $row = $button.closest('tr');
        if(!confirm('Вы действительно хотите удалить даный параметр? Это действие невозможно отменить.')) return;
        $.get('index.php?com=shop_sections&action=delete_option&id='+$row.data('id'),function(r){
            if (!r.success) {
                alert('Ошибка удаления!');
                return;
            }
            $row.remove();
        });

    }

})({});
$(document).ready(AdminPage.init);