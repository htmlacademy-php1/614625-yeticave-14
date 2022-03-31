<?php
require_once('helpers.php');
require_once ('data.php');

$page_content = include_template('main.php',['lots' => $lots,'categories' => $categories]);

$layout_content = include_template('layout.php',[
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'yeticave'
]);

print($layout_content);

/**
 * функция возвращает «ЧЧ: ММ», где первый элемент — целое количество часов до даты, а второй — остаток в минутах.
 * @param string $datalife
 * @return string
 */
function get_dt_range(string $datalife) :array
{
    //текущая дата/время  2019-10-10 14:30

    //return $diff;
    return 'string';
}
$date1 = '2019-10-10 14:31';
$date2 ='2019-11-10 14:31';

$diff = abs(strtotime($date2) - strtotime($date1));
$years   = floor($diff / (365*60*60*24));
$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
$days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
$hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
$minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
$hours   = floor(($diff)/ (60*60));
//echo $years;
echo $hours . '<br>';
echo $minuts;
$dateRange = ['hour' => 0, 'minute' => 0];

?>


