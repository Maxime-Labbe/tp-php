<?php 

namespace App\Config;

use \PDO;

class Database {
    private string $host;
    private string $dbname;
    private string $username;
    private string $password;
    private \PDO $pdo;

    public function __construct(string $host, string $dbname, string $username, string $password) {
        $this->host = "mysql:host=" . $host;
        $this->dbname = ";dbname=" . $dbname;
        $this->username = $username;    
        $this->password = $password;
        try {
            $this->pdo = new \PDO($this->host . $this->dbname, $this->username, $this->password);
        } catch (PDOException $e) {
            echo "Erreur " . $e->getMessage();
        }
        
    }

    public function getConnection() : \PDO {
        return $this->pdo;
    }
}