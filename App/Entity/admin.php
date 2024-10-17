<?php 

namespace App\Entity;

use App\Entity\User;

class Admin extends User {
    private string $role = "Admin";

    public function getRole() : string {
        return $this->role;
    }
}