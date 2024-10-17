<?php

require 'autoload.php';

use App\Entity\Post;
use App\Entity\Comment;
use App\Config\Database;
use app\Entity\Like;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;

session_start();

if(!isset($_SESSION['username']) || !isset($_SESSION['id'])) {
   header('Location: ../tp/login.php');
}

$db = new Database("localhost","php2","root","welcome1");
$connection = $db->getConnection();
$postRepo = new PostRepository($connection);
$commentRepo = new CommentRepository($connection);
$likeRepo = new LikeRepository($connection);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $description = $_POST['description'];
    $user_id = $_SESSION['id'];
    $post_id = $_GET['id'];
    $comment = new Comment($description,$user_id,$post_id);
    $comment->setCreatedAt(date("Y-m-d H:i:s"));
    $commentRepo->insert($comment);
}

$comments = $commentRepo->getCommentsFromPost($_GET['id']);
$post = $postRepo->read($_GET['id']);
$likers = $likeRepo->getUsersLikeFromPost($_GET['id']);

if ($post === null) {
    echo "Aucun post trouvé !";
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
    <div style="display:flex;flex-direction:row;justify-content:space-around;" class="readpost">
        <div>
            <div class="user">
                <img src="<?php echo $post->getUser()->getMediaObject() ?>" alt="user" class="profilepic">
                <p class="username"><a href="read_user.php?id=<?php echo $post->getUser()->getId()?>"> <?php echo $post->getUser()->getUsername() ?></a></p>
            </div>
            <img class="bigpost" src="<?php echo $post->getMediaObject(); ?>">
            <p class="description"><?php echo $post->getDescription(); ?></p>
            <div class="postdesc">
                <p style="font-size:1.2rem;">Posté le : <?php echo $post->getCreatedAt(); ?></p>
                <a href="like_post.php?id=<?php echo $_GET['id'] ?>"><button>
                        <?php
                        $like = new Like($_SESSION['id'],$_GET['id']);
                        echo ($likeRepo->isLiked($like) ? "Supprimer le like" : "Liker le poste") 
                        ?></button></a>
            </div>
            

            <h3 style='width:500px;'>Ajouter un commentaire</h3>
            <form method="POST" action="" enctype="multipart/form-data">
                Description:<input type="text" name="description" class="connectinput"><br>
                <input type="submit" value="Poster">
            </form>

            <?php foreach($comments as $comment): ?>
                <p>Commentaire : <?php echo $comment->getDescription(); ?></p>
                <p>Posté le : <?php echo $comment->getCreatedAt(); ?></p>
                <p>Posté par : <?php echo $comment->getUser()->getUsername(); ?></p>
            <?php endforeach; ?>
            <a href='index.php' style="font-size:1.5rem;color:black;">Liste des posts</a>
        </div>
        <div>
            <ul> Likes
                <?php foreach($likers as $liker): ?>
                    <li><img style="width:50px;height:50px;" src="<?php echo $liker->getMediaObject() ?>"><?php echo $liker->getUsername(); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>