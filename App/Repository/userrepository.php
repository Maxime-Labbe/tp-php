<?php 

namespace App\Repository;

require 'autoload.php';

use \PDO;
use App\Entity\User;
use App\Entity\Admin;

class UserRepository {

    private \PDO $db;
    
    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getAll() : array {
        $sql = "SELECT * FROM Users";
        $result = $this->db->query($sql);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        $users = [];
        foreach ($rows as $row) {
            $users[] = new User($row['username'],$row['email'],$row['password'],$row['media_object'],$row['id']);
        }
        return $users;
    }

    public function read($id) : ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id;");
        $stmt->bindValue(":id",$id);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? new User($data['username'],$data['email'],$data['password'],$data['media_object'],$data['id'],$data['created_at'],$data['last_connection']) : null; 
    } 

    public function getLogin($email) : ?User {
        $stmt = $this->db->prepare("SELECT id,email,username,password FROM users WHERE email = :email;");
        $stmt->bindValue(":email",$email);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? $data['email'] === "admin@admin.com" && $data['username'] === 'admin' ? new Admin($data['username'],$data['email'],$data['password'],'',$data['id']) : new User($data['username'],$data['email'],$data['password'],'',$data['id']) : null; 
    }

    public function verifyEmail($email) : bool {
        $stmt = $this->db->prepare("SELECT email FROM users WHERE email = :email;");
        $stmt->bindValue(":email",$email);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? true : false;
    }

    public function insert(User $user) {
        $sql = "INSERT INTO Users (username,email,password,media_object,created_at,last_connection) VALUES (:username,:email,:password,:media,:created,:last_connection);";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':media', $user->getMediaObject());
        $stmt->bindValue(':created', $user->getCreatedAt());
        $stmt->bindValue(':last_connection', $user->getLastConnection());
        return $stmt->execute();
    }

    public function update(User $user) {
        $stmt = $this->db->prepare("UPDATE Users SET username = :username, email = :email, password = :password, media_object = :media WHERE id = :id");
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
        $stmt->bindValue(':media', $user->getmediaObject());
        $stmt->bindValue(':id', $user->getId());
        return $stmt->execute();
    }

    public function updateConnection($id) {
        $stmt = $this->db->prepare("UPDATE Users SET last_connection = :last_connection WHERE id = :id");
        $stmt->bindValue(':last_connection', date("Y-m-d H:i:s"));
        $stmt->bindValue(':id',$id);
        return $stmt->execute();
    }

    public function delete($id) {
            $sql = "SELECT media_object FROM Users WHERE id = " . $id . ";";
            $result = $this->db->query($sql);
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($rows[0]['media_object']) && file_exists($rows[0]['media_object'])) {
                unlink($rows[0]['media_object']);
            }
            $sql = "DELETE FROM Users WHERE id = :id;";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id',$id);
            return $stmt->execute();
    }
}

?>