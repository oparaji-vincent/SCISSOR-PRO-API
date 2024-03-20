<?php

require dirname(__DIR__) . '\api\vendor\autoload.php';
set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");
require "./config.php";


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With,Authorization, Content-Type');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS');
header("Content-type:application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    die();
}
