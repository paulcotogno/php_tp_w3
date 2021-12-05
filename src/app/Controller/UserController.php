<?php

namespace App\Controller;

use App\Manager\UserManager;
use App\Config\Database;

class UserController
{
    private $requestMethod;

    private $userId;

    public function __construct($requestMethod, $userId)
    {
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->userId) {
                    $response = $this->showUser($this->userId);
                } else {
                    $response = $this->showUsers();
                }
                break;
            case 'POST':
                $response = $this->createUserFromRequest();
                break;
            case 'PUT':
                $response = $this->updateUserFromRequest($this->userId);
                break;
            case 'DELETE':
                $response = $this->deleteUser($this->userId);
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
    private function showUser($id)
    {
        $manager = new UserManager(Database::getConnection());
        $user = $manager->getUser($id);

        if (!$user) {
            return $this->unprocessableEntityResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($user);

        echo $response['body'];
        exit;
    }

    /**
     * @return array
     */
    private function showUsers()
    {
        $manager = new UserManager(Database::getConnection());
        $users = $manager->getAllUsers();

        if (!$users) {
            return $this->unprocessableEntityResponse();
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($users);

        echo $response['body'];
        exit;
    }

    /**
     * @return array
     */
    private function createUserFromRequest()
    {
        $input = (array)json_decode(file_get_contents('php://input'), true);
        $manager = new UserManager(Database::getConnection());

        if (!$this->validateUser($input)) {
            return $this->unprocessableEntityResponse();
        }

        if ($manager->createUser($input)) {
            echo 'User created successfully.';
        } else {
            echo 'User could not be created.';
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
    private function updateUserFromRequest($id)
    {
        $input = (array)json_decode(file_get_contents('php://input'), true);

        $manager = new UserManager(Database::getConnection());
        $user = $manager->getUser($id);
        if (!$user) {
            return $this->notFoundResponse();
        }
        if (!$this->validateUser($input)) {
            return $this->unprocessableEntityResponse();
        }

        if ($manager->updateUser($input, $id)) {
            echo 'User updated successfully.';
        } else {
            echo 'User could not be updated.';
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
    private function deleteUser($id)
    {
        $manager = new UserManager(Database::getConnection());
        $user = $manager->getUser($id);
        if (!$user) {
            return $this->notFoundResponse();
        }

        if ($manager->deleteUser($id)) {
            echo 'User deleted successfully.';
        } else {
            echo 'User could not be deleted.';
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
    private function validateUser($input)
    {
        if (!isset($input['firstName'])) {
            return false;
        }
        if (!isset($input['lastName'])) {
            return false;
        }
        if (!isset($input['isAdmin'])) {
            return false;
        }
        if (!isset($input['email'])) {
            return false;
        }
        if (!isset($input['password'])) {
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

        return $response;
    }
}
