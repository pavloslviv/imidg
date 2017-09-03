{literal}
<script type="text/javascript">
    $(document).ready(function(){
        $('.delete').bind('click',function(e){
            if (!confirm('Вы действительно хотите удалить клиента?')) e.preventDefault();
        });

        $(document).on('change','#card-list-file-input',function(e){
            var $input = $(this),
                file = this.files[0],
                formData = new FormData(),
                inputCode,
                $inputWrapper = $input.parent();
            formData.append('file',file);
            $('body').append('<div class="ajax-loader"></div>');
            function onSuccess(r){
                $('.ajax-loader').remove();
                if(r.result!=='success'){
                    alert(r.message ? r.message : 'Ошибка загрузки файла!');
                    return;
                }
                inputCode = $inputWrapper.html();
                $input.remove();
                _.delay(function(){
                    alert('Импорт прошел успешно. Добавлено '+ r.data.new+'. Обновлено '+ r.data.update+'.');
                    $inputWrapper.html(inputCode);
                },1);
            }

            function onError(){
                $('.ajax-loader').remove();
                alert('Ошибка загрузки файла!');
            }

            $.ajax({
                url: 'index.php?com=customers&action=import',
                dataType: 'json',
                data: formData,
                success: onSuccess,
                error: onError,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            });

        });
    });
</script>
{/literal}
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="index.php?com=customers&action=edit&id=0" class="btn btn-success"><i class="icon-user"></i> Создать</a>
                <div class="btn-file btn btn-primary">
                    <span class="glyphicon glyphicon-upload"></span>
                    Загрузить список карт
                    <input type="file" name="image" id="card-list-file-input" accept="text/xml"/>
                </div>
            </div>

            <h2>Клиенты</h2>
            <table class="table table-striped table-condensed table-bordered">
                <tr>
                    <th>Имя</th>
                    <th>E-mail</th>
                    <th>Заказы</th>
                    <th style="width: 60px;">Действия</th>
                </tr>
            {foreach from=$customers item="customer"}
                <tr>
                    <td>{$customer.name}</td>
                    <td>{$customer.mail}</td>
                    <td>
                        <a class="btn  btn-xs btn-warning" href="index.php?com=customers&action=payment&id={$customer.id}">
                            <span class="glyphicon glyphicon-shopping-cart"></span> Заказы
                        </a>
                    </td>
                    <td style="text-align: center;">
                        <a class="btn  btn-xs btn-primary" href="index.php?com=customers&action=edit&id={$customer.id}">
                            <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a class="delete btn btn-xs btn-danger"
                           href="index.php?com=customers&action=delete&id={$customer.id}">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </td>
                </tr>
            {/foreach}
            </table>
        </div>
    </div>

</div>