{literal}
<script type="text/javascript">
    $(function(){
        $('.del_scan').on('click',function(e){
            e.preventDefault();
            var href = $(e.currentTarget).attr('href');
            console.log(href);
            if (!confirm('Удалить копию документа?')) {
                return;
            }
            jQuery.get(href,function(data){
                if(data.result=='success'){
                    $('.'+data.data).remove();
                } else {
                    alert(data.message);
                }
            });
        });
    });
</script>
{/literal}
<form action="index.php?com=customers&action=save&id={$customer.id}" class="form-horizontal container" method="post">
<div class="well form-inline">
     E-mail: <input type="text" placeholder="Email" class="span3" name="customer[mail]" value="{$customer.mail}">
     Пароль: <input type="password" placeholder="Password" class="span3" name="customer[pass]">
     Вознаграждение: <div class="input-append"><input type="text" placeholder="Процент выплат" class="span1" name="customer[fee]" value="{$customer.fee}"><span class="add-on">%</span></div>
</div>
<div class="row">
    <fieldset class="span6">
        
        
        <h4>Персональные данные агента</h4>
        <div class="control-group">
            <label for="surname" class="control-label">Фамилия</label>

            <div class="controls">
                <input name="customer[surname]" type="text" id="surname" class="input-xlarge" value="{$customer.surname}">
            </div>
        </div>
        <div class="control-group">
            <label for="name" class="control-label">Имя</label>

            <div class="controls">
                <input name="customer[name]" type="text" id="name" class="input-xlarge" value="{$customer.name}">
            </div>
        </div>
        <div class="control-group">
            <label for="patronymic" class="control-label">Отчество</label>

            <div class="controls">
                <input name="customer[patronymic]" type="text" id="patronymic" class="input-xlarge"
                       value="{$customer.patronymic}">
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">Дата рождения</div>
            <div class="controls">
                <select class="span1" size="1" name="customer[dob_day]">
                {section name="dob_day" loop=32 start=1}
                    <option value="{$smarty.section.dob_day.index}"
                            {if $smarty.section.dob_day.index==$customer.dob.mday}selected="selected"{/if}>{$smarty.section.dob_day.index}</option>
                {/section}
                </select>
                <select class="span1" size="1" name="customer[dob_month]">
                {section name="dob_month" loop=13 start=1}
                    <option value="{$smarty.section.dob_month.index}"
                            {if $smarty.section.dob_month.index==$customer.dob.mon}selected="selected"{/if}>{$smarty.section.dob_month.index}</option>
                {/section}
                </select>
                <select class="span1" size="1" name="customer[dob_year]" style="width: 75px;">
                {section name="dob_year" loop=2013 start=1912}
                    <option value="{$smarty.section.dob_year.index}"
                            {if $smarty.section.dob_year.index==$customer.dob.year}selected="selected"{/if}>{$smarty.section.dob_year.index}</option>
                {/section}
                </select>
            </div>
        </div>
        <div class="control-group">
            <label for="pob" class="control-label">Место рождения</label>

            <div class="controls">
                <input name="customer[pob]" type="text" id="pob" class="input-xlarge"
                       value="{$customer.pob}">
            </div>
        </div>
    </fieldset>
    <fieldset class="span6">
        <h4>Документы</h4>

        <div class="control-group">
            <label for="passp_num" class="control-label">Паспорт(серия, №)</label>

            <div class="controls">
                <input type="text" id="passp_num" name="customer[passp_num]" class="input-xlarge"
                       value="{$customer.passp_num}">
            {if $customer.scan_1!=''}
                <a class="btn btn-primary scan_1" target="_blank" href="index.php?com=customers&action=download_scan&customer_id={$customer.id}&type=scan_1"><i class="icon-download-alt icon-white"></i></a>
                <a class="btn btn-primary scan_1 del_scan" target="_blank" href="index.php?com=customers&action=del_scan&customer_id={$customer.id}&type=scan_1"><i class="icon-trash icon-white"></i></a>
            {/if}
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">Дата видачи паспорта</div>
            <div class="controls">
                <select class="span1" size="1" name="customer[passp_date_day]">
                {section name="passp_date_day" loop=32 start=1}
                    <option value="{$smarty.section.passp_date_day.index}"
                            {if $smarty.section.passp_date_day.index==$customer.passp_date.mday}selected="selected"{/if}>{$smarty.section.passp_date_day.index}</option>
                {/section}
                </select>
                <select class="span1" size="1" name="customer[passp_date_month]">
                {section name="passp_date_month" loop=12}
                    <option value="{$smarty.section.passp_date_month.iteration}"
                            {if $smarty.section.passp_date_month.iteration==$customer.passp_date.mon}selected="selected"{/if}>{$smarty.section.passp_date_month.iteration}</option>
                {/section}
                </select>
                <select class="span1" size="1" name="customer[passp_date_year]" style="width: 75px;">
                {section name="passp_date_year" loop=2013 start=1912}
                    <option value="{$smarty.section.passp_date_year.index}"
                            {if $smarty.section.passp_date_year.index==$customer.passp_date.year}selected="selected"{/if}>{$smarty.section.passp_date_year.index}</option>
                {/section}
                </select>
            </div>
        </div>
        <div class="control-group">
            <label for="passp_issued" class="control-label">Кем выдан паспорт</label>

            <div class="controls">
                <input type="text" id="passp_issued" name="customer[passp_issued]" class="input-xlarge"
                       value="{$customer.passp_issued}">
            {if $customer.scan_2!=''}
                <a class="btn btn-primary scan_2" target="_blank" href="index.php?com=customers&action=download_scan&customer_id={$customer.id}&type=scan_2"><i class="icon-download-alt icon-white"></i></a>
                <a class="btn btn-primary scan_2 del_scan" target="_blank" href="index.php?com=customers&action=del_scan&customer_id={$customer.id}&type=scan_2"><i class="icon-trash icon-white"></i></a>
            {/if}
            </div>
        </div>
        <div class="control-group">
            <label for="tax_number" class="control-label">ИНН</label>

            <div class="controls">
                <input type="text" id="tax_number" name="customer[tax_number]"
                       class="input-xlarge" value="{$customer.tax_number}">
            {if $customer.scan_4!=''}
                <a class="btn btn-primary scan_4" target="_blank" href="index.php?com=customers&action=download_scan&customer_id={$customer.id}&type=scan_4"><i class="icon-download-alt icon-white"></i></a>
                <a class="btn btn-primary scan_4 del_scan" target="_blank" href="index.php?com=customers&action=del_scan&customer_id={$customer.id}&type=scan_4"><i class="icon-trash icon-white"></i></a>
            {/if}
            </div>
        </div>
        <div class="control-group">
            <label for="phone" class="control-label">Телефон</label>

            <div class="controls">
                <input type="text" id="phone" name="customer[phone]"
                       class="input-xlarge" value="{$customer.phone}">
            </div>
        </div>
    </fieldset>
