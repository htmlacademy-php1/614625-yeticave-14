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
function dbConnect(array $config) : mysqli
{
    $link = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
    if(!$link){
        error(mysqli_connect_error());
    }
    mysqli_set_charset($link, "utf8");
    mysqli_options($link, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, $config);
    return $link;
}

/**
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

/**
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

/**
 * функция возращает массив с параметрами лота
 * @param mysqli $link объект подключения к бд
 * @param $id id лота
 * @return $lot массив с лотами либо false, если лота не существует
 */
function getLot(mysqli $link,int $id) : array | false
{
    $sql = 'SELECT lots.id, lots.name, creation_time,img, description, begin_price, date_completion, categories.name as category,user_id,bid_step FROM lots
    LEFT JOIN categories on lots.category_id=categories.id WHERE lots.id=' . $id;
    $result = mysqli_query($link, $sql);
    if ( $result->num_rows===0 ){
        return false;
    }
    $lot = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $lot;
}

/**
 * функция создает лот
 * @param mysqli $link
 * @param $lotFormData массив с данными лота
 * @param $user_id id пользователя
 * @return id созданного лота
 */
function createLot(mysqli $link,array $lotFormData,int $user_id) : int
{
    $sql = 'INSERT INTO lots (name, creation_time, description, img, begin_price, date_completion, bid_step, user_id, category_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
    $data = [$lotFormData['name'], $lotFormData['creation_time'], $lotFormData['description'], $lotFormData['img'], $lotFormData['begin_price'], $lotFormData['date_completion'],
    $lotFormData['bid_step'], $user_id, $lotFormData['category']];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    return mysqli_insert_id($link);
}

/**
 * функция ищет email введенный пользователем в форму в базе данных
 * @param mysqli $link
 * @param $email string
 * @return bool
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

/**
 * функция создает нового пользователя в бд
 * @param mysqli $link
 * @param $userFormData array с данными пользователя
 * @return id добавленной записи в бд
 */
function addUser(mysqli $link, array $userFormData){
    $sql = 'INSERT INTO users (creation_time, name, email, password, contact) VALUES (?, ?, ?, ?, ?)';
    $data = [ date('Y-m-d'), $userFormData['name'], $userFormData['email'], $userFormData['password'], $userFormData['contact']];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    return mysqli_insert_id($link);
}

/**
 * функция ищет пароль в базе данных
 * @param mysqli $link
 * @param $email
 * @return false | string
*/
function searchPassword(mysqli $link, string $email) : false | string
{
    $sql = "SELECT password FROM users WHERE email =" . "'" . $email . "'";
    $result = mysqli_query($link, $sql);
    if ( $result->num_rows===0 ){
        return false;
    }
    $passwordFromBd = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    return $passwordFromBd[0]['password'];
}

/** 
 * функция ищет данные пользователя в базе данных
 * @param mysqli $link
 * @param $email
 * @return массив с данными пользователя
*/
function searchUser(mysqli $link, string $email) : array
{
    $sql = "SELECT id, name FROM users WHERE email =" . "'" . $email . "'";
    $result = mysqli_query($link, $sql);

    $userData = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    return $userData;    
}

/**
 * функция ищет лоты по гет запросу поисковой фразы
 * @param mysqli $link
 * @param $countLot количество лотов
 * @param $searchWord искомое слово
 * @param $page номер страницы
 * @return массив с лотами либо строка, если ничего не найдено
 */
function searchLots(mysqli $link, int $countLot, string $searchWord, int $page) : array | string
{
    //TODO использовать подготовленное выражение
    $searchWord =  mysqli_real_escape_string($link, $searchWord);
    $page -= 1;
    $sql = "SELECT lots.id, lots.name,creation_time,img,begin_price,date_completion,categories.name as category
    FROM lots 
    LEFT JOIN categories on lots.category_id=categories.id
    WHERE MATCH(lots.name, lots.description) AGAINST('" . $searchWord . "') LIMIT " . $countLot . " OFFSET " . $page;
    $result = mysqli_query($link, $sql);
    if ( $result->num_rows===0 ){
        $searchData = [];
        return $searchData;
    }
    $searchData = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
    return $searchData;   
}

/**
 * функция получает количесвто страниц лотов по гет запросу поисковой фразы
 * @param mysqli $link
 * @param $countLot количество лотов
 * @param $searchWord искомое слово
 * @return количество страниц
 */
function getCountSearchPage(mysqli $link, int $countLot, string $searchWord) : int
{
   //TODO использовать подготовленное выражение
    $sql ="SELECT count(id) as count 
    FROM lots 
    WHERE MATCH(name, description) AGAINST('" . $searchWord . "')";
    $result = mysqli_query($link, $sql);

    $countPage = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $countPage = $countPage[0]['count'];
    $countPage = ceil($countPage / $countLot);

    return $countPage;
}

/**
 * 
 */
function getNameCategory($link, $id){
    $sql = "SELECT name FROM categories WHERE id =" . "'" . $id . "'";
    $result = mysqli_query($link, $sql);

    $nameCategory = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $nameCategory = $nameCategory[0]['name'];
    
    return $nameCategory;   
}

function getCountCategoryPage(mysqli $link, int $countLot, int $id) : int
{
   
    $sql ="SELECT count(id) as count 
    FROM lots 
    WHERE category_id=" .$id;
    $result = mysqli_query($link, $sql);

    $countPage = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $countPage = $countPage[0]['count'];
    $countPage = ceil($countPage / $countLot);

    return $countPage;
}

function categoryLots(mysqli $link, int $countLot, int $id, int $page){
    $page -= 1;
    $sql = "SELECT lots.id, lots.name,creation_time,img,begin_price,date_completion,categories.name as category
    FROM lots 
    LEFT JOIN categories on lots.category_id=categories.id
    WHERE category_id=" .$id . " LIMIT " . $countLot . " OFFSET " . $page;
    $result = mysqli_query($link, $sql);

    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
    return $lots; 
}
  
function getBet($link, int $id){
    $sql = "SELECT price FROM bets WHERE lot_id=" . $id . " order by creation_time DESC LIMIT 1";
    $result = mysqli_query($link, $sql);
    if ( $result->num_rows===0 ){
        return '';
    }
    $bet = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $bet[0]['price'];
}

function getBetByUser($link, $lotId){
    $sql = "SELECT user_id FROM `bets` WHERE lot_id=" . $lotId . " order by creation_time DESC LIMIT 1";
    $result = mysqli_query($link, $sql);
    if ( $result->num_rows===0 ){
        return null;
    }
    $lastBet = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $lastBet[0]['user_id'];
}

function createBet($link, $price, $userId, $lotId){
    $sql = 'INSERT INTO bets (creation_time,price, user_id, lot_id) VALUES (?, ?, ?, ?)';
    $data = [date('Y-m-d h:i:s') ,$price, $userId, $lotId];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    var_dump($result);
    return mysqli_insert_id($link);
}

function getHistoryBet($link, $lotId){
    $sql = "SELECT users.name as name, bets.creation_time, bets.price 
    FROM bets
    LEFT JOIN users on bets.user_id = users.id
    WHERE lot_id=" . $lotId .  
    " order by creation_time DESC";

    $result = mysqli_query($link, $sql);
    if ( $result->num_rows===0 ){
        $historyBet = [];
        return $historyBet;
    }
    $historyBet = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $historyBet;
}