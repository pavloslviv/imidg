<!DOCTYPE html>
<html>
<head>
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/admin/template/css/template.css"/>
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/lib/bootstrap/css/bootstrap.css"/>
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/lib/bootstrap/css/bootstrap-responsive.css"/>
    <script type="text/javascript" src="{$HTTP_ROOT}/lib/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="{$HTTP_ROOT}/lib/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="{$HTTP_ROOT}/lib/underscore.js"></script>
    <script type="text/javascript" src="{$HTTP_ROOT}/lib/helpers.js"></script>
{literal}
    <script type="text/javascript">
        $(function () {
            $('.available').click(function () {
                var $popup = $(this).find('.expert_popup')
                window.parent.App.onPeriodClick($popup.html(),$popup.attr('data-date'));
            });
        });
    </script>
    <style type="text/css">
        body {
            padding: 10px;
        }

        .header {
            font-size: 12px;
        }

        .header th {
            font-weight: normal;
        }

        .header th div {
            position: relative;
            height: 40px;
        }

        .header span {
            display: block;
            left: -3px;
            position: absolute;
            top: 10px;
            transform: rotate(-90deg);
            -webkit-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
        }

        .expert_popup {
            display: none;
        }

        #add_period_calendar {
        }

        #add_period_calendar .available {
            background-color: #00ff00
        }

        #add_period_calendar td, #add_period_calendar th {
            padding: 0;
        }

        #add_period_calendar td.row_header {
            padding: 2px 5px;
            width: 70px;
        }
    </style>
{/literal}
</head>
<body>
<div style="overflow: hidden; padding-bottom: 10px">
    <div style="float: right;" class="btn-group">
        <a class="btn" href="index.php?com=orders&action=calendar&start={$weekStart-1}"><i
                class="icon-arrow-left"></i> Предыдущая неделя</a>
        <a class="btn" href="index.php?com=orders&action=calendar&start={$weekEnd+1}">Следующая неделя <i
                class="icon-arrow-right"></i></a>
    </div>
    <h3>{$weekStart|date_format:"%d.%m.%Y"} &mdash; {$weekEnd|date_format:"%d.%m.%Y"}</h3>
</div>
{if $week}
<table id="add_period_calendar" class="table table-bordered table-condensed">
    <tr class="header">
        <td>&nbsp;</td>
        {section loop=$weekStart+86400 start=$weekStart step=3600 name="th"}
            <th>
                <div><span>{$smarty.section.th.index|date_format:"%H:%M"}</span></div>
            </th>
        {/section}
    </tr>
    {foreach from=$week item="int" name="w"}
        {if $smarty.foreach.w.iteration%24==1}
        <tr>
            <td class="row_header">
                {$int.start|date_format:"%d.%m.%Y"}
            </td>
        {/if}
        <td{if $int.experts} class="available"{/if} data-id="{$int.id}" data-start="{$int.start}"
                             data-end="{$int.end}">
            {if $int.experts}
                <div class="expert_popup" data-date="{$int.start|date_format:"%d.%m.%Y %H:%M"}">
                    {foreach from=$int.experts item="expert_period"}
                        <label>
                            <input type="radio" name="expert" value="{$expert_period.id}">
                            {$experts[$expert_period.expert_id].full_name}
                        </label>
                    {/foreach}
                </div>
                {else}
                &nbsp;
            {/if}
        </td>
        {if $smarty.foreach.w.iteration%24==0}
        </tr>
        {/if}
    {/foreach}
</table>
    {else}
<div class="alert alert-error">
    <strong>Ошибка!</strong> Данная неделя недоступна для планирования.
</div>
{/if}
</body>
</html>