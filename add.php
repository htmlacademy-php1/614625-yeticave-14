<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/data.php';

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php',['error' => $error]);
}
else{
    $categories = getCategories($link);
}
//проверяем на null значения
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $lotFormData = getLotFormData($_POST);
    $errors = array_filter(getErrorForm($lotFormData,$categories,$_FILES));

    if (count($errors) > 0){
        $page_content = include_template('add.php',['categories' => $categories, 'errors' => $errors, 'lotFormData' => $lotFormData]);
    }
    else{
        //переложить файл по новому пути, далее записать в бд
        $filename = $_FILES['img'];
        var_dump($_FILES['img']);
        exit();
        loadLot($link, $lotFormData, $_FILES);
        echo 'запись в бд';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $lotFormData = getLotFormData([]);
    $page_content = include_template('add.php',['categories' => $categories, 'lotFormData' => $lotFormData]);
}

$layout_content = include_template('layout.php',[
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Добавление лота'
]);

print($layout_content);
