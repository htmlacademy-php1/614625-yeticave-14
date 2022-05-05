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
<<<<<<< HEAD
    $userLoginData = getUserLoginData($_POST);
    $errors = validateLoginForm($link, $userLoginData);
    var_dump($errors);
    exit();
=======
>>>>>>> 1710a95f7f8578407d424e1c0294d1f7a4734745
//
//    if (count($errors) === 0)
//    {
//        $userFormData['password'] = password_hash($userFormData['password'], null, $options = []);
//        addUser($link, $userFormData);
//        header("Location:/login.php");
//    }
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
