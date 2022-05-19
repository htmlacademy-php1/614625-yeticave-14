<?php
session_start();

require_once __DIR__ . '/functions/error.php';
require_once __DIR__ . '/functions/calculate.php';
require_once __DIR__ . '/functions/db.php';
require_once __DIR__ . '/functions/template.php';
require_once __DIR__ . '/functions/validate.php';
require_once __DIR__ . '/functions/upload.php';
require_once __DIR__ . '/getwinner.php';

$config = require 'config.php';

$link = dbConnect($config);