</div>
<div class="row">
    <fieldset class="span6">

        <h4>Адрес прописки</h4>

        <div class="control-group">
            <label for="reg_addr_zip" class="control-label">Почтовый индекс</label>

            <div class="controls">
                <input type="text" id="reg_addr_zip" name="customer[reg_addr_zip]" class="input-xlarge"
                       value="{$customer.reg_addr_zip}">
            {if $customer.scan_3!=''}
                <a class="btn btn-primary scan_3" target="_blank" href="index.php?com=customers&action=download_scan&customer_id={$customer.id}&type=scan_3"><i class="icon-download-alt icon-white"></i></a>
                <a class="btn btn-primary scan_3 del_scan" target="_blank" href="index.php?com=customers&action=del_scan&customer_id={$customer.id}&type=scan_3"><i class="icon-trash icon-white"></i></a>
            {/if}
            </div>
        </div>
        <div class="control-group">
            <label for="reg_addr_region" class="control-label">Область</label>

            <div class="controls">
                <input type="text" id="reg_addr_region" name="customer[reg_addr_region]" class="input-xlarge"
                       value="{$customer.reg_addr_region}">
            </div>
        </div>
        <div class="control-group">
            <label for="reg_addr_district" class="control-label">Район</label>

            <div class="controls">
                <input type="text" id="reg_addr_district" name="customer[reg_addr_district]" class="input-xlarge"
                       value="{$customer.reg_addr_district}">
            </div>
        </div>
        <div class="control-group">
            <label for="reg_addr_city" class="control-label">Населённый пункт</label>

            <div class="controls">
                <input type="text" id="reg_addr_city" name="customer[reg_addr_city]" class="input-xlarge"
                       value="{$customer.reg_addr_city}">
            </div>
        </div>
        <div class="control-group">
            <label for="reg_addr_street" class="control-label">Улица</label>

            <div class="controls">
                <input type="text" id="reg_addr_street" name="customer[reg_addr_street]" class="input-xlarge"
                       value="{$customer.reg_addr_street}">
            </div>
        </div>
        <div class="control-group">
            <label for="reg_addr_bldg" class="control-label">Дом</label>

            <div class="controls">
                <input type="text" id="reg_addr_bldg" name="customer[reg_addr_bldg]" class="span1"
                       value="{$customer.reg_addr_bldg}">
            </div>
        </div>
        <div class="control-group">
            <label for="reg_addr_corp" class="control-label">Корпус</label>

            <div class="controls">
                <input type="text" id="reg_addr_corp" name="customer[reg_addr_corp]" class="span1"
                       value="{$customer.reg_addr_corp}">
            </div>
        </div>
        <div class="control-group">
            <label for="reg_addr_apt" class="control-label">Кв.</label>

            <div class="controls">
                <input type="text" id="reg_addr_apt" name="customer[reg_addr_apt]" class="span1"
                       value="{$customer.reg_addr_apt}">
            </div>
        </div>

    </fieldset>
    <fieldset class="span6">
        <h4>Адрес проживания</h4>

        <div class="control-group">
            <label for="addr_zip" class="control-label">Почтовый индекс</label>

            <div class="controls">
                <input type="text" id="addr_zip" name="customer[addr_zip]" class="input-xlarge"
                       value="{$customer.addr_zip}">
            </div>
        </div>
        <div class="control-group">
            <label for="addr_region" class="control-label">Область</label>

            <div class="controls">
                <input type="text" id="addr_region" name="customer[addr_region]" class="input-xlarge"
                       value="{$customer.addr_region}">
            </div>
        </div>
        <div class="control-group">
            <label for="addr_district" class="control-label">Район</label>

            <div class="controls">
                <input type="text" id="addr_district" name="customer[addr_district]" class="input-xlarge"
                       value="{$customer.addr_district}">
            </div>
        </div>
        <div class="control-group">
            <label for="addr_city" class="control-label">Населённый пункт</label>

            <div class="controls">
                <input type="text" id="addr_city" name="customer[addr_city]" class="input-xlarge"
                       value="{$customer.addr_city}">
            </div>
        </div>
        <div class="control-group">
            <label for="addr_street" class="control-label">Улица</label>

            <div class="controls">
                <input type="text" id="addr_street" name="customer[addr_street]" class="input-xlarge"
                       value="{$customer.addr_street}">
            </div>
        </div>
        <div class="control-group">
            <label for="addr_bldg" class="control-label">Дом</label>

            <div class="controls">
                <input type="text" id="addr_bldg" name="customer[addr_bldg]" class="span1"
                       value="{$customer.addr_bldg}">
            </div>
        </div>
        <div class="control-group">
            <label for="addr_corp" class="control-label">Корпус</label>

            <div class="controls">
                <input type="text" id="addr_corp" name="customer[addr_corp]" class="span1"
                       value="{$customer.addr_corp}">
            </div>
        </div>
        <div class="control-group">
            <label for="addr_apt" class="control-label">Кв.</label>

            <div class="controls">
                <input type="text" id="addr_apt" name="customer[addr_apt]" class="span1"
                       value="{$customer.addr_apt}">
            </div>
        </div>

    </fieldset>
