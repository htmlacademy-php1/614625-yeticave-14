<?php
require_once __DIR__ . '/init.php';

$categories = getCategories($link);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $userLoginData = getUserLoginData($_POST);
    $errors = validateLoginForm($link, $userLoginData);

    if (count($errors) === 0)
    {

        $userData = searchUser($link, $userLoginData['email']);
        $_SESSION['name'] = $userData[0]['name'];
        $_SESSION['user_id'] = $userData[0]['id'];

        header("Location:/");
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(isset($_SESSION['user_id'])){
        header("Location:/403.php");
        exit();
    }
    $userLoginData = getUserLoginData([]);
    $errors = [];
}

$page_content = include_template('login.php',[
    'categories' => $categories,
    'errors' => $errors,
    'userLoginData' => $userLoginData
]);

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Вход'
]);

print($layout_content);