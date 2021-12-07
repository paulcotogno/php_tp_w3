<?php

namespace App\Router;

class Router
{
    private string $uri;

    private $requestMethod;

    public function __construct($uri, $requestMethod)
    {
        $this->uri = $uri;
        $this->requestMethod = $requestMethod;

        $uri = explode('/', $uri);

        switch ($uri[1]) {
            case 'api':
                new ApiRouter($uri, $requestMethod);
                break;
            default :
                new ViewRouter($uri);
                break;
        }
    }
}