</div>
<h4>Выплата вознаграждения</h4>
<div class="row">
    <fieldset class="span6">
        <div class="control-group">
            <label for="bank_title" class="control-label">Банк</label>

            <div class="controls">
                <input type="text" id="bank_title" name="customer[bank_title]" class="span4"
                       value="{$customer.bank_title}">
            </div>
        </div>
        <div class="control-group">
            <label for="bank_mfo" class="control-label">МФО</label>

            <div class="controls">
                <input type="text" id="bank_mfo" name="customer[bank_mfo]" class="span4"
                       value="{$customer.bank_mfo}">
            </div>
        </div>
        <div class="control-group">
            <label for="bank_edrpou" class="control-label">ЕДРПОУ</label>

            <div class="controls">
                <input type="text" id="bank_edrpou" name="customer[bank_edrpou]" class="span4"
                       value="{$customer.bank_edrpou}">
            </div>
        </div>
    </fieldset>
    <fieldset class="span6">
        <div class="control-group">
            <label for="bank_acc" class="control-label">№ счета</label>

            <div class="controls">
                <input type="text" id="bank_acc" name="customer[bank_acc]" class="span4"
                       value="{$customer.bank_acc}">
            </div>
        </div>
        <div class="control-group">
            <label for="bank_card" class="control-label">Текущий (карточный) счет</label>

            <div class="controls">
                <input type="text" id="bank_card" name="customer[bank_card]" class="span4"
                       value="{$customer.bank_card}">
            </div>
        </div>
    </fieldset>
</div>
<div class="row">
    <fieldset class="span12">
        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Сохранить</button>
            <a class="btn" href="index.php?com=customers&action=list">Отменить</a>
        </div>
    </fieldset>
</div>
</form>
