<script type="text/javascript" src="/admin/template/js/shop_section_edit.js"></script>
<a href="/admin/index.php?com=shop_sections" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Назад</a>
<h3>Редактирование раздела</h3>
<div id="app-container">
<input id="section-id" type="hidden" value="{$section.id}">
<div class="row">
    <div class="{if $section.cat_level==1}col-md-6{else}col-md-12{/if}">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Основные параметры</h3>
            </div>
            <div class="panel-body" style="height: 595px">
                <form id="main-form" class="" method="post">
                    <div class="form-group">
                        <label for="inputName" class="control-label">Название{$lang_label}</label>
                        <input name="title" type="text" class="form-control translated-field" id="inputName"
                               value="{$section.title}">
                    </div>
                    <div class="form-group">
                        <label for="inputSef" class="control-label">SEF URL</label>
                        <input name="sef" type="text" class="form-control" id="inputSef" value="{$section.sef}">
                    </div>
                    <div class="form-group">
                        <label for="inputMTitle" class="control-label">Title{$lang_label}</label>
                        <textarea name="meta_title" class="form-control translated-field"
                                  id="inputMTitle">{$section.meta_title}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="inputMDescr" class="control-label">Description{$lang_label}</label>
                        <textarea name="meta_description" class="form-control translated-field"
                                  id="inputMDescr">{$section.meta_description}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="inputMKeyw" class="control-label">Keywords{$lang_label}</label>
                        <textarea name="meta_keywords" class="form-control translated-field"
                                  id="inputMKeyw">{$section.meta_description}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="inputOffer" class="control-label">Скидка (в %)</label>
                            <input name="offer" type="text" class="form-control product-property"  id="inputOffer"
                                   value="{$section.offer}" placeholder="00" maxlength=2>
                    </div>
                    <div class="form-group">
                        <div class="center form-buttons">
                            <button class="btn btn-primary" type="submit" disabled="disabled">Сохранить</button>
                            <button class="btn btn-default" type="reset" disabled="disabled">Отменить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {if $section.cat_level==1}
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Параметры товаров</h3>
            </div>
            <div class="panel-body" style="height: 520px; overflow: auto">
                <table id="option-list" class="table table-striped table-fixed">
                    <thead>
                    <tr>
                        <th style="width: 15%;">ID</th>
                        <th>Название{$lang_label}</th>
                        <th class="center" style="width: 18%;">Фильтр</th>
                        <th class="center" style="width: 15%;">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    {/if}
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Описание раздела{$lang_label}</h3>
            </div>
            <div class="panel-body">
                <form id="description-form" class="" method="post">
                    <div class="form-group">
                        <textarea id="description-editor" name="description">{$section.desciption}</textarea>
                    </div>
                    <div class="form-group">
                        <div class="center form-buttons">
                            <button class="btn btn-primary" type="submit" disabled="disabled">Сохранить</button>
                            <button class="btn btn-default" type="reset" disabled="disabled">Отменить</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<script type="text/html" id="tmpl-option-row">
    <tr class="option-row" data-id="<%= id %>">
        <td><%= id %></td>
        <td>
            <input class="form-control title-input" type="text" value="<%= title %>" />
        </td>
        <td class="center">
            <input class="filter-input" type="checkbox" name="filter_<%= id %>" value="1" <%= is_filter ? 'checked="checked"' : '' %> />
        </td>
        <td>
            <button class="save-option-trigger btn btn-xs btn-success" disabled>
                <span class="glyphicon glyphicon-floppy-disk"></span>
            </button>
            <button class="delete-option-trigger btn btn-xs btn-danger">
                <span class="glyphicon glyphicon-remove"></span>
            </button>
        </td>
    </tr>
</script>
<script type="text/html" id="tmpl-new-option-row">
    <tr class="new-option-row" data-id="0">
        <td>&nbsp;</td>
        <td>
            <input class="form-control title-input translated-field" type="text" placeholder="Название параметра" />
        </td>
        <td class="center">
            <input class="filter-input" type="checkbox" value="1" />
        </td>
        <td>
            <button class="add-option-trigger btn btn-xs btn-success" disabled>
                <span class="glyphicon glyphicon-plus-sign"></span>
            </button>
        </td>
    </tr>
</script>
</div>