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

//if ($_SERVER['REQUEST_METHOD'] === 'POST'){
//    $userFormData = getUserFormData($_POST);
//    $errors = validateSignUpForm($link, $userFormData);
//
//    if (count($errors) === 0)
//    {
//        $userFormData['password'] = password_hash($userFormData['password'], null, $options = []);
//        addUser($link, $userFormData);
//        header("Location:/login.php");
//    }
//}
//if ($_SERVER['REQUEST_METHOD'] === 'GET'){
//    $userFormData = getUserFormData([]);
//    $errors = [];
//}

$page_content = include_template('login.php',['categories' => $categories]);

$layout_content = include_template('layout.php',[
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Вход'
]);

print($layout_content);
