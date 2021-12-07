<?php

require './vendor/autoload.php';

use App\Controller\CommentController;
use App\Controller\UserController;
use App\Controller\PostController;
use App\Router\Router;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


$requestMethod = $_SERVER["REQUEST_METHOD"];

new Router($uri, $requestMethod);
