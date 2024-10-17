<?php 

namespace App\Repository;

require 'autoload.php';

use \PDO;
use App\Entity\User;
use App\Entity\Like;

class LikeRepository {

    private \PDO $db;
    
    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function isLiked(Like $like) : bool {
        $sql = "SELECT * FROM Likes WHERE post_id = " . $like->getPostId() . " and user_id = " . $like->getUserId() . ";";
        $result = $this->db->query($sql);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return $rows ? true : false;
    }

    public function getLikes() : array {
        $sql = "SELECT COUNT(*) as likes FROM Likes LEFT JOIN posts on posts.id = likes.post_id GROUP BY post_id ORDER BY posts.created_at DESC;";
        $result = $this->db->query($sql);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    } 

    public function getCountFromPost($id) : int {
        $sql = "SELECT COUNT(*) as likes FROM Likes WHERE post_id = " . $id . ";";
        $result = $this->db->query($sql);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return $rows[0]['likes'];
    }

    public function getUsersLikeFromPost($id) : array {
        $sql = "SELECT users.* FROM Likes LEFT JOIN users ON users.id = Likes.user_id WHERE post_id = " . $id . ";";
        $result = $this->db->query($sql);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        $users = [];
        foreach ($rows as $row) {
            $users[] = new User($row['username'],$row['email'],$row['password'],$row['media_object'],$row['id']);
        }
        return $users;
    }

    public function insert(Like $like) {
        $sql = "INSERT INTO likes (user_id,post_id) VALUES (:user_id,:post_id);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $like->getUserId());
        $stmt->bindValue(':post_id', $like->getPostId());
        return $stmt->execute();
    }

    public function delete(Like $like) {
        $sql = "DELETE FROM Likes WHERE post_id = :post_id and user_id = :user_id;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':post_id',$like->getPostId());
        $stmt->bindValue(':user_id',$like->getUserId());
        return $stmt->execute();
    }
}
?>