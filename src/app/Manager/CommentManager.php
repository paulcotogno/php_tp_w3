<?php

namespace App\Manager;


use App\Model\Comment;

class CommentManager
{
    private \PDO $db;

    public function __construct(\PDO $pdo)
    {
        $this->db = $pdo;
    }

    /**
     * @return Comment[]|bool
     */
    public function getAllComments()
    {
        $query = "SELECT * FROM `comments`";
        $res = $this->db->query($query);

        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $postId
     *
     * @return Comment[]|bool
     */
    public function getCommentsByPost($postId)
    {
        $query = "SELECT * FROM `comments` WHERE postId = $postId";
        $res = $this->db->query($query);

        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $userId
     *
     * @return Comment
     */
    public function getCommentsByUser($userId)
    {
        $query = "SELECT * FROM `comments` WHERE userId = $userId";
        $res = $this->db->query($query);

        return $res->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $id
     *
     * @return Comment
     */
    public function getComment($id)
    {
        $query = "SELECT * FROM `comments` WHERE id = $id";
        $res = $this->db->query($query);

        return $res->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $input
     *
     * @return false|\PDOStatement
     */
    public function createComment($input)
    {
        $query = "INSERT INTO `comments` (content, date, userId, postId) 
                    VALUES ('" . $input['content'] . "','" . $input['date'] . "'," . $input['userId'] . "," . $input['postId'] . ");";

        return $this->db->query($query);
    }

    /**
     * @param $input
     * @param $id
     *
     * @return false|\PDOStatement
     */
    public function updateComment($input, $id)
    {
        $query = "UPDATE `comments` SET  
                      content='" . $input['content'] . "'
                       WHERE id = $id";

        return $this->db->query($query);
    }

    /**
     * @param $id
     *
     * @return false|\PDOStatement
     */
    public function deleteComment($id)
    {
        $query = "DELETE FROM `comments` WHERE id = $id";

        return $this->db->query($query);
    }
}
