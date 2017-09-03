<link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/lib/jquery/ui/redmond/jquery-ui.custom.css"/>
<script type="text/javascript" src="{$HTTP_ROOT}/lib/jquery/ui/jquery-ui.min.js"></script>
 <link rel="stylesheet" href="{$HTTP_ROOT}/lib/tinymce/plugins/elfinder/css/elfinder.min.css" type="text/css" media="screen"
      charset="utf-8"/>
{*<script src="{$HTTP_ROOT}/lib/tinymce/plugins/elfinder/js/plugin.min.js" type="text/javascript" charset="utf-8"></script>
<script src="{$HTTP_ROOT}/lib/tinymce/plugins/elfinder/js/i18n/elfinder.ru.js" type="text/javascript" charset="utf-8"></script>*}
<script type="text/javascript" src="{$HTTP_ROOT}/lib/tinymce/tinymce.min.js"></script>
{literal}
<script type="text/javascript">
    tinymce.init({
        selector: ".editor",
        theme: "modern",
        height: 300,
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor elfinder"
        ],
        content_css: "/template/css/template.css",
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons | code",
        relative_urls : false,
        remove_script_host : true,
        {/literal}
        document_base_url : '{$HTTP_ROOT}'
        {literal}
    });
</script>
{/literal}