<?php

namespace App\Entity;

class Comment {
    private $id;
    private string $description;
    private int $user_id;
    private int $post_id;
    private $created_at;
    private $user;

    public function __construct($description, $user_id, $post_id, $id = null, $created_at = null, $user = null) {
        $this->description = $description;
        $this->user_id = $user_id;
        $this->post_id = $post_id;
        $this->id = $id;
        $this->created_at = $created_at;
        $this->user = $user;
    }

    public function getId() : int {
        return $this->id;
    }

    public function getDescription() : string {
        return $this->description;
    }

    public function getUserId() : int {
        return $this->user_id;
    }

    public function getPostId() : ?int {
        return $this->post_id;
    }

    public function getCreatedAt() : ?string {
        return $this->created_at;
    }

    public function getUser() : ?User {
        return $this->user;
    }

    public function setId($id) : void {
        $this->id = $id;
    }

    public function setDescription($description) : void {
        $this->description = $description;
    }

    public function setUserId($user_id) : void {
        $this->user_id = $user_id;
    }

    public function setPostId($post_id) :void {
        $this->post_id = $post_id;
    }

    public function setCreatedAt($created_at) : void {
        $this->created_at = $created_at;
    }   

    public function setUser($user) : void {
        $this->user = $user;
    }

}
?>