<?php

namespace App\Manager;

use App\Model\Post;

class PostManager
{
    private \PDO $db;

    public function __construct(\PDO $pdo)
    {
        $this->db = $pdo;
    }

    /**
     * @return Post[]|bool
     */
    public function getAllPosts()
    {
        $query = "SELECT * FROM `posts`";
        $res = $this->db->query($query);

        return $res->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $id
     *
     * @return Post
     */
    public function getPost($id)
    {
        $query = "SELECT * FROM `posts` WHERE id = $id";
        $res = $this->db->query($query);

        return $res->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $input
     *
     * @return false|\PDOStatement
     */
    public function createPost($input)
    {
        $query = "INSERT INTO `posts` (title, content, date, userId, imageLink) VALUES ('" . $input['title'] . "','" . $input['content'] . "','" . $input['date'] . "'," . $input['userId'] . ",'" . $input['imageLink'] . "');";

        return $this->db->query($query);
    }

    /**
     * @param $input
     * @param $id
     *
     * @return false|\PDOStatement
     */
    public function updatePost($input, $id)
    {
        $query = "UPDATE `posts` SET  
                        title='" . $input['title'] . "',
                        content='" . $input['content'] . "',
                        imageLink='" . $input['imageLink'] . "'
                WHERE id = $id";

        return $this->db->query($query);
    }

    /**
     * @param $id
     *
     * @return false|\PDOStatement
     */
    public function deletePost($id)
    {
        $query = "DELETE FROM `posts` WHERE id = $id";

        return $this->db->query($query);
    }
}
