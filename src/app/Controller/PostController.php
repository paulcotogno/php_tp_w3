<?php

namespace App\Controller;

use App\Config\Database;
use App\Manager\PostManager;

class PostController
{
    private $requestMethod;

    private $postId;

    public function __construct($requestMethod, $postId)
    {
        $this->requestMethod = $requestMethod;
        $this->postId = $postId;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->postId) {
                    $response = $this->showPost($this->postId);
                } else {
                    $response = $this->showPosts();
                }
                break;
            case 'POST':
                $response = $this->createPostFromRequest();
                break;
            case 'PUT':
                $response = $this->updatePostFromRequest($this->postId);
                break;
            case 'DELETE':
                $response = $this->deletePost($this->postId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            return $response['body'];
        }
    }

    /**
     * @param $id
     *
     * @return array
     */
    private function showPost($id)
    {
        $manager = new PostManager(Database::getConnection());
        $post = $manager->getPost($id);

        if (!$post) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($post);

        echo $response['body'];
        exit;
    }

    /**
     * @return array
     */
    private function showPosts()
    {
        $manager = new PostManager(Database::getConnection());
        $posts = $manager->getAllPosts();

        if (!$posts){
           return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($posts);

        echo $response['body'];
        exit;
    }

    /**
     * @return array
     */
    private function createPostFromRequest()
    {
        $input = (array)json_decode(file_get_contents('php://input'), true);
        $manager = new PostManager(Database::getConnection());

        if (!$this->validatePost($input)) {
            return $this->unprocessableEntityResponse();
        }

        if ($manager->createPost($input)) {
            echo 'Post created successfully.';
        } else {
            echo 'Post could not be created.';
        }
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;

        return $response;
    }

    /**
     * @param $id
     *
     * @return array
     */
    private function updatePostFromRequest($id)
    {
        $input = (array)json_decode(file_get_contents('php://input'), true);

        $manager = new PostManager(Database::getConnection());
        $user = $manager->getPost($id);
        if (!$user) {
            return $this->notFoundResponse();
        }
        if (!$this->validatePost($input)) {
            return $this->unprocessableEntityResponse();
        }

        if ($manager->updatePost($input, $id)) {
            echo 'Post updated successfully.';
        } else {
            echo 'Post could not be updated.';
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;

        return $response;
    }

    /**
     * @param $id
     *
     * @return array
     */
    private function deletePost($id)
    {
        $manager = new PostManager(Database::getConnection());
        $post = $manager->getPost($id);
        if (!$post) {
            return $this->notFoundResponse();
        }
        if ($manager->deletePost($id)) {
            echo 'Post deleted successfully.';
        } else {
            echo 'Post could not be deleted.';
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;

        return $response;
    }

    /**
     * @param $input
     *
     * @return bool
     */
    private function validatePost($input)
    {
        if (!isset($input['title'])) {
            return false;
        }
        if (!isset($input['content'])) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input',
        ]);

        return $response;
    }

    /**
     * @return array
     */
    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;

        echo 'Post not found';

        return $response;
    }

}
