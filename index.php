<?php
require_once('helpers.php');
require_once ('data.php');

$page_content = include_template('main.php',['lots' => $lots,'categories' => $categories]);

$layout_content = include_template('layout.php',[
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'yeticave'
]);

print($layout_content);

?>


