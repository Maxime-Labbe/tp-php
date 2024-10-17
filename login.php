<?php

require 'autoload.php';

use App\Config\Database;
use App\Repository\UserRepository;
use App\Validator\UserValidation;

$db = new Database("localhost","php2","root","welcome1");
$connection = $db->getConnection();
$userRepo = new UserRepository($connection);
$userValidator = new UserValidation();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    foreach($_POST as $key => $value) {
        $userValidator->checkAttr($key,$userValidator->getErrors());
    }
    if (count($userValidator->getErrors()) === 0) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $db = new Database("localhost","php2","root","welcome1");
        $connection = $db->getConnection();
        $userRepo = new UserRepository($connection);
        $user = $userRepo->getLogin($email);
        if ($user === null) {
            echo "Utilisateur non trouvé !";
        } else if (password_verify($password,$user->getPassword()) && $user->getEmail() === $email) {
            $userRepo->updateConnection($user->getId());
            session_start();
            $_SESSION['id'] = $user->getId();
            $_SESSION['username'] = $user->getUsername();
            if($user->getRole() === 'Admin') {
                $_SESSION['admin'] = true;
            } else {
                $_SESSION['admin'] = false;
            }
            header('Location: ../tp/');
            exit();
        } else {
            $errors[] = "Email ou mot de passe incorrect.";
        }
    } else {
        $errors = $userValidator->getErrors();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Se connecter</title>
</head>
<body>
    <div class="page">
        <div class="container">
            <h1 class="connect">Se connecter</h1>
            <ul class="errors">
                <?php foreach($errors as $error) {
                        echo "<li class='lielems'>$error</li>";
                    }
                ?>
            </ul>
            <form method="POST" action="" enctype="multipart/form-data" class="form">
                Email:<input type="email" name="email" class="connectinput"><br>
                Password:<input type="password" name="password" class="connectinput"><br>
                <input type="submit" value="Se connecter" class="connectsubmit">
            </form>

            <a href="create_user.php" class="createaccount">Créer un compte</a>
        </div>
    </div>
</body>
</html>