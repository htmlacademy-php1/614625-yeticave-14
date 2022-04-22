<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
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
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : null|string {
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
    //return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * функция проверяет нет ли null в значение полей
 * @param array $lotFormData входной $_POST массив
 * @return array $lotFormData отфильтрованные значения на null $_POST
 */
function getLotFormData(array $lotFormData):array
{
    $lotFormData['name'] = ($lotFormData['name']) ?? null;
    $lotFormData['category'] =isset($lotFormData['category']) ? (int)$lotFormData['category'] : null;
    $lotFormData['description']  = $lotFormData['description'] ?? null;
    $lotFormData['begin_price'] = isset($lotFormData['begin_price']) ? (int)$lotFormData['begin_price'] : null;
    $lotFormData['bid_step'] = isset($lotFormData['bid_step']) ? (int)$lotFormData['bid_step'] : null;
    $lotFormData['date_completion'] = $lotFormData['date_completion'] ?? null;

    return $lotFormData;
}


function getErrorForm(array $lotFormData,array $categories,array $file):array
{
    $errors = [
        'name' => validateLengthLot($lotFormData['name']),
        'category' => validateCategory($lotFormData['category'],$categories),
        'description'  => validateLengthLot($lotFormData['description']),
        'begin_price' => validateLotNumber($lotFormData['begin_price']),
        'bid_step' => validateLotNumber($lotFormData['bid_step']),
        'date_completion' => is_date_valid($lotFormData['date_completion']),
        'img'     => validateFile($_FILES)
    ];

    return $errors;
}

//если валидация успешена возвращает null, иначе текст ошибки
//минимум 3 символа максимум 122
function validateLengthLot($valueInput){
    if ( strlen($valueInput)>=3 && strlen($valueInput)<=122 ){
        return null;
    }
    return 'введите значение от 3 до 122 символов';
}

//если валидация успешена возвращает null, иначе текст ошибки
function validateCategory($idCategory,$categories){
    //если id с такой категорией существует вернуть true, если не существует, то false
    foreach ($categories as $category){
        if($category['id'] == $idCategory){
            return null;
        }
    }
    return 'Выбранной категории не существует';
}

//если валидация успешена возвращает null, иначе текст ошибки
function validateLotNumber(int $valueInput){
    if ($valueInput >0){
        return null;
    }
    return 'Введите число, значение числа должно быть больше 0';
}

//если валидация успешена возвращает null, иначе текст ошибки
function validateFile($file){
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

//function validateFileName($link, $imgFile){
//    if ( searchFileName($link, $imgFile['img']['name']) ){
//        echo 'true';
//        exit();
//    }
//    return false;
//}
