<div class="col-md-8 col-md-offset-2">
    <form class="panel panel-default" action="index.php?com=brand&action=save&id={$item->id}" method="post" enctype="multipart/form-data">
        <div class="panel-heading">{if !$item->id}Создание блока бренда{else}Редактирование блока: &laquo;{$item->get('title')}&raquo;{/if}</div>
        <div class="pane-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Название:</label>
                        <input type="text" name="attributes[title]" class="form-control" value="{$item->get('title')}"/>
                    </div>
                    <div class="form-group">
                        <label class="nine columns clearfix">Ссылка:</label>
                        <input type="text" name="attributes[link]" class="form-control" value="{$item->get('link')}"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="nine columns">Изображение (200:60):</label>
                        <input class="form-control" type="file" name="file"/>
                    </div>
                    <div style="margin-bottom: 15px;">
                        {if $item->get('img')}
                            <img width="220" src="{$HTTP_ROOT}/media/brand/{$item->id}.jpg" alt="Logo">
                        {/if}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group center">
                        <button class="btn btn-primary" type="submit">Сохранить</button>
                        <a class="btn btn-default" href="index.php?com=brand&action=list">Отмена</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
