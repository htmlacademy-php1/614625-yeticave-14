<?php
$endLots = getEndLots($link);
if($endLots){
    foreach ($endLots as $lot){
        $winnerUser = getBetByUser($link, $lot['lot_id']);
        if($winnerUser){
            addWinnerLot($link, $winnerUser['user_id'], $lot['lot_id']);
            //отправка почты
        }
        else{
            addCompletedLot($link, $lot['lot_id']);
        }
    }
}