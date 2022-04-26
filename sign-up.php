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

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $userFormData = getUserFormData($_POST);
    $errors = validateSignUpForm($link, $userFormData);

//Для хранения пароля в БД, его предварительно нужно обработать встроенной функцией password_hash.
//    if (count($errors) === 0)
//    {
//        $img = uploadFile($_FILES);
//        $lotFormData['img'] =  $img;
//        $lotFormData['creation_time'] = date('Y-m-d');
//        $lotId = createLot($link, $lotFormData);
//        header("Location:/lot.php?id=" . $lotId);
//    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $userFormData = getUserFormData([]);
    $errors = [];
}

$page_content = include_template('sign-up.php',['categories' => $categories, 'userFormData' => $userFormData, 'errors' => $errors]);

$layout_content = include_template('layout.php',[
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Регистрация нового аккаунта'
]);

print($layout_content);
