<?php

namespace App\Router;

use App\Controller\CommentController;
use App\Controller\UserController;
use App\Controller\PostController;

class ApiRouter
{
    private array $uri;

    private $requestMethod;

    public function __construct($uri, $requestMethod)
    {
        $this->uri = $uri;
        $this->requestMethod = $requestMethod;

        switch ($uri[2]) {
            case 'user' :
                $userId = (isset($uri[3])) ? (int)$uri[3] : null;
                $controller = new UserController($requestMethod, $userId);
                $controller->processRequest();
                break;
            case 'post' :
                $postId = (isset($uri[3])) ? (int)$uri[3] : null;
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
                                $this->NotFound();
                                exit;
                            }
                            break;
                        case 'user':
                            if (isset($uri[4])) {
                                $userId = (int)$uri[4];
                            } else {
                                $this->NotFound();
                                exit;
                            }
                            break;
                        case 'id':
                            if (isset($uri[4])) {
                                $commentId = (int)$uri[4];
                            } else {
                                $this->NotFound();
                                exit;
                            }
                            break;
                        default:
                            $this->NotFound();
                            exit;
                    }
                }
                $controller = new CommentController($requestMethod, $commentId, $postId, $userId);
                $controller->processRequest();
                break;

            default:
                $this->NotFound();
                break;
        }
    }

    public function NotFound()
    {
        header("HTTP/1.1 404 Not Found");
        echo 'Url Not Found';
    }
}
