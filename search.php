<?php
require_once __DIR__ . '/init.php';

$search = trim($_GET['search']);
$search = filter_var($search);
$categories = getCategories($link);
$page = $_GET['page'] ?? 1;
$countPage = getCountSearchPage($link, $config['lotPerPage'], $search);

$lots = searchLots($link, $config['lotPerPage'], $search, $page);

$page_content = include_template('search.php',[
    'lots' => $lots,
    'categories' => $categories,
    'countPage' => $countPage,
    'page' => $page
]);

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Результаты поиска по запросу:' . $search 
]);

print($layout_content);