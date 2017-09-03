<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Sergiy
 * Date: 15.01.12
 * Time: 18:30
 * To change this template use File | Settings | File Templates.
 */
require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.pop3.php');
require_once('phpmailer/class.smtp.php');
class Mailer extends PHPMailer
{
    public function __construct()
    {
        //Mandrill setup
        /*$this->IsSMTP(); // set mailer to use SMTP
        $this->Host = "smtp.mandrillapp.com"; // specify main and backup server
        $this->Port = 587; // set the port to use
        $this->SMTPAuth = true; // turn on SMTP authentication
        $this->Username = "imidgcomua"; // your SMTP username or your gmail username
        $this->Password = "wID_Zo58pLloQr9aYxCPjQ"; // your SMTP password or your gmail password
        */
        $this->From = 'no_reply@imidg.com.ua';
        $this->FromName = 'Магазин "Імідж"';
        $this->Sender = 'no_reply@imidg.com.ua';
        $this->Priority = 3;
        $this->CharSet = 'UTF-8';
    }

    /**
     * @param $to адрес получателя
     * @param $toName имя получателя
     * @param $subject тема сообщения
     * @param $body текст сообщения
     * @return boolean   result of senfing
     */
    public function sendSimpleLetter($to, $toName, $subject, $body)
    {
        // Устанавливаем тему письма
        $this->Subject = $subject;

        // Задаем тело письма
        $this->Body = $body;

        // Добавляем адрес в список получателей
        $this->AddAddress($to, $toName);

        $result = $this->Send();
        $this->ClearAddresses();
        $this->ClearAttachments();
        return $result;
    }

}
