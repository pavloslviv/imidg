<!DOCTYPE html>
<html>
<head>
    {literal}
        <style type="text/css">
            html, body {
                margin: 0;
                padding: 0;
            }

            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 14px;
                color: #212121;
                padding: 50px;
            }

            a img {
                border: none;
            }

            h1, h2, h3 {
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                color: #d15151; /* text color */
            }

            h1 {
                font-size: 24px;
                font-weight: normal;
            }

            p {
                margin: 0;
                margin-bottom: 5px;
            }
        </style>
    {/literal}
</head>
<body>
<h1>Запит на відновлення паролю!</h1>
<p>Для відновлення паролю перейдіть за посиланням <a href="{$data.link}">{$data.link}</a></p>
<p>Якщо ви не запитували відновлення паролю видаліть цей лист. При повторному отриманні зверніться до адміністратора.</p>
</body>
</html>