<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="UTF-8">
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/lib/skeleton/base.css"/>
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/lib/skeleton/layout.css"/>
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/lib/skeleton/skeleton.css"/>
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/admin/template/css/template.css"/>
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/lib/jquery/ui/redmond/jquery-ui.custom.css"/>
    <link type="text/css" rel="stylesheet" href="{$HTTP_ROOT}/lib/jquery/fancybox/jquery.fancybox.css"/>
    <script type="text/javascript" src="{$HTTP_ROOT}/lib/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="{$HTTP_ROOT}/lib/jquery/ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{$HTTP_ROOT}/lib/jquery/fancybox/jquery.fancybox.pack.js"></script>
{if $editor_enable}{include file="blocks/head_editor.tpl"}{/if}
</head>
<body>
{if $component}{include file="pages/$component.tpl"}{/if}
</body>
</html>