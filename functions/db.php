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
 * функция возвращает массив с открытыми лотами
 * @param mysqli $link объект подключения к бд
 * @param $countLot количество выводимых лотов
 * @return $lots массив с лотами
 */
function getLots(mysqli $link, int $countLot):array
{
    $sql = 'SELECT lots.id, lots.name,creation_time,img,begin_price,date_completion,categories.name as category FROM lots
    LEFT JOIN categories on lots.category_id=categories.id WHERE NOW() < date_completion ORDER BY date_completion DESC LIMIT ' . $countLot;
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
    $searchWord =  mysqli_real_escape_string($link, $searchWord);
    $page -= 1;
    $sql = "SELECT lots.id, lots.name,creation_time,img,begin_price,date_completion,categories.name as category
    FROM lots 
    LEFT JOIN categories on lots.category_id=categories.id
    WHERE MATCH(lots.name, lots.description) AGAINST( ? ) LIMIT " . $countLot . " OFFSET " . $page;
    $data = [$searchWord];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    if (!mysqli_stmt_execute($stmt)) {
        exit('Ошибка при выполнении запроса');
    }
    $result = mysqli_stmt_get_result($stmt);
    if ( $result->num_rows===0 ){
        $searchData = [];
        return $searchData;
    }
    while ($row = mysqli_fetch_assoc($result)) {
        $searchData[] = $row;
    }
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
    $sql ="SELECT count(id) as count
    FROM lots
    WHERE MATCH(name, description) AGAINST( ? )";
    $data = [$searchWord];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    if (!mysqli_stmt_execute($stmt)) {
        exit('Ошибка при выполнении запроса');
    }
    $result = mysqli_stmt_get_result($stmt);
    $countPage = mysqli_fetch_assoc($result);
    $countPage = ceil($countPage['count'] / $countLot);
    return $countPage;
}

/**
 * функция получает название категории
 * @param mysqli $link
 * @param $id id категории
 * @return строку с названием
 */
function getNameCategory(mysqli $link, int $id) : string
{
    $sql = "SELECT name FROM categories WHERE id =" . "'" . $id . "'";
    $result = mysqli_query($link, $sql);

    $nameCategory = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $nameCategory = $nameCategory[0]['name'];
    
    return $nameCategory;   
}

/**
 * функция получает количество страниц в категории
 * @param mysqli $link
 * @param $countLot количество лотов на странице
 * @param $id id категории
 * @return количество страниц 
 */
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

/**
 * функция получает лоты в категории
 * @param mysqli $link
 * @param $countLot количество лотов на одной странице
 * @param $id id категории
 * @param $page номер страницы
 * @return массив с лотами
 */
function categoryLots(mysqli $link, int $countLot, int $id, int $page) : array
{
    $page -= 1;
    $sql = "SELECT lots.id, lots.name,creation_time,img,begin_price,date_completion,categories.name as category
    FROM lots 
    LEFT JOIN categories on lots.category_id=categories.id
    WHERE category_id=" .$id . " LIMIT " . $countLot . " OFFSET " . $page;
    $result = mysqli_query($link, $sql);

    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
    return $lots; 
}
  
/**
 * функция получает стоимость по ставке
 * @param mysqli $link
 * @param $id идентификатор лота
 * @return пустую строку или цену по ставке
 */
function getBet(mysqli $link, int $id) : string | int
{
    $sql = "SELECT price FROM bets WHERE lot_id=" . $id . " order by creation_time DESC LIMIT 1";
    $result = mysqli_query($link, $sql);
    if ( $result->num_rows===0 ){
        return '';
    }
    $bet = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $bet[0]['price'];
}

/**
 * функция получает пользователя по ставке
 * @param mysqli $link
 * @param $lotId идентификатор лота
 * @return null в случае, если у лота нет ставки или массив с Id и почтой
 */
function getBetByUser(mysqli $link,int $lotId) : array | null
{
    $sql = "SELECT user_id, users.email FROM bets LEFT JOIN users on bets.user_id = users.id WHERE lot_id=" . $lotId . " order by bets.creation_time DESC LIMIT 1";
    $result = mysqli_query($link, $sql);
    if ( $result->num_rows===0 ){
        return null;
    }
    $lastBet = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $lastBet[0];
}

/**
 * функция создает ставку у лота
 * @param mysqli $link
 * @param $price цена
 * @param $userId id пользователя
 * @param $lotId id лота
 * @return id вставленного значения в бд
 */
function createBet(mysqli $link, int $price, int $userId, int $lotId) : int
{
    $sql = 'INSERT INTO bets (creation_time,price, user_id, lot_id) VALUES (?, ?, ?, ?)';
    $data = [date('Y-m-d h:i:s') ,$price, $userId, $lotId];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    return mysqli_insert_id($link);
}

/**
 * функция получает историю ставок у лота
 * @param mysqli $link
 * @param $lotId ид лота
 * @return массив с историей ставок
 */
function getHistoryBet(mysqli $link, int $lotId) : array
{
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

/**
 * функция получает ставки пользователя для личного кабинета
 * @param mysqli $link
 * @param $userId идентификатор пользователя
 * @return массив с ставками
 */
function getMyBets(mysqli $link, int $userId) : array
{
    $sql = "SELECT 
        lots.id,
        lots.img,
        lots.name as lots_name,
        categories.name as category_name,
        lots.date_completion,
        bets.price,
        bets.creation_time,
        lots.winner_id,
        users.contact
    FROM bets
    LEFT JOIN lots on bets.lot_id = lots.id
    LEFT JOIN categories on lots.category_id = categories.id
    LEFT JOIN users on bets.user_id = users.id
    WHERE bets.user_id = " . $userId . " ORDER BY bets.creation_time DESC";
    $result = mysqli_query($link, $sql);
    if ( $result->num_rows===0 ){
        $myBets = [];
        return $myBets;
    }
    $myBets = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $myBets;
}

function getEndLots(mysqli $link)
{   
    $sql = "SELECT 
	    id as lot_id
    FROM lots
    WHERE NOW() > date_completion AND completed=0";
    $result = mysqli_query($link, $sql);
    if ( $result->num_rows===0 ){
        $endLots = [];
        return $endLots;
    }
    $endLots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $endLots;
}

function addWinnerLot($link, $winnerUser, $lotId){
    $sql = "UPDATE lots set winner_id = ?, completed = ? WHERE id = ?";
    $data = [$winnerUser, 1, $lotId];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    return mysqli_insert_id($link);
}

function addCompletedLot($link, $lotId){
    $sql = "UPDATE lots set completed = ? WHERE id = ?";
    $data = [1, $lotId];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    return mysqli_insert_id($link);
}