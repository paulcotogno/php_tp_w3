<?php

require './vendor/autoload.php';

use App\Controller\CommentController;
use App\Controller\UserController;
use App\Controller\PostController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($uri[1] == 'api') {
    switch ($uri[2]) {
        case 'user' :
            if (isset($uri[3])) {
                $userId = (int)$uri[3];
            } else {
                $userId = null;
            }
            $controller = new UserController($requestMethod, $userId);
            $controller->processRequest();
            break;
        case 'post' :
            if (isset($uri[3])) {
                $postId = (int)$uri[3];
            } else {
                $postId = null;
            }
            $controller = new PostController($requestMethod, $postId);
            $controller->processRequest();
            break;
        case 'comment' :
            $postId = null;
            $userId = null;
            $commentId = null;

            if (isset($uri[3])) {
                switch ($uri[3]) {
                    case 'post':
                        if (isset($uri[4])) {
                            $postId = (int)$uri[4];
                        } else {
                            if (!isset($uri[4])) {
                                header("HTTP/1.1 404 Not Found");
                                echo 'Url Not Found';
                                exit;
                            }
                        }
                        break;
                    case 'user':
                        if (isset($uri[4])) {
                            $userId = (int)$uri[4];
                        } else {
                            header("HTTP/1.1 404 Not Found");
                            echo 'Url Not Found';
                            exit;
                        }
                        break;
                    case 'id':
                        if (isset($uri[4])) {
                            $commentId = (int)$uri[4];
                        } else {
                            header("HTTP/1.1 404 Not Found");
                            echo 'Url Not Found';
                            exit;
                        }
                        break;
                    default:
                        header("HTTP/1.1 404 Not Found");
                        echo 'Url Not Found';
                        return;
                }
            }
            $controller = new CommentController($requestMethod, $commentId, $postId, $userId);
            $controller->processRequest();
            break;

        default:
            header("HTTP/1.1 404 Not Found");
            echo 'Url Not Found';
            break;
    }
} else {
    header("HTTP/1.1 404 Not Found");
    exit;
}

