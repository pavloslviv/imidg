<!DOCTYPE html>
<html>
<head>
    <title>{$meta_title}</title>
    <meta name="description" content="{$meta_descr}">
    <meta name="keywords" content="{$meta_keyw}">

{literal}
    <style type="text/css">
        html, body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: #4c4c4c;
            padding: 50px;
        }

        a img {
            border: none;
        }

        h1, h2, h3 {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            color: #2796b6;
        }

        h1 {
            font-size: 24px;
            font-weight: normal;
        }

        p {
            margin: 0;
        }
    </style>
{/literal}
</head>
<body>
<h1>{$title}</h1>
{$text}
</body>
</html>