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
<h1>Клієнт звернувся за підтримкою.</h1>

<p>Клієнт:
<table border="1">
    <tr>
        <td>Ім'я</td>
        <td>{$data.name}</td>
    </tr>
    <tr>
        <td>Телефон</td>
        <td>{$data.phone}</td>
    </tr>
    <tr>
        <td>e-mail</td>
        <td>{$data.mail}</td>
    </tr>
</table>
</p>
<p style="font-weight: bold">Тема:</p>

<p>{$data.topic}</p>

<p style="font-weight: bold">Текст повідомлення:</p>

<p>{$data.text}</p>
</body>
</html>