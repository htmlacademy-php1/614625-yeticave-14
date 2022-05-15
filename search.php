<?php
require_once __DIR__ . '/init.php';

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php',['error' => $error]);
}
else{
    $_GET['search'] = trim($_GET['search']);
    $categories = getCategories($link);
    $page = $_GET['page'] ?? 1;
    $countPage = getCountSearchPage($link, $config['lotPerPage'], $_GET['search']);
    
    $lots = searchLots($link, $config['lotPerPage'], $_GET['search'], $page);
}

$page_content = include_template('search.php',['lots' => $lots,'categories' => $categories,'countPage' => $countPage, 'page' => $page]);

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Результаты поиска по запросу:' . $_GET['search'] 
]);

print($layout_content);