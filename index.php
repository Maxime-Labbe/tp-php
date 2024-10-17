<?php
 require 'autoload.php';

 use App\Entity\User;
 use App\Entity\Like;
 use App\Config\Database;
 use App\Repository\PostRepository;
 use App\Repository\LikeRepository;
 use App\Repository\UserValidation;

 session_start();

 if(!isset($_SESSION['username']) || !isset($_SESSION['id'])) {
    header('Location: ../tp/login.php');
 }

$db = new Database("localhost","php2","root","welcome1");
$connection = $db->getConnection();
$postRepo = new PostRepository($connection);
$likeRepo = new LikeRepository($connection);

$posts = $postRepo->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Accueil</title>
</head>
<body>

    <div style="display:flex;flex-direction:row;justify-content:space-around;align-items:center;width:90%;margin-left:auto;margin-right:auto;">
        <?php echo ($_SESSION['admin'] ? 
        '<a href="create_user.php"><button >Créer un utilisateur</button></a>
        <a href="list_users.php"><button >Liste des utilisateurs</button></a>
        <a href="list_posts.php"><button >Liste des posts</button></a>'
         : "") ?>
        <a href="create_post.php"><button >Créer un post</button></a>
        <a href="read_user.php?id=<?php echo $_SESSION['id']?>"><button>Mon compte</button></a>
        <a href="signout.php" style="font-size:1.3rem;"><button>Se déconnecter</button></a>
    </div>
    <h1 style='width:500px;margin-left:auto;margin-right:auto;text-align:center;'>Bonjour, <?php echo $_SESSION['username']; ?></h1>
    <h2 style='width:500px;margin-left:auto;margin-right:auto;text-align:center;'>Liste des posts</h2>
        <div class="posts">
        <?php foreach($posts as $post) : ?>

            <div class="post">
                <div class="user">
                    <img src="<?php echo $post->getUser()->getMediaObject() ?>" alt="user" class="profilepic">
                    <p class="username"><a href="read_user.php?id=<?php echo $post->getUser()->getId()?>"> <?php echo $post->getUser()->getUsername() ?></a></p>
                </div>
                <div>
                    <p class="seepost"><a href="read_post.php?id=<?php echo $post->getId();?>">Voir le post</a></p>
                    <img src="<?php echo $post->getMediaObject() ?>" alt="post" class="postpic">
                </div>
                <div>
                    <p class="likes"><?php echo $likeRepo->getCountFromPost($post->getId()) ?> personne a aimé !</p>
                </div>
            </div>

        <?php endforeach; ?>
        </div>

</body>
</html>