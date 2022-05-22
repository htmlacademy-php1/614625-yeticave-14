<?php
//получить все лоты у которых разница дат текущая - дата окончания равна 0 и комплетед = чему то
//запустить цикл перебора массива полученных лотов
//по каждому лоту найти последнюю ставку, если ставки нет продолжаем, если ставка есть, то выставляем виннер id лоту того пользователя кто сделал ставку
$endLots = getEndLots($link);
// print_r('<pre>');
// var_dump($endLots);
// print_r('</pre>');

if($endLots){
    foreach ($endLots as $lot){
        $winneruser = getBetByUser($link, $lot['lot_id']);
        // var_dump($winneruser);
        echo "лот - " . $lot['lot_id'];
        echo '<br>';
        // exit();
        //echo '<br>';
        if($winneruser){
            //если пользователь найден, то мы в таблицу лотов добавляем, что лот закончился completed = 1 и указываем winner_id
            //так же отправляем уведомление пользователю на почту
            echo $winneruser;
            echo '<br>';
        }
        else{
            //иначе добавляем completed = 1
        }
    }
}