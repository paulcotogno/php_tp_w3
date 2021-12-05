<?php

namespace App\Controller;

use App\Config\Database;
use App\Manager\CommentManager;

class CommentController
{
    private $requestMethod;

    private $commentId;

    private $postId;

    private $userId;

    public function __construct($requestMethod, $commentId, $postId, $userId)
    {
        $this->requestMethod = $requestMethod;
        $this->commentId = $commentId;
        $this->postId = $postId;
        $this->userId = $userId;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->commentId) {
                    $response = $this->showComment($this->commentId);
                } else {
                    if ($this->postId) {
                        $response = $this->showCommentByPost($this->postId);
                    } else {
                        if ($this->userId) {
                            $response = $this->showCommentByUser($this->userId);
                        } else {
                            $response = $this->showComments();
                        }
                    }
                }
                break;
            case 'POST':
                $response = $this->createPostFromRequest();
                break;
            case 'PUT':
                $response = $this->updatePostFromRequest($this->commentId);
                break;
            case 'DELETE':
                $response = $this->deleteComment($this->commentId);
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
    private function showComment($id)
    {
        $manager = new CommentManager(Database::getConnection());
        $comment = $manager->getComment($id);

        if (!$comment) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($comment);

        echo $response['body'];
        exit;
    }

    /**
     * @param $userId
     *
     * @return array
     */
    private function showCommentByUser($userId)
    {
        $manager = new CommentManager(Database::getConnection());
        $comment = $manager->getCommentsByUser($userId);

        if (!$comment) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($comment);

        echo $response['body'];
        exit;
    }

    /**
     * @param $postId
     *
     * @return array
     */
    private function showCommentByPost($postId)
    {
        $manager = new CommentManager(Database::getConnection());
        $comment = $manager->getCommentsByPost($postId);

        if (!$comment) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($comment);

        echo $response['body'];
        exit;
    }

    /**
     * @return array
     */
    private function showComments()
    {
        $manager = new CommentManager(Database::getConnection());
        $comments = $manager->getAllComments();

        if (!$comments) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($comments);

        echo $response['body'];
        exit;
    }

    /**
     * @return array
     */
    private function createPostFromRequest()
    {
        $input = (array)json_decode(file_get_contents('php://input'), true);
        $manager = new CommentManager(Database::getConnection());

        if (!$this->validateComment($input)) {
            return $this->unprocessableEntityResponse();
        }

        if ($manager->createComment($input)) {
            echo 'Comment created successfully.';
        } else {
            echo 'Comment could not be created.';
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
    private function updatePostFromRequest($id): array
    {
        $input = (array)json_decode(file_get_contents('php://input'), true);

        $manager = new CommentManager(Database::getConnection());
        $comment = $manager->getComment($id);
        if (!$comment) {
            return $this->notFoundResponse();
        }
        if (!$this->validateComment($input)) {
            return $this->unprocessableEntityResponse();
        }

        if ($manager->updateComment($input, $id)) {
            echo 'Comment updated successfully.';
        } else {
            echo 'Comment could not be updated.';
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
    private function deleteComment($id)
    {
        $manager = new CommentManager(Database::getConnection());
        $comment = $manager->getComment($id);
        if (!$comment) {
            return $this->notFoundResponse();
        }
        if ($manager->deleteComment($id)) {
            echo 'Comment deleted successfully.';
        } else {
            echo 'Comment could not be deleted.';
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
    private function validateComment($input)
    {
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

        echo 'Comment not found';

        return $response;
    }
}
