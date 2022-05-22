<?php
/**
 * функция возвращает отсавшееся время до даты истечения срока лота «ЧЧ: ММ», где первый элемент — целое количество часов до даты, а второй — остаток в минутах.
 * @param string $datalife - дата истечения
 * @param string $currentTime - текущее время
 * @return array $dataRange с параметрами часы и минуты
 */
function get_dt_range(string $datalife, string $currentTime) :array
{
    $diff = strtotime($datalife) - strtotime($currentTime);
    if($diff<0) {
        $dateRange = ['hour' => sprintf("%02d",0), 'minute' => sprintf("%02d",0), 'seconds' => sprintf("%02d",0)];
        return $dateRange;
    }
    $years   = floor($diff / (365*60*60*24));
    $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
    $minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
    $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60)); 
    $hours   = floor(($diff)/ (60*60));
    $dateRange = ['hour' => $hours, 'minute' => $minuts, 'seconds' => $seconds]; 

    // $dateRange['hour'] = sprintf("%02d", $dateRange['hour']);
    // $dateRange['minute'] = sprintf("%02d", $dateRange['minute']);
    // $dateRange['seconds'] = sprintf("%02d", $dateRange['seconds']);

    return $dateRange;
}
