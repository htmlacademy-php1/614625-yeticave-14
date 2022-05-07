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
    $userLoginData = getUserLoginData($_POST);
    $errors = validateLoginForm($link, $userLoginData);

    if (count($errors) === 0)
    {
        session_start();
        echo session_id();
//        $userFormData['password'] = password_hash($userFormData['password'], null, $options = []);
//        addUser($link, $userFormData);
//        header("Location:/login.php");
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $userLoginData = getUserLoginData([]);
    $errors = [];
}

$page_content = include_template('login.php',['categories' => $categories, 'errors' => $errors, 'userLoginData' => $userLoginData]);

$layout_content = include_template('layout.php',[
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Вход'
]);

print($layout_content);