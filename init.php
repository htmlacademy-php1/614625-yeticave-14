<?php
session_start();

require_once __DIR__ . '/functions/error.php';
require_once __DIR__ . '/functions/calculate.php';
require_once __DIR__ . '/functions/db.php';
require_once __DIR__ . '/functions/template.php';
require_once __DIR__ . '/functions/validate.php';
require_once __DIR__ . '/functions/upload.php';

if (!file_exists(__DIR__ . '/config.php')) {
    exit ('Создайте файл config.php на основе файла config.sample.php и сконфигурируйте его');
}

$config = require __DIR__ . '/config.php';

$link = dbConnect($config);
