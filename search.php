<?php
require_once __DIR__ . '/init.php';

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php',['error' => $error]);
}
else{
    $categories = getCategories($link);
    $lots = getLots($link);
}

$page_content = include_template('search.php',['lots' => $lots,'categories' => $categories]);

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Результаты поиска по запросу:' . $_GET['search'] 
]);

print($layout_content);