<?php
require_once __DIR__ . '/functions/calculate.php';
require_once __DIR__ . '/functions/db.php';
require_once __DIR__ . '/functions/template.php';
require_once __DIR__ . '/functions/validate.php';

$config = require 'config.php';

$link = dbConnect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
