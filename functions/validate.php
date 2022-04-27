<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД' так же проверяет дату на то, что она больше текущей
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return null в случае успешной проверки, иначе false
 */
function is_date_valid(string $date) : ?string
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);
    if (!$dateTimeObj){
        return 'Введите дату в указаном формате ГГГГ-ММ-ДД';
    }
    $rangeTime = get_dt_range($date,date('Y-m-d'));
    if($rangeTime['hour']>0 || $rangeTime['minute']>0){
        return null;
    }
    return 'Дата окончания торгов должна быть больше текущей даты';
}

/**
 * функция проверяет нет ли null в значение полей формы лота
 * @param array $lotFormData входной $_POST массив
 * @return array $lotFormData отфильтрованные значения на null $_POST
 */
function getLotFormData(array $lotFormData) : array
{
    $lotFormData['name'] = ($lotFormData['name']) ?? null;
    $lotFormData['category'] =isset($lotFormData['category']) ? (int)$lotFormData['category'] : null;
    $lotFormData['description']  = $lotFormData['description'] ?? null;
    $lotFormData['begin_price'] = isset($lotFormData['begin_price']) ? (int)$lotFormData['begin_price'] : null;
    $lotFormData['bid_step'] = isset($lotFormData['bid_step']) ? (int)$lotFormData['bid_step'] : null;
    $lotFormData['date_completion'] = $lotFormData['date_completion'] ?? null;

    return $lotFormData;
}

/**
 *  функция проверяет ошибки заполнения полей формы
 * @param array $lotFormData входной массив данных с формы
 * @param array $categories массив с категориями
 * @param array $file массив с даннными о файле
 * @return array $errors массив с ошибками
 */
function validateLotForm(array $lotFormData,array $categories,array $file) : array
{
    $errors = [
        'name' => validateLengthForm($lotFormData['name'], 3, 122),
        'category' => validateCategory($lotFormData['category'],$categories),
        'description'  => validateLengthForm($lotFormData['description'],3,122),
        'begin_price' => validateLotNumber($lotFormData['begin_price']),
        'bid_step' => validateLotNumber($lotFormData['bid_step']),
        'date_completion' => is_date_valid($lotFormData['date_completion']),
        'img'     => validateFile($_FILES)
    ];
    $errors = array_filter($errors);
    return $errors;
}

/**
 * функция проверяет длину введенной строки, строка должна находиться в пределах от 3 до 122 символов
 * @param $valueInput string
 * @param int $minValue минимальное-допустимое значение строки
 * @param int $maxValue максимально-допустимое значение строки
 * @return null в случае правильной длины, иначе strint текст ошибки
 */
function validateLengthForm(string $valueInput,int $minValue,int $maxValue) : null | string
{
    if ( strlen($valueInput)>=$minValue && strlen($valueInput)<=$maxValue ){
        return null;
    }
    return 'введите значение от ' . $minValue . ' до ' . $maxValue . ' символов';
}

/**
 * функция проверяет существует ли выбранная категория в массиве категорий, полученных из бд
 * @param $idCategory id выбранной категории
 * @param $categories array с категориями
 * return null в случае, если категория существует, иначе string - текст с ошибкой
 */
function validateCategory(int $idCategory,array $categories) :null | string
{
    foreach ($categories as $category){
        if($category['id'] == $idCategory){
            return null;
        }
    }
    return 'Выбранной категории не существует';
}

/**
 *  функция проверяет больше 0 входящее число
 * @param int $valueInput число входящее
 * @return null в случае успешной проверки, иначе string текст ошибки
 */
function validateLotNumber(int $valueInput) : null | string
{
    if ($valueInput >0){
        return null;
    }
    return 'Введите число, значение числа должно быть больше 0';
}

/**
 * функция проверяет файл на его формат.Допустимые форматы файлов: jpg, jpeg, png
 * @param $file array входящий массив с файлом
 * @return null в случае успешной проверки, иначе string текст ошибки
 */
function validateFile(array $file) : null | string
{
    if ($file['img']['error']===4){
        return 'Загрузите файл. Допустимые форматы файлов: jpg, jpeg, png';
    }
    $typeFile = ['image/png','image/jpeg','image/jpeg'];
    $fileMineType = mime_content_type($file['img']['tmp_name']);
    if (!in_array( $fileMineType, $typeFile)){
        return 'Неверный формат файла. Допустимые форматы файлов: jpg, jpeg, png';
    }
    return null;
}

/*
 * функция проверяет нет ли null в значение полей формы регистрации пользователя
 */
function getUserFormData(array $userFormData) :array
{
    $userFormData['email'] = ($userFormData['email']) ?? null;
    $userFormData['password'] = ($userFormData['password']) ?? null;
    $userFormData['name']  = $userFormData['name'] ?? null;
    $userFormData['contact'] = $userFormData['contact'] ?? null;

    return $userFormData;
}

/*
 *
 */
function validateSignUpForm($link, $userFormData){
    $errors = [
        'email' => validateEmail($link, $userFormData['email']),
        'password' => validateLengthForm($userFormData['password'],5,20),//от 5 до 20 символов
        'name' => validateLengthForm($userFormData['name'], 3, 122),
        'contact' => validateLengthForm($userFormData['contact'],3,122)
    ];
    $errors = array_filter($errors);
    return $errors;
}

function validateEmail($link, $email){
    if (filter_var($email,FILTER_VALIDATE_EMAIL) === false){
        return 'Некорректный адрес электонной почты.';
    }
    if( searchUserEmail($link, $email) ){
        return $email . ' занят другим пользователем';
    }
    return null;
}

function getUserLoginData($userLoginData){

}
