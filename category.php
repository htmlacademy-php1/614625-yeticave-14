<?php
require_once __DIR__ . '/init.php';

$categories = getCategories($link);

$page = $_GET['page'] ?? 1;
$nameCategory = '';
$countPage = 0;
$lots = [];
if (isset($_GET['id'])){
    if($_GET['id']){
        $nameCategory = getNameCategory($link, $_GET['id']);
        $countPage = getCountCategoryPage($link, $config['lotPerPage'], $_GET['id']);
        $lots = categoryLots($link, $config['lotPerPage'], $_GET['id'], $page);
    }
}

$page_content = include_template('category.php',[
    'lots' => $lots,
    'categories' => $categories,
    'nameCategory' => $nameCategory,
    'page' => $page,
    'countPage' => $countPage
]);

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Все лоты в категории ' .$nameCategory
]);

print($layout_content);