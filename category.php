<?php
require_once __DIR__ . '/init.php';

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php',['error' => $error]);
}
else{
    $nameCategory = getNameCategory($link, $_GET['id']);
    $categories = getCategories($link);
    $lots = getLots($link);
    
    $page = $_GET['page'] ?? 1;
    $countPage = getCountCategoryPage($link, $config['lotPerPage'], $_GET['id']);

    $lots = categoryLots($link, $config['lotPerPage'], $_GET['id'], $page);

}
$page_content = include_template('category.php',['lots' => $lots,'categories' => $categories, 'nameCategory' => $nameCategory, 'page' => $page, 'countPage' => $countPage]);

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Все лоты в категории ' .$nameCategory
]);

print($layout_content);