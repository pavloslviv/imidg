{*<link rel="stylesheet" type="text/css" href="/lib/jquery/fullcalendar/fullcalendar.css"/>*}
<script type="text/javascript" src="template/js/schedule_planning.js"></script>
<script type="text/javascript">
    window.currentExpert = {$expertId};
</script>
<div class="container">
    <div style="float: right;" class="btn-group">
        <a class="btn" href="index.php?com=schedule&expert_id={$expertId}&start={$weekStart-1}"><i class="icon-arrow-left"></i> Предыдущая неделя</a>
        <a class="btn" href="index.php?com=schedule&expert_id={$expertId}&start={$weekEnd+1}">Следующая неделя <i class="icon-arrow-right"></i></a>
    </div>
    <h2>Планирование времени ({$weekStart|date_format:"%d.%m.%Y"} &mdash; {$weekEnd|date_format:"%d.%m.%Y"})</h2>

    {if $week}
    <div id="planning_calendar" class="row">
    {foreach from=$week item="int" name="w"}
        {if $smarty.foreach.w.iteration%24==1}
        <div class="day {if $int.start|date_format:"%w"==0 || $int.start|date_format:"%w"==6}span1{else}span2{/if}">
            <div class="title">{$int.start|date_format:"%d.%m.%Y"}</div>
            <ul>
        {/if}
            <li data-id="{$int.id}" data-start="{$int.start}" data-end="{$int.end}">
                <span class="date-block">{$int.start|date_format:"%H:%M"}</span>
                {if $int.exist.status=='free'}<span class="available period" data-id="{$int.exist.id}"></span>
                {elseif $int.exist.status=='hold'}<span class="hold period" data-id="{$int.exist.id}"><i class="icon-info-sign" style="float:right"></i></span>
                {elseif $int.exist.status=='paid'}<span class="paid period" data-id="{$int.exist.id}"><i class="icon-info-sign" style="float:right"></i></span>{/if}
            </li>
            {if $smarty.foreach.w.iteration%24==0}
            </ul>
        </div>
        {/if}
    {/foreach}
    </div>
    {else}
        <div class="alert alert-error">
            <strong>Ошибка!</strong> Данная неделя недоступна для планирования.
        </div>
    {/if}
</div>
<script type="text/template" id="tpl_available_period">
    <span class="available period" data-id="<%= data.id %>"></span>
</script>
<script type="text/template" id="tpl_order_info">
    <span><strong>Имя:</strong><%= data.name %></span>
    <span><strong>Тел.:</strong><%= data.phone %></span>
    <span><strong>Skype:</strong><%= data.skype %></span>
    <span><strong>E-mail:</strong><%= data.mail %></span>
</script>