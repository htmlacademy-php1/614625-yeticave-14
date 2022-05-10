<?php
require_once __DIR__ . '/init.php';

unset($_SESSION['user_id']);
unset($_SESSION['name']);

header("Location:/");