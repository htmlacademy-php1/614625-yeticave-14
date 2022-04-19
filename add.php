<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/data.php';

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php',['error' => $error]);
}
else{
    $categories = getCategories($link);
//   print_r('<pre>');
//    var_dump($categories);
//    print_r('</pre>');
//    exit();
}
//проверяем на null значения
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $lotFormData = getLotFormData($_POST);
//    var_dump($lotFormData);
//    exit();
    $errors = [
        'lot-name' => validateLengthLot($lotFormData['lot-name']),
        'category' => validateCategory($lotFormData['category'],$categories),
        'message'  => validateLengthLot($lotFormData['message']),
        'lot-rate' => validateLotNumber($lotFormData['lot-rate']),
        'lot-step' => validateLotNumber($lotFormData['lot-step']),
        'lot-date' => is_date_valid($lotFormData['lot-date']),
        'file'     => validateFile($_FILES)
    ];
    $errors = array_filter($errors, function ($error){
        if($error === false){
            return true;
        }
    });
    if (count($errors) > 0){
        $page_content = include_template('add.php',['categories' => $categories, 'errors' => $errors, 'lotFormData' => $lotFormData]);
    }
    else{
        echo 'запись в бд';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $lotFormData = getLotFormData([]);
//    var_dump($lotFormData);
//    exit();
    $page_content = include_template('add.php',['categories' => $categories, 'lotFormData' => $lotFormData]);
}

//если я проверяю форму и есть ошибки, то передаю ошибки в шаблон, иначе записываю информацию в бд и перехожу в детальную страницу

//$page_content = include_template('add.php',['categories' => $categories]);

$layout_content = include_template('layout.php',[
    'is_auth'    => $is_auth,
    'user_name'  => $user_name,
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => 'Добавление лота'
]);

print($layout_content);
