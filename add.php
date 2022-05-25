<?php
require_once __DIR__ . '/init.php';

if(!isset($_SESSION['user_id'])){
    header("Location:/403.php");
    exit();
}

$categories = getCategories($link);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $lotFormData = getLotFormData($_POST);
    $errors = validateLotForm($lotFormData,$categories,$_FILES);

    if (count($errors) === 0)
    {
        $img = uploadFile($_FILES);
        $lotFormData['img'] =  $img;
        $lotFormData['creation_time'] = date('Y-m-d h:i:s');
        $lotId = createLot($link, $lotFormData, $_SESSION['user_id']);
        header("Location:/lot.php?id=" . $lotId);
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $lotFormData = getLotFormData([]);
    $errors = [];
}

$page_content = include_template('add.php',['categories' => $categories, 'errors' => $errors, 'lotFormData' => $lotFormData]);

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Добавление лота'
]);

print($layout_content);
