<?php

namespace App\Entity;

class Like {
    private int $user_id;
    private int $post_id;

    public function __construct($user_id, $post_id) {
        $this->user_id = $user_id;
        $this->post_id = $post_id;
    }

    public function getUserId() : int {
        return $this->user_id;
    }

    public function getPostId() : int {
        return $this->post_id;
    }

    public function setUserId($user_id) : void {
        $this->user_id = $user_id;
    }

    public function setPostId($post_id) :void {
        $this->post_id = $post_id;
    }

}


?>