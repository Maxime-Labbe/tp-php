<?php 

require 'autoload.php';

use App\Config\Database;
use App\Repository\UserRepository;

session_start();

if(!isset($_SESSION['username']) || !isset($_SESSION['id'])) {
    header('Location: ../tp/login.php');
 }

 if(!$_SESSION['admin']) {
    header('Location: ../tp/');
}

$db = new Database("localhost","php2","root","welcome1");
$connection = $db->getConnection();
$userRepo = new UserRepository($connection);

if (!array_key_exists('id',$_GET)) {
    header('Location: ../tp/');    
}

try {
    $userRepo->delete($_GET['id']);
    header('Location: ../tp/');
} catch (PDOException $e) {
    echo "Erreur " . $e->getMessage();
}

?>