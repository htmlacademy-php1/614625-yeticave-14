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
 * @param $host адрес сервера
 * @param $username имя пользователя
 * @param $password пароль
 * @param $dbname имя базы данных
 * @return object(mysqli)  возвращает объект подключения к бд
 */
function dbConnect(string $host,string $username,string $password,string $dbname):object
{
    $link = mysqli_connect($host, $username, $password, $dbname);
    mysqli_set_charset($link, "utf8");
    return $link;
}

/*
 * функция возвращает массив с категориями
 * @param $link объект подключения к бд
 */
function getCategories(object $link):array
{
    $sql = 'SELECT `name`,`code` FROM categories';
    $result = mysqli_query($link, $sql);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $categories;
}

/*
 * функция возвращает массив с лотами
 * @param $link объект подключения к бд
 */
function getLots(object $link):array
{
    $sql = 'SELECT lots.name,creation_time,img,begin_price,date_completion,categories.name as category FROM lots
    LEFT JOIN categories on lots.category_id=categories.id';
    $result = mysqli_query($link, $sql);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $lots;
}
