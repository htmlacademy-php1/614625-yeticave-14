<?php
require_once __DIR__ . '/init.php';

$categories = getCategories($link);

if( !isset($_GET['id']) )
{
    header( "Location:/404.php", true,302 );
    exit();
}
if ( empty($_GET['id']) )
{
    header( "Location:/404.php", true,302 );
    exit();
}
$lot = getLot($link, $_GET['id']);
if(!$lot){
    header( "Location:/404.php", true,302 );
    exit();
}


$bet = getBet($link, $_GET['id']);

if(empty($bet)){
    $bet = $lot[0]['begin_price'];
}
$bidStep = $bet + $lot[0]['bid_step'];
$error = '';

$historyBet = getHistoryBet($link, $_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    $price = $_POST['price'];
    $error = validateBet($price, $lot, $bidStep, $link);

    if (empty($error)){
        createBet($link, $price, $_SESSION['user_id'], $lot[0]['id']);
        header("Location:/lot.php?id=" . $_GET['id']);
        exit();
    }

}


$page_content = include_template('lot.php',[
    'lot' => $lot[0],
    'bet' => $bet,
    'bidStep' => $bidStep,
    'error' => $error,
    'historyBet' => $historyBet
]);

$layout_content = include_template('layout.php',[
    'categories' => $categories,
    'content'    => $page_content,
    'title'      => $lot[0]['name']
]);

print($layout_content);
