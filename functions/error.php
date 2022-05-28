<?php
/**
 * функция выводит ошибку подключения к БД и убивает процесс выполнения приложения
 * @param string $error сообщение о причине не подключении к БД
 */
function error(string $error) : void
{
    $content = include_template('error.php',['error' => $error]);
    $layout_content = include_template('layout.php',[
        'categories' => [],
        'content'    => $content,
        'title'      => 'Ошибка' 
    ]);
    print($layout_content);
    exit();
}

