<?php

require 'autoload.php';

use App\Entity\User;
use App\Config\Database;
use App\Repository\UserRepository;
use App\Repository\PostRepository;
use App\Repository\LikeRepository;
use App\Validator\UserValidation;

session_start();

if(!isset($_SESSION['username']) || !isset($_SESSION['id'])) {
   header('Location: ../tp/login.php');
}
if ($_SESSION['id'] !== $_GET['id']) {
    //header('Location: ../tp/');
}

$db = new Database("localhost","php2","root","welcome1");
$connection = $db->getConnection();
$userRepo = new UserRepository($connection);
$postRepo = new PostRepository($connection);
$likeRepo = new LikeRepository($connection);
$user = $userRepo->read($_GET['id']);
$posts = $postRepo->getPostsFromUser($_GET['id']);

if ($user === null) {
    echo "Aucun utilisateur trouvé !";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Read</title>
</head>
<body>

    <?php echo ($_SESSION['admin'] || $_GET['id'] === strval($_SESSION['id']) ? "<a href='update_user.php?id=" . $user->getId() . "'>Modifier le profil</a>" : "") ?>
    <?php echo ($_SESSION['admin'] ? "<p>ID : " . $user->getId() . "</p>" : "") ?>
    <p>Username : <?php echo $user->getUsername(); ?></p>
    <?php echo ($_SESSION['admin'] ? "<p>Email : " . $user->getEmail() . "</p>" : "") ?>
    <?php

    if ($user->getMediaObject() !== "uploads/default.jpg" && $user->getMediaObject() !== "") {
        echo '<img src="' . $user->getMediaObject() . '" style="width:100px;height:100px;">';
    } else {
        echo "<p>Aucune image trouvé !</p>";
    }

    ?>
    
    <?php echo ($_SESSION['admin'] ? "<p>Créer le : " . $user->getCreatedAt() . "</p>" : "") ?>
    <?php echo ($_SESSION['admin'] ? "<p>Dernière connexion : " . $user->getLastConnection() . "</p>" : "") ?>

    <h1 style='width:500px;'>Liste des posts</h1>
    <table >
        <tr >
            <th >Image</th>
            <th >Description</th>
            <th >Likes</th>
            <th >Posté le :</th>
            <th>Actions</th>
        </tr>
        <?php foreach($posts as $post) : ?>
        <tr >
            <td ><img src= "<?php echo (file_exists($post->getMediaObject()) ? $post->getMediaObject() : "uploads/default.jpg") ?>" style='width:100px;height:100px;'></td>
            <td ><?php echo $post->getDescription() ?></td>
            <td ><?php echo $likeRepo->getCountFromPost($post->getId()) ?></td>
            <td ><?php echo $post->getCreatedAt() ?></td>
            <td><p class="seepost"><a href="read_post.php?id=<?php echo $post->getId();?>">Voir le post</a></p></td>
        </tr>
        <?php endforeach; ?>
        </table>
        <a href='index.php'>Liste des utilisateurs</a>
</body>
</html>