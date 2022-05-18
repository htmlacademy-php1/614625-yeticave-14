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

/**
 * функция проверяет нет ли null в значение полей формы регистрации пользователя
 * @param array $userFormData массив с данными с формы
 * @return array $userFormData массив с отфильтрованными на null значениями
 */
function getUserFormData(array $userFormData) : array
{
    $userFormData['email'] = ($userFormData['email']) ?? null;
    $userFormData['password'] = ($userFormData['password']) ?? null;
    $userFormData['name']  = $userFormData['name'] ?? null;
    $userFormData['contact'] = $userFormData['contact'] ?? null;

    return $userFormData;
}

/**
 * функция валидирует значения в форме регистрации
 * @param mysqli $link
 * @param array $userFormData значения с полей формы
 * @return array $errors массив ошибок
 */
function validateSignUpForm(mysqli $link, array $userFormData) : array
{
    $errors = [
        'email' => validateEmail($link, $userFormData['email']),
        'password' => validateLengthForm($userFormData['password'],5,20),//от 5 до 20 символов
        'name' => validateLengthForm($userFormData['name'], 3, 122),
        'contact' => validateLengthForm($userFormData['contact'],3,122)
    ];
    $errors = array_filter($errors);
    return $errors;
}

/**
 * функция проверяет на корректность введенного email и на уникальность email
 * @param mysqli $link
 * @param string $email
 * @return string | null либо текст ошибки, либо null
 */
function validateEmail(mysqli $link, string $email) : string | null
{
    if (filter_var($email,FILTER_VALIDATE_EMAIL) === false){
        return 'Некорректный адрес электонной почты.';
    }
    if( searchUserEmail($link, $email) ){
        return $email . ' занят другим пользователем';
    }
    return null;
}

/**
 * функция проверяет нет ли null в значение полей формы login
 * @param array $userLoginData массив с данными с формы
 * @return массив с проверенными значениями
 */
function getUserLoginData(array $userLoginData) :array
{
    $userLoginData['email'] = ($userLoginData['email']) ?? null;
    $userLoginData['password'] = ($userLoginData['password']) ?? null;

    return $userLoginData;
}

/**
 * функция валидирует значения в форме login
 * @param mysqli $link
 * @param $userLoginData array с данными с формы
 * @return массив с ошибками
 */
function validateLoginForm(mysqli $link, array $userLoginData) : array
{
    $errors = [
        'email' => checkEmail($link, $userLoginData['email']),
        'password' => checkPassword($link, $userLoginData['password'], $userLoginData['email'])
    ];

    $errors = array_filter($errors);
    return $errors;
}

/**
 * функция проверяет емаил на валидность значения и на существование
 * @param mysqli $link
 * @param $email
 * @return string ошибки или null
 */
function checkEmail(mysqli $link, string $email) : string | null
{
    if (filter_var($email,FILTER_VALIDATE_EMAIL) === false){
        return 'Некорректный адрес электонной почты.';
    }
    if( !searchUserEmail($link, $email) ){
        return 'Такой пользователь не найден';
    }
    return null;
}

/**
 * функция проверяет пароль на валидность значения, на то что он существует для данного пользователя и на правильность
 * @param mysqli $link
 * @param $password 
 * @param $email email
 * @return string ошибки или null
 */
function checkPassword(mysqli $link, string $password, string $email) : string | null
{
    $valueValidateLength = validateLengthForm($password, 5, 20);

    if ( $valueValidateLength !== null){
        return $valueValidateLength;
    }
    $passwordFromBd = searchPassword($link, $email);
    if($passwordFromBd === false){
        return 'пароль не найден для данного пользователя';
    }
    if (!password_verify($password, $passwordFromBd)){
        return 'неверный пароль';
    }; 
    return null;
}

function validateBet($price, $lot, $bidStep, $link){
    //подумать нужна ли проверка по дате, даже если в шаблоне не показывается
    //регистрации
    if($_SESSION['user_id'] === $lot[0]['user_id']){
        return 'Вы создали лот, ставку сделать Вы не можете';
    }
    
    if(empty($price)){
        //$price = 0;
        return 'Введите ставку';
    }

    $lastBetLot = getBetByUser($link, $lot[0]['id']);
    if($lastBetLot === $_SESSION['user_id']){
        return 'Ваша ставка уже сделана';
    }

    if($price<$bidStep){
        return 'Минимальная ставка ' . $bidStep;
    }
    return null;
}