<?php
$endLots = getEndLots($link);

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require 'vendor/autoload.php';

$dsn = '';
$transport = Transport::fromDsn($dsn);

// Формирование сообщения
$message = new Email();
$message->to("Pruchkin-Maksim@yandex.ru");
$message->from("mail@giftube.academy");
$message->subject("Просмотры вашей гифки");
$message->text("Вашу гифку «Кот и пылесос» посмотрело больше 1 млн!");

// Отправка сообщения
$mailer = new Mailer($transport);
$mailer->send($message);
if($endLots){
    foreach ($endLots as $lot){
        $winnerUser = getBetByUser($link, $lot['lot_id']);
        if($winnerUser){
            addWinnerLot($link, $winnerUser['user_id'], $lot['lot_id']);
            //отправка почты
            //smtp://login:password@host:port
            //$dsn = 'smtp://4234:32434@smtp.mailtrap.io:2525?encryption=tls&auth_mode=login';

        }
        else{
            addCompletedLot($link, $lot['lot_id']);
        }
    }
}