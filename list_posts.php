<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Liste des utilisateurs</title>
</head>
<body>
<?php

require 'autoload.php';

use App\Config\Database;
use App\Repository\PostRepository;

session_start();

if(!isset($_SESSION['username']) || !isset($_SESSION['id'])) {
   header('Location: ../tp/login.php');
}

if(!$_SESSION['admin']) {
    header('Location: ../tp/');
}

$db = new Database("localhost","php2","root","welcome1");
$connection = $db->getConnection();
$postRepo = new PostRepository($connection);

$posts = $postRepo->getAll();
?>

<h1>Liste des posts</h1>
<table >
        <tr >
            <th >Utilisateur</th>
            <th >Image</th>
            <th >Description</th>
            <th >Posté le :</th>
            <th >Actions</th>
        </tr>
        <?php foreach($posts as $post) : ?>
        <tr >
            <td ><?php echo $post->getUser()->getUsername() ?></td>
            <td ><img src= "<?php echo (file_exists($post->getMediaObject()) ? $post->getMediaObject() : "uploads/default.jpg") ?>" style='width:100px;height:100px;'></td>
            <td ><?php echo $post->getDescription() ?></td>
            <td ><?php echo $post->getCreatedAt() ?></td>
            <td >
                <a href="read_post.php?id=<?php echo $post->getId();?>"><button>Voir le post</button></a>    
                <a href="delete_post.php?id=<?php echo $post->getId() ?>" onClick="return window.confirm('Êtes-vous sûr de vouloir supprimer ?')"><button>Supprimer</button></a>
            </td>
        </tr>
        <?php endforeach; ?>
        </table>
</body>
</html>