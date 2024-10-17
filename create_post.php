<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Créer un post</title>
</head>
<body>

    <?php 

    require 'autoload.php';

    use App\Entity\Post;
    use App\Config\Database;
    use App\Repository\PostRepository;
    use App\Repository\ImageUploader;
    
    session_start();

    if(!isset($_SESSION['username']) && !isset($_SESSION['id'])) {
        header('Location: ../tp/login.php');
    }

    $db = new Database("localhost","php2","root","welcome1");
    $connection = $db->getConnection();
    $postRepo = new PostRepository($connection);
    $imageUploader = new ImageUploader();

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $description = $_POST['description'];
        $user_id = $_SESSION['id'];
        $filePath = null;
        $filePath = $imageUploader->create();
        if ($filePath !== null) {
            $post = new Post($description,($filePath === null ? '' : $filePath),$user_id);
            $post->setCreatedAt(date("Y-m-d H:i:s"));
            $postRepo->insert($post);
        }
    }

    ?>
    
    <h1 style='width:500px;'>Créer un post</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        Description:<input type="text" name="description"><br>
        Image:<input type="file" name="file" id="file"><br>
        <input type="submit" value="Créer">
    </form>

    <a href='index.php'>Liste des posts</a>

</body>
</html>

