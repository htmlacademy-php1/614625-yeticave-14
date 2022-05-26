<?php
$endLots = getEndLots($link);

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require __DIR__ . '/vendor/autoload.php';

if($endLots){
    $dsn = 'smtp://' . $config['mail']['login'] .':' . $config['mail']['password'] .  $config['mail']['host'] .':' . $config['mail']['port'];
    $transport = Transport::fromDsn($dsn);
    foreach ($endLots as $lot){
        $winnerUser = getBetByUser($link, $lot['lot_id']);
        if($winnerUser){
            addWinnerLot($link, $winnerUser['user_id'], $lot['lot_id']);
            $message = new Email();
            $message->to($winnerUser['email']);
            $message->from("keks@phpdemo.ru");
            $message->subject("Ваша ставка победила");
            $emailContent = include_template('email.php',[
                'nameUser' => $winnerUser['name'],
                'nameLot' => $lot['name'],
                'idLot' => $lot['lot_id'],
            ]);
            $message->html($emailContent);
            $mailer = new Mailer($transport);
            $mailer->send($message);   
        }
        else{
            addCompletedLot($link, $lot['lot_id']);
        }
    }
}