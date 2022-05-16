<?php
require_once __DIR__ . '/init.php';

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php',['error' => $error]);
}
else{
    $categories = getCategories($link);

    if( !isset($_GET['id']) )
    {
        header( "Location:/404.php", true,302 );
        exit();
    }
    if ( empty($_GET['id']) )
    {
        header( "Location:/404.php", true,302 );
        exit();
    }
    $lot = getLot($link, $_GET['id']);
    if(!$lot){
        header( "Location:/404.php", true,302 );
        exit();
    }
}

$page_content = include_template('lot.php',['lot' => $lot[0]]);

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => $lot[0]['name']
]);

print($layout_content);
