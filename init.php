<?php
require_once __DIR__ . '/functions/calculate.php';
require_once __DIR__ . '/functions/db.php';
require_once __DIR__ . '/functions/template.php';
require_once __DIR__ . '/functions/validate.php';

$db = require_once  'databaseconnect.php';
$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, "utf8");
