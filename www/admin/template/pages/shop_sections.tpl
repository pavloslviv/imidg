<div id="app-container" class="sixteen columns  panel">
    {literal}
        <script type="text/javascript">
            (function (app) {
                app.templates = {
                    'addSection': 'tmpl_add_section',
                    'delSection': 'tmpl_del_section'
                }

                app.events = {
                    'click@.add-section-trigger': 'onAddSectionClick',
                    'submit@#add-section-form': 'onAddSectionSubmit',
                    'click@.del-section-trigger': 'onDeleteSectionClick',
                    'submit@#del-section-form': 'onDelSectionSubmit',
                }

                app.init = function () {
                    app.$el = $('#app-container');
                    _.each(app.templates, function (tmplId, name) {
                        app.templates[name] = _.template($('#' + tmplId).html());
                    });

                    _.each(app.events, function (handler, evt) {
                        evt = evt.split('@');
                        if (!app[handler]) return;
                        $(document).on(evt[0], evt[1], app[handler]);
                    });

                }
                window.AdminPage = app;

                app.onAddSectionClick = function (e) {
                    e.preventDefault();
                    var parentId = $(e.currentTarget).data('parent');
                    var $modal = $(app.templates.addSection({parentId: parentId})).modal();
                    $modal.on('hidden.bs.modal', function () {
                        $modal.remove();
                    })
                }

                app.onDeleteSectionClick = function (e) {
                    e.preventDefault();
                    var id = $(e.currentTarget).data('id');
                    var $modal = $(app.templates.delSection({id: id})).modal();
                    $modal.on('hidden.bs.modal', function () {
                        $modal.remove();
                    })
                }

                app.onAddSectionSubmit = function (e) {
                    e.preventDefault();
                    var $form = $(e.currentTarget),
                            data,
                            $titleInput = $form.find('[name="title"]');
                    if (!jQuery.trim($titleInput.val())) {
                        $titleInput.closest('.form-group').addClass('has-error');
                        return;
                    } else {
                        $titleInput.closest('.form-group').addClass('has-success');
                    }
                    data = {
                        parent: $form.find('[name="parentId"]').val(),
                        title: jQuery.trim($titleInput.val())
                    };
                    $.post('index.php?com=shop_sections&action=add',data,function(r){
                        if(r.success){
                            window.location.href='index.php?com=shop_sections&action=edit&id='+ r.sectionId;
                        }
                    });
                }

                app.onDelSectionSubmit = function (e) {
                    e.preventDefault();
                    var $form = $(e.currentTarget),
                            data;
                    data = {
                        id: $form.find('[name="id"]').val(),
                    };
                    $.post('index.php?com=shop_sections&action=del', data, function(r){
                        if(r){
                            window.location.href='index.php?com=shop_sections';
                        }
                    });
                }
            })({});
            $(document).ready(AdminPage.init);
        </script>
    {/literal}
    <table class="table table-striped">
        {foreach from=$items item="item"}
            <tr class="section">
                <td>
                    {if $item.cat_level>0}
                        {for $var=1 to $item.cat_level}&nbsp;&nbsp;&nbsp;&nbsp;{/for}
                        &boxur;&boxh;
                    {/if}
                    {$item.title}
                </td>
                <td style="text-align: left">
                    {if $item.cat_level>0}
                        <a class="btn btn-xs btn-success" href="index.php?com=shop_sections&action=edit&id={$item.id}">
                            <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a class="add-section-trigger btn btn-xs btn-primary" href="#" data-parent="{$item.id}">
                            <span class="glyphicon glyphicon-plus-sign"></span>
                        </a>
                        <a class="btn del-section-trigger btn-xs btn-danger" href="#" data-id="{$item.id}">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    {else}
                        <span class="btn btn-xs btn-default disabled">
                            <span class="glyphicon glyphicon-edit"></span>
                        </span>
                        <a class="add-section-trigger btn btn-xs btn-primary" href="#" data-parent="{$item.id}">
                            <span class="glyphicon glyphicon-plus-sign"></span>
                        </a>
                        <span class="btn btn-xs btn-default disabled">
                            <span class="glyphicon glyphicon-remove"></span>
                        </span>
                    {/if}
                </td>
            </tr>
        {/foreach}
    </table>
</div>
<script type="text/template" id="tmpl_add_section">
    <div class="modal fade">
        <div class="modal-dialog">
            <form id="add-section-form" role="form" class="modal-content">
                <input type="hidden" name="parentId" value="<%= parentId %>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Создание раздела</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="newSectionTitle">Название раздела</label>
                        <input name="title" type="text" class="form-control" id="newSectionTitle">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Создать</button>
                </div>
            </form>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl_del_section">
    <div class="modal fade">
        <div class="modal-dialog">
            <form id="del-section-form" role="form" class="modal-content">
                <input type="hidden" name="id" value="<%= id %>">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Удаление раздела</h4>
                </div>
                <div class="modal-body">
                    <h5>Вы действительно желаете удалить раздел?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Удалить</button>
                </div>
            </form>
        </div>
    </div>
</script>