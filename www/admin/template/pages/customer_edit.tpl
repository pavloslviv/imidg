<form action="index.php?com=customers&action=save&id={$customer.id}" class="form-horizontal container" method="post">
    <h4>Клиент</h4>
    <div class="col-md-4">
        <div class="form-group">
            <label for="inputName" class="col-lg-2 control-label">Имя</label>

            <div class="col-lg-10">
                <input name="customer[name]" type="text" class="form-control" id="inputName" value="{$customer.name}">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail" class="col-lg-2 control-label">Email</label>

            <div class="col-lg-10">
                <input name="customer[mail]" type="email" class="form-control" id="inputEmail" value="{$customer.mail}">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPhone" class="col-lg-2 control-label">Телефон</label>

            <div class="col-lg-10">
                <input name="customer[phone]" type="phone" class="form-control" id="inputPhone" value="{$customer.phone}">
            </div>
        </div>
        <div class="form-group">
            <label for="inputLogin" class="col-lg-2 control-label">Логин</label>

            <div class="col-lg-10">
                <input name="customer[login]" type="text" class="form-control" id="inputLogin" value="{$customer.login}">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword" class="col-lg-2 control-label">Пароль</label>

            <div class="col-lg-10">
                <input name="customer[pass]" type="password" class="form-control" id="inputPassword">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-offset-2">
                <button class="btn btn-primary" type="submit">Сохранить</button>
                <a class="btn btn-default" href="index.php?com=customers&action=list">Отменить</a>
            </div>
        </div>
    </div>

</form>
