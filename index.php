<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/data.php';

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php',['error' => $error]);
}
else{
    //запрос на показ категорий
    $sql = 'SELECT `name`,`code` FROM categories';

    $result = mysqli_query($link, $sql);

    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

    //получение лотов
    $sql = 'SELECT lots.name,creation_time,img,begin_price,date_completion,categories.name as category FROM lots
    LEFT JOIN categories on lots.category_id=categories.id';

    $result = mysqli_query($link, $sql);

    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
}
$page_content = include_template('main.php',['lots' => $lots,'categories' => $categories]);

$layout_content = include_template('layout.php',[
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'yeticave'
]);

print($layout_content);
