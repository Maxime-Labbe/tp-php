<?php

namespace App\Entity;

class User {
    private $id;
    private string $username;
    private string $email;
    private string $password;
    private string $media_object;
    private $created_at;
    private $last_connection;

    public function __construct(string $username, string $email, string $password, string $media_object, $id = null, $created_at = null, $last_connection = null,) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->media_object = $media_object;
        $this->id = $id;
        $this->created_at = $created_at;
        $this->last_connection = $last_connection;
    }

    public function getUsernameEmail() : string {
        return $this->username . " - " . $this->email;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() : string {
        return $this->username;
    }

    public function getEmail() : string {
        return $this->email;
    }

    public function getPassword() : string {
        return $this->password;
    }

    public function getMediaObject() : string {
        return $this->media_object;
    }

    public function getCreatedAt() : ?string {
        return $this->created_at;
    }

    public function getLastConnection() : ?string {
        return $this->last_connection;
    }

    public function setUsername(string $username) : void {
        $this->username = $username;
    }

    public function setEmail(string $email) : void {
        $this->email = $email;
    }
    
    public function setPassword(string $password) : void {
        $this->password = $password;
    }
    
    public function setMediaObject(string $media_object) : void {
        $this->media_object = $media_object;
    }
    
    public function setCreatedAt(string $created_at) : void {
        $this->created_at = $created_at;
    }

    public function setLastConnection(string $last_connection) : void {
        $this->last_connection = $last_connection;
    }
}

?>