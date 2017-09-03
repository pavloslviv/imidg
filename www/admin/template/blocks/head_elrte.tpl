<link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/lib/jquery/ui/redmond/jquery-ui.custom.css"/>
<script type="text/javascript" src="{$HTTP_ROOT}/lib/jquery/ui/jquery-ui.min.js"></script>
<link rel="stylesheet" href="{$HTTP_ROOT}/lib/jquery/elrte/css/elrte.min.css" type="text/css" media="screen"
      charset="utf-8"/>
<link rel="stylesheet" href="{$HTTP_ROOT}/lib/jquery/elrte/css/elrte-inner.css" type="text/css" media="screen"
      charset="utf-8"/>
<link rel="stylesheet" href="{$HTTP_ROOT}/lib/jquery/elfinder/css/elfinder.css" type="text/css" media="screen"
      charset="utf-8"/>
<script src="{$HTTP_ROOT}/lib/jquery/elfinder/js/elfinder.min.js" type="text/javascript" charset="utf-8"></script>
<script src="{$HTTP_ROOT}/lib/jquery/elfinder/js/i18n/elfinder.ru.js" type="text/javascript" charset="utf-8"></script>
<script src="{$HTTP_ROOT}/lib/jquery/elrte/elrte.min.js" type="text/javascript" charset="utf-8"></script>
<script src="{$HTTP_ROOT}/lib/jquery/elrte/i18n/elrte.ru.js" type="text/javascript" charset="utf-8"></script>
{literal}
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        var opts = {
            lang:'ru', // set your language
            styleWithCSS:true,
            absoluteURLs: false,
            cssClass : 'el-rte',
            lang     : 'en',
            cssfiles : ['/lib/jquery/elrte/css/elrte-inner.css'],
            //cssfiles : ['/template/css/style.css','/template/css/inner.css','/template/css/editor.css'],
            height:300,
            toolbar:'maxi',
            fmOpen:function (callback) {
                $('<div id="myelfinder" />').elfinder({
                    url:'/lib/jquery/elfinder/connectors/php/connector.php',
                    lang:'en',
                    dialog:{ width:900, modal:true, title:'elFinder - file manager for web' },
                    closeOnEditorCallback:true,
                    editorCallback:callback
                })
            }
        };
        // create editor
        $('.editor').elrte(opts);
    });
</script>
{/literal}