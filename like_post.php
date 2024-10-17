<?php 

require 'autoload.php';

use App\Entity\Like;
use App\Config\Database;
use App\Repository\LikeRepository;

session_start();

if(!isset($_SESSION['username']) || !isset($_SESSION['id'])) {
    header('Location: ../tp/login.php');
 }

$db = new Database("localhost","php2","root","welcome1");
$connection = $db->getConnection();
$likeRepo = new LikeRepository($connection);

if (!array_key_exists('id',$_GET)) {
    header('Location: ../tp/');    
}

$like = new Like($_SESSION['id'],$_GET['id']);

if ($likeRepo->isLiked($like)) {
    $likeRepo->delete($like);
    header('Location: ../tp/');
} else {
    $likeRepo->insert($like);
    header('Location: ../tp/');
}

?>