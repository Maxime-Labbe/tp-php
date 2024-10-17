<?php 

namespace App\Repository;

require 'autoload.php';

use \PDO;
use App\Entity\Comment;
use App\Entity\User;

class CommentRepository {

    private \PDO $db;
    
    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getCommentsFromPost($id) : array {
        $sql = "SELECT comments.description,comments.id as comment_id, comments.user_id, comments.post_id, comments.created_at as comment_creation, users.* FROM Comments LEFT JOIN users ON comments.user_id = users.id WHERE post_id = " . $id . ";";
        $result = $this->db->query($sql);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        $posts = [];
        foreach ($rows as $row) {
            $posts[] = new Comment($row['description'],$row['comment_id'],$row['user_id'],$row['post_id'],$row['comment_creation'], new User($row['username'],$row['email'],$row['password'],$row['media_object'],$row['id']));
        }
        return $posts;
    }

    public function insert(Comment $comment) {
        $sql = "INSERT INTO Comments (description,user_id,post_id,created_at) VALUES (:description,:user_id,:post_id,:created_at);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':description', $comment->getDescription());
        $stmt->bindValue(':user_id', $comment->getUserId());
        $stmt->bindValue(':post_id', $comment->getPostId());
        $stmt->bindValue(':created_at', $comment->getCreatedAt());
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM Comments WHERE id = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id',$id);
        return $stmt->execute();
    }
}
?>