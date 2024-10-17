<?php 

namespace User;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Create</title>
</head>
<body>

    <?php 

    require 'autoload.php';

    use App\Entity\User;
    use App\Config\Database;
    use App\Repository\UserRepository;
    use App\Validator\UserValidation;
    use App\Repository\ImageUploader;
    
    session_start();

    if($_SESSION && !$_SESSION['admin'] && isset($_SESSION['username']) && isset($_SESSION['id'])) {
        header('Location: ../tp/');
    }

    $db = new Database("localhost","php2","root","welcome1");
    $connection = $db->getConnection();
    $userRepo = new UserRepository($connection);
    $userValidator = new UserValidation();
    $imageUploader = new ImageUploader();
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if ($userRepo->getLogin($_POST['email'])) {
            $errors = $userValidator->getErrors();
            $errors[] = "Email déjà utilisé !<br>";
            $userValidator->setErrors($errors);
        }
        foreach($_POST as $key => $value) {
            $userValidator->checkAttr($key,$userValidator->getErrors());
        } 
        $userValidator->checkFiles($userValidator->getErrors());
        if (count($userValidator->getErrors()) === 0) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'],PASSWORD_BCRYPT);
            $filePath = null;
            $filePath = $imageUploader->create();
            $user = new User($username,$email,$password,($filePath === null ? '' : $filePath));
            $user->setCreatedAt(date("Y-m-d H:i:s"));
            $userRepo->insert($user);
            if(!isset($_SESSION['username']) && !isset($_SESSION['id'])) {
                header('Location: ../login.php');
            } else {
                header('Location: ../index.php');
            }
        } else {
            $errors = $userValidator->getErrors();
        }
    }

    ?>
    <div class="page">
        <div class="container">
            <h1 class="connect">Créer un utilisateur</h1>
            <ul class="errors">
                <?php if($errors) {
                    foreach($errors as $error) {
                        echo "<li class='lielems'>$error</li>";
                    }
                }
                ?>
            </ul>
            <form method="POST" action="" enctype="multipart/form-data" class="form">
                Nom:<input type="text" name="username" class="connectinput"><br>
                Email:<input type="email" name="email" class="connectinput"><br>
                Password:<input type="password" name="password" class="connectinput"><br>
                Photo:<input type="file" name="file" id="file"><br>
                <input type="submit" value="Créer" class="connectsubmit">
            </form>

            <?php if ($_SESSION && $_SESSION['admin']) {
                echo "<a href='index.php' class='createaccount'>Liste des utilisateurs</a>";
            }
            ?>
        </div>
    </div> 
</body>
</html>

