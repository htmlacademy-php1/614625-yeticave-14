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
header("HTTP/1.1 404 Not Found");
$page_content = include_template('404.php',array());

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => '404'
]);

print($layout_content);

