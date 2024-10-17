<?php

namespace App\Entity;

use App\Entity\User;

class Post {
    private $id;
    private string $description;
    private string $media_object;
    private $user_id;
    private $created_at;
    private $user;

    public function __construct(string $description, string $media_object, $user_id = null, $id = null, $created_at = null, $user = null) {
        $this->description = $description;
        $this->media_object = $media_object;
        $this->id = $id;
        $this->user_id = $user_id;
        $this->created_at = $created_at;
        $this->user = $user;
    }

    public function getDescription() : ?string {
        return $this->description;
    }
    
    public function getMediaObject() : ?string {
        return $this->media_object;
    }
    
    public function getId() : ?string {
        return $this->id;
    }
    
    public function getUserId() : ?int {
        return $this->user_id;
    }

    public function getCreatedAt() : ?string {
        return $this->created_at;
    }

    public function getUser() : ?User {
        return $this->user;
    }
    
    public function setDescription(string $description) : void {
        $this->description = $description;
    }
    
    public function setMediaObject(string $media_object) : void {
        $this->media_object = $media_object;
    }
    
    public function setId(int $id) : void {
        $this->id = $id;
    }

    public function setUserId(int $user_id) : void {
        $this->user_id = $user_id;
    }

    public function setCreatedAt(string $created_at) : void {
        $this->created_at = $created_at;
    }

    public function setUser(User $user) : void {
        $this->user = $user;
    }
}

?>