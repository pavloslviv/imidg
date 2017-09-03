{literal}
    <link rel="stylesheet" href="/lib/js/fancybox/jquery.fancybox.css"/>
    <script type="text/javascript" src="/lib/js/fancybox/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="/admin/template/pages/mailman.js"></script>
<script type="text/javascript ">
    $('.fancy_frame').fancybox({type:'iframe'});
    function sendLetter(e,id){
        var $popup, $table, subscribers,startTime, failCounter= 0,$progress,totalCount=0,currentCount=0;
        if (!confirm('Разослать письмо?')){
            return;
        }
        startTime= new Date();
        $popup = $('<div id="mail_queue">');
        $popup.append('<div class="progress"></div><table class="itemlist">');
        $table = $popup.find('table');
        $progress = $popup.find('.progress');
        $.post('index.php?com=mailman&action=get_contacts',{id : id},function(r){
            if (r.result!='success'){
                alert(r.message);
                return;
            }
            subscribers = r.data;
            totalCount = subscribers.length;
            processBlock();
            $.fancybox({
                content: $popup
            });
        });
        function processBlock(){
            var list;
            if (!subscribers.length){
                alert('Все письма отправлены! Время выполнения '+Math.round(((new Date())-startTime)/1000)+' сек. Ошибок: '+failCounter);
                $.post('index.php?com=mailman&action=finish_sending',{id : id});
                $(e.currentTarget).remove();
                return;
            }
            list = subscribers.splice(0,10);
            $.post('index.php?com=mailman&action=send',{id : id, subscribers: list},function(r){
                if(r.data){
                    jQuery.each(r.data,function(index,item){
                        if(!item.ok){
                            failCounter++;
                        }
                        $table.append('<tr><td>'+item.name+'</td><td>'+item.mail+'</td><td>'+(item.ok?'OK':'')+'</td></tr>');
                        currentCount++;
                    });
                }
                $progress.html('Обработано '+Math.round(currentCount/totalCount*100)+'%');
                setTimeout(processBlock,5000);
            });
        }
    }
</script>
{/literal}
<div class="sixteen columns  panel">
    <div class="pull-right">
        <a href="index.php?com=mailman&action=edit&id=0" class="btn btn-success"><i class="glyphicon glyphicon-envelope"></i> Добавить рассылку</a>
    </div>
    <h4>Рассылки</h4>

    <table class="table table-fixed table-striped">
        <tr>
            <th>Тема</th>
            <th>Файл</th>
            <th>Дата рассылки</th>
            <th>&nbsp;</th>
            <th>Действия</th>
        </tr>
    {foreach from=$mail_list item="mail"}
        <tr>
            <td>{$mail.subject}</td>
            <td>{if $mail.file}{$mail.file}{else}нет{/if}</td>
            <td style="width: 100px; text-align: center">{if $mail.date}{$mail.date|date_format:"%d.%m.%Y"}{else}&nbsp;{/if}</td>
            <td style="text-align: center">
                <a class="send_letter_tigger" href="javascript:void(0)" title="Разослать" data-id="{$mail.id}">
                    <img src="{$HTTP_ROOT}/lib/images/mail.png" alt="Разослать"/>
                </a>
            </td>
            <td>
                <a href="index.php?com=mailman&action=edit&id={$mail.id}"><img src="{$HTTP_ROOT}/lib/images/edit.png"
                                                                            alt="Edit"/></a>
                <a href="index.php?com=mailman&action=delete&id={$mail.id}"><img src="{$HTTP_ROOT}/lib/images/delete.png"
                                                                              alt="Delete"/></a>
            </td>
        </tr>
    {/foreach}
    </table>
</div>
<script id="tmpl_contact_list" type="text/template">
    <div class="row">
        <div class="sixteen columns"> Фильтровать доступные:
            <select id="filter_field">
                <option value="" <%= filter.field=='' ? 'selected' : '' %>>нет</option>
                <option value="name" <%= filter.field=='name' ? 'selected' : '' %>>Имя</option>
                <option value="mail" <%= filter.field=='mail' ? 'selected' : '' %>>E-mail</option>
                <option value="city" <%= filter.field=='city' ? 'selected' : '' %>>Город</option>
            </select>
            <input type="text" id="filter_text" value="<%= filter.text %>">
            <button type="button" id="filter_apply">OK</button>
        </div>
        <div class="eight columns">
            <h4>Доступные контакты</h4>
            <table id="available_contacts" class="itemlist">
                <tr>
                    <th width="30%">Имя</th>
                    <th width="30%">E-mail</th>
                    <th width="30%">Город</th>
                    <th width="10%"><a class="add_all" href="#">Добавить всех</a></th>
                </tr>
                <% _.each(contAvailable,function(user){ %>
                <tr data-id="<%= user.id %>">
                    <td><%= user.name %></td>
                    <td><%= user.mail %></td>
                    <td><%= user.city %></td>
                    <td><a class="move_contact" href="#"><img src="{$HTTP_ROOT}/lib/images/add.png" alt="Добавить"/></a></td>
                </tr>
                <% });%>
            </table>
        </div>
        <div class="eight columns">
            <h4>Выбранные контакты</h4>
            <table id="selected_contacts" class="itemlist">
                <tr>
                    <th width="30%">Имя</th>
                    <th width="30%">E-mail</th>
                    <th width="30%">Город</th>
                    <th width="10%"><a class="remove_all" href="#">Удалить всех</a></th>
                </tr>
                <% _.each(contSelected,function(user){ %>
                <tr data-id="<%= user.id %>">
                    <td><%= user.name %></td>
                    <td><%= user.mail %></td>
                    <td><%= user.city %></td>
                    <td><a class="move_contact" href="#"><img src="{$HTTP_ROOT}/lib/images/delete.png" alt="Удалить"/></a></td>
                </tr>
                <% });%>
            </table>
        </div>
    </div>
    <div style="text-align: center"><button type="button" name="send_mail" id="send_mail">Разослать</button></div>
</script>