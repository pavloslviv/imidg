    {*<script type="text/javascript" src="/lib/js/codemirror/codemirror.js"></script>
    <script type="text/javascript" src="/lib/js/codemirror/util/formatting.js"></script>
    <script type="text/javascript" src="/lib/js/codemirror/modes/xml.js"></script>
    <script type="text/javascript" src="/lib/js/codemirror/modes/javascript.js"></script>
    <script type="text/javascript" src="/lib/js/codemirror/modes/css.js"></script>
    <script type="text/javascript" src="/lib/js/codemirror/modes/htmlmixed.js"></script>
    <link rel="stylesheet" type="text/css" href="lib/codemirror/codemirror.css">*}
    <script type="text/javascript" src="/lib/ckeditor/plugins/codemirror/js/codemirror.min.js"></script>
    <script type="text/javascript" src="/lib/ckeditor/plugins/codemirror/js/codemirror.modes.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/lib/ckeditor/plugins/codemirror/css/codemirror.min.css">
<div class="container">
    <form id="new_url" class="form-inline row">
        <div class="input-group col-md-6">
            <input type="text" class="form-control" name="new_url" placeholder="Добавьте новый URL без указания домена" >
            <span class="input-group-btn">
                <button type="submit" class="btn btn-success">Добавить</button>
            </span>
        </div>
        {*<div class="span6">
            <div class="span2" style="text-align: right">
                <button id="ping_urls" class="btn btn-primary" type="button">Проверить ссылки</button>
            </div>
            <div class="span3">
                <div id="ping_status" class="progress progress-striped" style="display: none">
                    <div class="bar" style="width: 0%;"></div>
                    <div class="info"></div>
                </div>
            </div>
        </div>*}
    </form>
    <table class="table table-striped" id="tags_list">
        <thead>
            <tr>
                <th>URL</th>
                <th>Title</th>
                <th>Мета-теги</th>
                <th>Описание</th>
                <th>Удалить</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
<script type="text/template" id="tmpl_edit_popup">
    <form id="edit_meta_form" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Мета теги</h3>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">
                        <input type="hidden" name="id" value="<%= id %>">
                        <div class="control-group">
                            <label class="control-label" for="inputUrl">URL</label>
                            <div class="controls">
                                <textarea class="form-control" id="inputUrl" placeholder="Title" name="url"><%= url %></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputTitle">Title</label>
                            <div class="controls">
                                <textarea class="form-control" id="inputTitle" placeholder="Title" name="title"><%= title %></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputDescription">Description</label>
                            <div class="controls">
                                <textarea class="form-control" id="inputDescription" placeholder="Description" name="description"><%= description %></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="inputKeywords">Keywords</label>
                            <div class="controls">
                                <textarea class="form-control" id="inputKeywords" placeholder="Keywords" name="keywords"><%= keywords %></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn cancel_popup">Отмена</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </form>
</script>
<script type="text/template" id="tmpl_text_edit">
    <form id="edit_text_form" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Редактирование текста описания</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<%= id %>">
                    <textarea name="text"><%= text %></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn cancel_popup">Отмена</button>
                    <button type="submit" class="btn btn-primary save_popup">Сохранить</button>
                </div>
            </div>
        </div>
    </form>
</script>
<script type="text/template" id="tmpl_list_row">
    <tr id="item_<%= id %>" data-id="<%= id %>">
        <td><%= url %></td>
        <td><%= title %></td>
        <td>
            <button class="btn btn-mini edit_meta">Править</button>
        </td>
        <td>
            <div class="btn-group">
                <button type="button" class="btn btn-mini edit_text" data-type="source">HTML</button>
                <button type="button" class="btn btn-mini edit_text" data-type="tinymce">WYSIWIG</button>
            </div>
        </td>
        <td>
            <button class="btn btn-mini btn-danger del_item"><i class="glyphicon glyphicon-remove"></i></button>
        </td>
    </tr>
</script>
<script type="text/javascript" src="template/js/meta_tags.js"></script>
<script type="text/javascript">
    App.data.list = {$list};
</script>
</body>
</html>