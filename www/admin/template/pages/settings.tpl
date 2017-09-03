<div class="col-md-12">
    <h2>Настройки сайта</h2>
    {literal}
        <script type="text/javascript">
            $(function () {
                function toggleButton($button,enable){
                    if(enable){
                        $button.removeAttr('disabled')
                                .find('span')
                                .removeClass('glyphicon-floppy-saved')
                                .addClass('glyphicon-floppy-save');
                    } else {
                        $button.attr('disabled','disabled')
                                .find('span')
                                .removeClass('glyphicon-floppy-save')
                                .addClass('glyphicon-floppy-saved');
                    }
                }

                function updateCKEditor(){
                    _.each(CKEDITOR.instances,function(i){
                        i.updateElement();
                    });
                }
                $('.param-input').on('keyup change', function () {
                    toggleButton($(this).closest('tr').find('button'),true);
                });
                $('.param-apply').on('click', function () {
                    var $button = $(this),
                            $input = $button.closest('tr').find('.param-input'),
                            data = {};
                    updateCKEditor();
                    if($button.is(':disabled')){
                        return;
                    }
                    data.section = $input.attr('data-section');
                    data.name = $input.attr('name');
                    data.value = $input.val();
                    $.post('index.php?com=settings&action=save', data, function (r) {
                        if (r.result != 'success') {
                            alert(r.message ? r.message : 'Ошибка сохранения параметра!');
                            return;
                        }
                        alert('Изменения успешно сохранены.');
                        if(!$button.hasClass('always-on')){
                            toggleButton($button,false);
                        }
                    })
                });
            });
        </script>
    {/literal}
    <table class="table table-striped table-fixed">
        <thead>
        <tr>
            <th style="width: 180px;">Параметр</th>
            <th>Значение</th>
            <th style="width: 60px;">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$list item="param"}
            {if $param.hidden==0}
            <tr>
                <td style="text-align: right"><span title="{$param.description}">{$param.title}</span>{$lang_label}</td>
                <td>
                    {if $param.type=='text'}
                        <input class="param-input form-control input-sm translated-field" type="text" name="{$param.name}"
                           data-section="{$param.section}"
                           value="{$param.value}" style="margin: 0;"/>
                    {elseif $param.type=='html'}
                        <textarea id="input_{$param.section}_{$param.name}" class="param-input editor" name="{$param.name}" data-section="{$param.section}">{$param.value}</textarea>
                    {/if}
                </td>
                <td>
                    {if $param.type=='html'}
                    <button class="param-apply btn btn-sm btn-success always-on"><span class="glyphicon glyphicon-floppy-saved"></span></button>
                    {else}
                    <button class="param-apply btn btn-sm btn-success" disabled="disabled"><span class="glyphicon glyphicon-floppy-saved"></span></button>
                    {/if}
                </td>
            </tr>
            {/if}
        {/foreach}
        </tbody>
    </table>
</div>