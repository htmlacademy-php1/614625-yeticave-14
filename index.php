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

function get_dt_range(string $datalife) :string
{
    return $datalife;
}
echo get_dt_range('2019-10-11 12:30:12');
?>


