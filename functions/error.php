<?php
//подключу потом
function error($error){
    $content = include_template('error.php',['error' => $error]);
    $layout_content = include_template('layout.php',[
        'categories' => [],
        'content'    => $content,
        'title'      => 'Ошибка' 
    ]);
    print($layout_content);
    exit();
}

