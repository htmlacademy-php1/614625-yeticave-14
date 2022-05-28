<?php
require_once __DIR__ . '/init.php';

$categories = getCategories($link);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $userFormData = getUserFormData($_POST);
    $errors = validateSignUpForm($link, $userFormData);

    if (count($errors) === 0)
    {
        $userFormData['password'] = password_hash($userFormData['password'], null, $options = []);
        addUser($link, $userFormData);
        header("Location:/login.php");
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(isset($_SESSION['user_id'])){
        header("Location:/403.php");
        exit();
    }
    $userFormData = getUserFormData([]);
    $errors = [];
}

$page_content = include_template('sign-up.php',[
    'categories' => $categories,
    'userFormData' => $userFormData,
    'errors' => $errors
]);

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Регистрация нового аккаунта'
]);

print($layout_content);
