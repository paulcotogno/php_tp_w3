<?php

namespace App\Router;

class ViewRouter
{
    private array $uri;

    public function __construct($uri)
    {
        $this->uri = $uri;

        switch ($uri[1]){
            case '':
                $this->render('home');
                break;
            case 'post':
                $this->render('post');
                break;
            case 'createPost':
                $this->render('createPost');
                break;
            case 'account':
                $this->render('account');
                break;
            case 'users':
                $this->render('users');
                break;
            case 'signIn':
                $this->render('signIn');
                break;
            case 'logIn':
                $this->render('logIn');
                break;
            default :
                header("HTTP/1.1 404 Not Found");
                break;
        }
    }

    // NOT WORKING

    /**
     * @param $view
     */
    public function render($view)
    {
        ob_start();
        include __DIR__ . '/../View/' . $view . '.php';
        $content = ob_get_clean();
        include __DIR__ . '/../View/template.php';

    }
}

