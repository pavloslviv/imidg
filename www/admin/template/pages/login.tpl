<div class="container">
    <form id="login" method="post" role="form" class="col-md-3 col-md-offset-5">

        {if $error}
            <div class="alert alert-danger">{$error}</div>
        {/if}

        <div class="form-group"><input class="form-control" name="login" type="text" placeholder="Логин"/></div>
        <div class="form-group"><input class="form-control" name="pass" type="password" placeholder="Пароль"/></div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-log-in"></span> Войти</button>
        </div>
    </form>
</div>
