<?php
require_once __DIR__ . '/init.php';

if(!isset($_SESSION['user_id'])){
    header("Location:/403.php");
    exit();
}

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php',['error' => $error]);
}
else{
    $categories = getCategories($link);
    //$lots = getLots($link);
}

$page_content = include_template('my-bets.php',['categories' => $categories]);

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Мои ставки'
]);

print($layout_content);