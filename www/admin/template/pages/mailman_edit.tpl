{literal}
    <script type="text/javascript">
        (function(){
            var onFileSelect = function(){
                var file = this.files[0];
                if(!file){
                    return;
                }
                if(file.size>15728640){
                    alert('Размер файла превышает 15МБ, для надежной доставки рекомендуется прикреплять файлы не больше 15МБ');
                }
            }
            $(document).ready(function(){
                $('#attachment_file').on('change',onFileSelect);
            });

        })();
    </script>
{/literal}
<form action="index.php?com=mailman&action=save&id={$mail->id}" method="post" enctype="multipart/form-data">
    <h4>{if !$mail->id}Создание рассылки{else}Редактирование рассылки: &laquo;{$mail->get('subject')}&raquo;{/if}</h4>
    <div class="row">
        <label class="col-md-4">От:<br/>
            <input type="text" name="attributes[from]" class="form-control" value="{if $mail->get('from')}{$mail->get('from')}{else}info@imidg.com.ua{/if}"/>
        </label>
        <label class="col-md-4">Тема:<br/>
            <input type="text" name="attributes[subject]" class="form-control" value="{$mail->get('subject')}"/>
        </label>
        <label class="col-md-4">Файл:<br/>
            {if $mail->get('file')!=''}{$mail->get('file')}<br>{/if}
            <input id="attachment_file" type="file" name="file" class="form-control"/>
        </label>
    </div>
    <div class="row">
        <label class="col-md-12">Текст<br/>
            <textarea name="attributes[body]" class="editor">{$mail->get('body')}</textarea>
        </label>
    </div>


    <div class="fifteen columns">
        <button class="btn btn-primary" type="submit">Сохранить</button>
        <a class="btn btn-default" href="index.php?com=mailman&action=list">Отмена</a>
    </div>
</form>