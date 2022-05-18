<?php
require_once __DIR__ . '/init.php';

$categories = getCategories($link);
$lots = getLots($link);

header("HTTP/1.1 403 forbidden");
$page_content = include_template('403.php',array());

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => '403'
]);

print($layout_content);