(function(app){
    app.loadTemplates = function () {
        app.tpl = {
            availPeriod: _.template($('#tpl_available_period').html()),
            orderInfo: _.template($('#tpl_order_info').html())
        };
    }

    app.onPeriodClick =function(event){
        var $item = $(event.currentTarget),
            $avPeriod = $item.find('.available.period');
        if ($avPeriod.length){
            $.post('index.php?com=schedule&action=delete',{period_id: $avPeriod.attr('data-id'),expert_id: window.currentExpert},function(r){
                if (r.result!='success') {
                    alert(r.message);
                    return
                }
                $avPeriod.fadeOut('fast',function(){
                    $avPeriod.remove();
                });
            });
        } else {
            if ($item.find('span.period').length){
                var $orderItem = $item.find('span.period');
                if ($orderItem.hasClass('expand')){
                    $orderItem.html('<i class="icon-info-sign" style="float:right"></i>').removeClass('expand');
                } else {
                    $.post('index.php?com=schedule&action=info', {period_id: $orderItem.attr('data-id'),expert_id: window.currentExpert}, function(r){
                        "use strict";
                        if(r.result=='success'){
                            $orderItem.html(app.tpl.orderInfo({data:r.data})).addClass('expand');
                        }
                    });
                    $item.find('span.period').popover('show');
                }
                return;
            }
            $.post('index.php?com=schedule&action=add',{period_id: $item.attr('data-id'),expert_id: window.currentExpert},function(r){
                if (r.result!='success') {
                    alert(r.message);
                    return
                }
                $item.append(app.tpl.availPeriod({data: r.data}));
            });
        }
    }

    app.init = function(e){
        app.loadTemplates();
        $('#planning_calendar li').live('click',app.onPeriodClick);
        /*$('.hold.period, .paid.period').popover({
            title: 'Информация о заказе',
            placement: 'bottom',
            trigger: 'manual'
        })*/
    }
    window.App=app;
})({});
$(App.init);