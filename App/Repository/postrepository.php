<?php 

namespace App\Repository;

require 'autoload.php';

use \PDO;
use App\Entity\User;
use App\Entity\Post;

class PostRepository {

    private \PDO $db;
    
    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getAll() : array {
        $sql = "SELECT posts.description,posts.media_object as media_post,posts.created_at as post_creation,posts.id as post_id, users.* FROM Posts LEFT JOIN users ON users.id = posts.user_id ORDER BY posts.created_at DESC;";
        $result = $this->db->query($sql);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        $posts = [];
        foreach ($rows as $row) {
            $posts[] = new Post($row['description'],$row['media_post'],$row['id'],$row['post_id'],$row['post_creation'], new User($row['username'],$row['email'],$row['password'],$row['media_object'],$row['id']));
        }
        return $posts;
    }

    public function getPostsFromUser($id) : array {
        $sql = "SELECT * FROM Posts WHERE user_id = $id ORDER BY created_at DESC;";
        $result = $this->db->query($sql);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        $posts = [];
        foreach ($rows as $row) {
            $posts[] = new Post($row['description'],$row['media_object'],$row['user_id'],$row['id'],$row['created_at']);
        }
        return $posts;
    }

    public function read($id) : ?Post {
        $stmt = $this->db->prepare("SELECT posts.description,posts.media_object as media_post,posts.created_at as post_creation,posts.id as post_id, users.* FROM Posts LEFT JOIN users ON users.id = posts.user_id WHERE posts.id = :id;");
        $stmt->bindValue(":id",$id);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? new Post($data['description'],$data['media_post'],$data['post_id'],$data['id'],$data['post_creation'], new User($data['username'],$data['email'],$data['password'],$data['media_object'],$data['id'])) : null; 
    }

    public function insert(Post $post) {
        $sql = "INSERT INTO posts (description,media_object,user_id,created_at) VALUES (:description,:media,:user_id,:created_at);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':description', $post->getDescription());
        $stmt->bindValue(':media', $post->getMediaObject());
        $stmt->bindValue(':user_id', $post->getUserId());
        $stmt->bindValue(':created_at', $post->getCreatedAt());
        return $stmt->execute();
    }

    public function update(Post $post) {
        $sql = "UPDATE posts SET description = :description, media_object = :media) VALUES (:description,:media);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':description', $post->getDescription());
        $stmt->bindValue(':media', $user->getMediaObject());
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "SELECT media_object FROM Posts WHERE id = " . $id . ";";
        $result = $this->db->query($sql);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($rows[0]['media_object']) && file_exists($rows[0]['media_object'])) {
            unlink($rows[0]['media_object']);
        }
        $sql = "DELETE FROM Posts WHERE id = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id',$id);
        return $stmt->execute();
    }
}
?>