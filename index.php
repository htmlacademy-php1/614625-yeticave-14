<?php
require_once __DIR__ . '/init.php';

require_once __DIR__ . '/getwinner.php';

$categories = getCategories($link);
$lots = getLots($link, 9);

$page_content = include_template('main.php',['lots' => $lots,'categories' => $categories]);

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'yeticave'
]);

print($layout_content);
