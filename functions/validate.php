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
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);
    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * функция проверяет нет ли null в значение полей
 * @param array $lotFormData входной $_POST массив
 * @return array $lotFormData отфильтрованные значения на null $_POST
 */
function getLotFormData(array $lotFormData):array
{
    $lotFormData['lot-name'] = isset($_POST['lot-name']) ? $_POST['lot-name'] : null;
    $lotFormData['category'] = isset($_POST['category']) ? $_POST['category'] : null;
    $lotFormData['message']  = isset($_POST['message']) ? $_POST['message'] : null;
    $lotFormData['lot-rate'] = isset($_POST['lot-rate']) ? (int)$_POST['lot-rate'] : null;
    $lotFormData['lot-step'] = isset($_POST['lot-step']) ? (int)$_POST['lot-step'] : null;
    $lotFormData['lot-date'] = isset($_POST['lot-date']) ? $_POST['lot-date'] : null;

    return $lotFormData;
}

//если валидация успешена, то возвращает true, иначе false
function validateLengthLot(string $valueInput){
    if ( strlen($valueInput)>0 ){
        return true;
    }
    return false;
}

//если валидация успешена, то возвращает true, иначе false
function validateCategory($idCategory,$categories){
    //если id с такой категорией существует вернуть true, если не существует, то false
    foreach ($categories as $category){
        if($category['id'] == $idCategory){
            return true;
        }
    }
    return false;
}

//если валидация успешена, то возвращает true, иначе false
function validateLotNumber(int $valueInput){
    if ($valueInput >0){
        return true;
    }
    return false;
}

//если валидация успешена, то возвращает true, иначе false
function validateFile($file){

}
