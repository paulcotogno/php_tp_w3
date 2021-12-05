<?php

namespace App\Manager;

use App\Model\User;

class UserManager
{
    private \PDO $db;

    public function __construct(\PDO $pdo)
    {
        $this->db = $pdo;
    }

    /**
     * @return User[]|bool
     */
    public function getAllUsers()
    {
        $query = "SELECT * FROM `users`";
        $res = $this->db->query($query);

        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $id
     *
     * @return User
     */
    public function getUser($id)
    {
        $query = "SELECT * FROM `users` WHERE  id = $id LIMIT 0,1";
        $res = $this->db->query($query);

        return $res->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $input
     *
     * @return false|\PDOStatement
     */
    public function createUser($input)
    {
        $query = "INSERT INTO `users` (firstName, lastName, isAdmin, email, password) 
                    VALUES ('" . $input['firstName'] . "','" . $input['lastName'] . "'," . $input['isAdmin'] . ",'" . $input['email'] . "','" . $input['password'] . "');";

        return $this->db->query($query);
    }

    public function updateUser($input, $id)
    {
        $query = "UPDATE `users` SET  
                        firstName='" . $input['firstName'] . "',
                        lastName='" . $input['lastName'] . "',
                        isAdmin=" . $input['isAdmin'] . ",
                        email='" . $input['email'] . "',
                        password='" . $input['password'] . "' 
                WHERE id = $id";

        return $this->db->query($query);
    }

    /**
     * @param $id
     *
     * @return false|\PDOStatement
     */
    public function deleteUser($id)
    {
        $query = "DELETE FROM `users` WHERE id = $id";

        return $this->db->query($query);
    }

    public function getUserByMailAndPsswrd($mail, $password)
    {
        $query = "SELECT * FROM `users` WHERE email = '$mail' AND password = '$password'";
        $res = $this->db->query($query);

        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }
}
