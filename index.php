<?php
require_once('helpers.php');
require_once ('data.php');

$page_content = include_template('main.php',['lots' => $lots,'categories' => $categories]);

$layout_content = include_template('layout.php',[
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'user_name'  => $user_name,
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'yeticave'
]);

print($layout_content);

/**
 * Функция округляет число и добавляет знак рубля
 * @param int $price число
 * @return string цена с добавлением знака ₽
 */
function formatNumber(int $price) :string
{
    if ($price<1000){
        return ceil($price) . ' ₽';
    }
    $price = ceil($price);
    return number_format($price, 0, null, ' ') . ' ₽';
}

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
$date1 = date_create_from_format('Y-m-d H:i:s','2019-10-10 14:31:00');
$date2 = date_create_from_format('Y-m-d H:i:s','2019-11-11 12:30:12');
//var_dump($date1);
$diff = date_diff($date1, $date2);
//var_dump($diff);

?>


