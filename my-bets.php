<?php
require_once __DIR__ . '/init.php';

if(!isset($_SESSION['user_id'])){
    header("Location:/403.php");
    exit();
}

$categories = getCategories($link);
//$lots = getLots($link);

$page_content = include_template('my-bets.php',['categories' => $categories]);

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Мои ставки'
]);

print($layout_content);