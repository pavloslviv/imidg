<script type="text/javascript" xmlns="http://www.w3.org/1999/html" src="template/js/users.js"></script>
<script type="text/javascript">
    App.userList = {$users|json_encode};
</script>
    <span id="new-user-btn" style="float: right;" class="edit btn btn-success btn-sm" title="создать" data-id="0"><i class="icon-ok"></i> Создать</span>
    <h2>Пользователи</h2>

        <table id="user_list" class="paramlist table table-striped table-condensed">
            <thead>
            <tr>
                <th>Фото</th>
                <th>Имя</th>
                <th>Уровень</th>
                <th>Логин</th>
                <th>Телефон</th>
                <th style="width: 155px;">Действия</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$users item="user"}
                <tr data-id="{$user.id}">
                    <td class="center" style="width: 128px;">
                        {if $user.photo==1}
                            <div class="thumbnail" style="display: inline-block;"><img src="/media/users/{$user.id}_small.jpg"/></div><br />
                        {/if}
                        <button  data-id="{$user.id}" class="upload btn btn-xs btn-primary"><span class="glyphicon glyphicon-upload"></span> загрузить</button>
                    </td>
                    <td>{$user.full_name}</td>
                    {*<td>{$user.level}</td>*}
                    <td>{$user.username}</td>
                    <td>{$user.mail}</td>
                    <td>{$user.phone}</td>
                    <td>
                        <span class="edit btn btn-xs btn-success" data-id="{$user.id}"><span class="glyphicon glyphicon-edit"></span></span>
                        <a class="delete btn btn-xs btn-danger" href="index.php?com=users&action=delete&id={$user.id}"><span class="glyphicon glyphicon-remove"></span></a>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
<script id="user_row_template" type="text/template">
    <tr data-id="<%= user.id %>">
        <td class="center" style="width: 128px;">
        <% if (user.photo==1){ %>
            <div class="thumbnail" style="display: inline-block;"><img src="/media/users/<%=user.id %>_small.jpg"/></div><br />
        <% } %>
            <button  data-id="<%=user.id %>" class="upload btn btn-xs btn-primary"><span class="glyphicon glyphicon-upload"></span> загрузить</button>
        </td>
        <td><%= user.full_name %></td>
        <td><%= user.username %></td>
        <td><%= user.mail %></td>
        <td><%= user.phone %></td>
        <td>
            <span class="edit btn btn-xs btn-success" data-id="<%= user.id %>"><span class="glyphicon glyphicon-edit"></span></span>
            <a class="delete btn btn-xs btn-danger" href="index.php?com=users&action=delete&id=<%= user.id %>"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
    </tr>
</script>
<script id="user_edit_template" type="text/template">
    <div class="modal">
    <form role="form" class="modal-dialog">
        <div class="modal-content">
        <input type="hidden" name="id" value="<%= u.id %>"/>
        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title">Пользователь</h4>
        </div>
        <fieldset class="modal-body">
            <div class="form-group">
                <label class="control-label" for="full_name">Полное имя</label>
                <div class="controls">
                    <input type="text"  class="form-control" id="full_name" name="full_name" value="<%= u.full_name %>"/>
                </div>
            </div>
           {* <div class="form-group">
                <label class="control-label" for="level">Уровень доступа</label>
                <div class="controls">
                    <select  class="form-control" id="level" name="level">
                        <option value="consultant">Консультант</option>
                        <option value="admin"<%= u.level=='admin' ? 'selected="selected"' : '' %>>Администратор</option>
                    </select>
                </div>
            </div>*}
            <div class="form-group">
                <label class="control-label" for="username">Логин</label>
                <div class="controls">
                    <input type="text"  class="form-control" id="username" name="username" value="<%= u.username %>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="password">Пароль</label>
                <div class="controls">
                    <input type="text"  class="form-control" id="password" name="pass"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="mail">E-mail</label>
                <div class="controls">
                    <input type="text"  class="form-control" id="mail" name="mail" value="<%= u.mail %>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="phone">Телефон</label>
                <div class="controls">
                    <input type="text"  class="form-control" id="phone" name="phone" value="<%= u.phone %>"/>
                </div>
            </div>
        </fieldset>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Сохранить</button>
        </div>
        </div>
    </form>
    </div>
</script>
<script id="upload_form" type="text/template">
    <div class="modal" id="myModal">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">×</button>
            <h3>Загрузка фото</h3>
        </div>
        <div class="modal-body">
            <p class="center">Выберите файл <input name="title" type="file" accept="image/*"><input type="hidden" name="user_id" value="<%= user_id %>" /></p>

        </div>
        <div class="modal-footer">
            <a href="#" data-dismiss="modal" class="btn">Закрыть</a>
        </div>
    </div>
</script>
