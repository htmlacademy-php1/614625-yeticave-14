<?php
require_once __DIR__ . '/init.php';

if(!isset($_SESSION['user_id'])){
    header("Location:/403.php");
    exit();
}

$categories = getCategories($link);
$bets = getMyBets($link, $_SESSION['user_id']);

// print_r('<pre>');
// var_dump($bets);
// print_r('</pre>');

$page_content = include_template('my-bets.php',['categories' => $categories,'bets' => $bets]);

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Мои ставки'
]);

print($layout_content);