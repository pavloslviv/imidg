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
<h1>Повідомлення з форми зворотнього зв'язку.</h1>

<p>Клієнт:
<table border="1" style="border-collapse: collapse">
    <tr>
        <td>Ім'я</td>
        <td>{$data.name}</td>
    </tr>
    <tr>
        <td>Tелефон</td>
        <td>{$data.phone}</td>
    </tr>
    <tr>
        <td>E-mail</td>
        <td>{$data.mail}</td>
    </tr>
</table>
</p>
<p style="font-weight: bold">Текст повідомлення:</p>

<p>{$data.message}</p>
</body>
</html>