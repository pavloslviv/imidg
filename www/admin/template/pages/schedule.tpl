<link rel="stylesheet" type="text/css" href="/lib/jquery/fullcalendar/fullcalendar.css" />
<script type="text/javascript" src="/lib/jquery/fullcalendar/fullcalendar.min.js"></script>
<script type="text/javascript" src="template/js/orders.js"></script>
<script type="text/javascript">
    App.orderList = {$orders|json_encode};
</script>
<div class="container">
    <div class="row">
        <div class="span12">
            <h2>Заказы</h2>
            <div id="calendar"></div>
        </div>
    </div>

</div>