<?php
/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * функция подключения к бд
 * @param $config параметры подключения к базам данных сервера
 * @return mysqli возвращает объект подключения к бд
 */
function dbConnect(array $config):mysqli
{
    $link = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
    mysqli_set_charset($link, "utf8");
    mysqli_options($link, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, $config);
    return $link;
}

/*
 * функция возвращает массив с категориями
 * @param mysqli $link объект подключения к бд
 * @return $categories массив с категориями
 */
function getCategories(mysqli $link):array
{
    $sql = 'SELECT id, name, code FROM categories';
    $result = mysqli_query($link, $sql);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $categories;
}

/*
 * функция возвращает массив с лотами
 * @param mysqli $link объект подключения к бд
 * @return $lots массив с лотами
 */
function getLots(mysqli $link):array
{
    $sql = 'SELECT lots.id, lots.name,creation_time,img,begin_price,date_completion,categories.name as category FROM lots
    LEFT JOIN categories on lots.category_id=categories.id';
    $result = mysqli_query($link, $sql);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $lots;
}

/*
 * функция возращает массив с параметрами лота
 * @param mysqli $link объект подключения к бд
 * @param $id id лота
 * @return $lot массив с лотами либо false, если лота не существует
 */
function getLot(mysqli $link,int $id) : array | false
{
    $sql = 'SELECT lots.name, creation_time,img, description, begin_price, date_completion, categories.name as category FROM lots
    LEFT JOIN categories on lots.category_id=categories.id WHERE lots.id=' . $id;
    $result = mysqli_query($link, $sql);
    if ( $result->num_rows===0 ){
        return false;
    }
    $lot = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $lot;
}

/*
 * функция создает лот
 * @param mysqli $link
 * @param $lotFormData массив с данными лота
 * @return id созданного лота
 */
function createLot(mysqli $link,array $lotFormData) : int
{
    $sql = 'INSERT INTO lots (name, creation_time, description, img, begin_price, date_completion, bid_step, user_id, category_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
    $data = [$lotFormData['name'], $lotFormData['creation_time'], $lotFormData['description'], $lotFormData['img'], $lotFormData['begin_price'], $lotFormData['date_completion'],
    $lotFormData['bid_step'], 1, $lotFormData['category']];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    return mysqli_insert_id($link);
}

/*
 * функция ищет email введенный пользователем в форму в базе данных
 * @param mysqli $link
 * @param $email string
 * return bool
 */
function searchUserEmail(mysqli $link, string $email) : bool
{
    $sql = "SELECT email FROM users WHERE email =" . "'" . $email . "'";
    $result = mysqli_query($link, $sql);
    if ( $result->num_rows===0 ){
        return false;
    }
    return true;
}

/*
 * функция создает нового пользователя в бд
 * @param mysqli $link
 * @param $userFormData array с данными пользователя
 * return id добавленной записи в бд
 */
function addUser(mysqli $link, array $userFormData){
    $sql = 'INSERT INTO users (creation_time, name, email, password, contact) VALUES (?, ?, ?, ?, ?)';
    $data = [ date('Y-m-d'), $userFormData['name'], $userFormData['email'], $userFormData['password'], $userFormData['contact']];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    return mysqli_insert_id($link);
}

function searchPassword($link, $password, $email){
    //найти пароль
    //если пароль найден у данного пользователя, то проверить его и вернуть верный он или нет
    $sql = "SELECT password FROM users WHERE email =" . "'" . $email . "'";
    $result = mysqli_query($link, $sql);
    if ( $result->num_rows===0 ){
        return false;
    }
    $passwordFromBd = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    return $passwordFromBd[0]['password'];
}