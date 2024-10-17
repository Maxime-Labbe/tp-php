<?php 

require 'autoload.php';

use App\Entity\User;
use App\Config\Database;
use App\Repository\UserRepository;
use App\Validator\UserValidation;
use App\Repository\ImageUploader;

session_start();

if(!isset($_SESSION['username']) || !isset($_SESSION['id'])) {
    header('Location: ../tp/login.php');
}

if(!$_SESSION['admin']) {
    header('Location: ../tp/');
}

if (!array_key_exists('id',$_GET)) {
    header('Location : ../tp/');    
}

$db = new Database("localhost","php2","root","welcome1");
$connection = $db->getConnection();
$userRepo = new UserRepository($connection);
$userValidator = new UserValidation();
$imageUploader = new ImageUploader();

$user = $userRepo->read($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    foreach($_POST as $key => $value) {
        $userValidator->checkAttr($key,$userValidator->getErrors());
    }
    if (isset($_FILES['file']) && $_FILES['file']['name']) {
        $isFile = true;
        $userValidator->checkFiles($userValidator->getErrors());
    } else {
        $isFile = false;
    }
    if (count($userValidator->getErrors()) === 0 || (count($userValidator->getErrors()) === 1 && $_POST['password'] === '')) {
        $filePath = null;
        if ($isFile) {
            $filePath = $imageUploader->create();
        }
        if ($user->getMediaObject() !== '' && $user->getMediaObject() !== null) {
            $imageUploader->delete($user->getMediaObject());
        }
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'] ? password_hash($_POST['password'],PASSWORD_BCRYPT) : $user->getPassword();
        $user = new User($username,$email,$password,($filePath === null ? $user->getMediaObject() : $filePath),$_GET['id']);
        $userRepo->update($user);
        header('Location:../tp/');
    } else {
        $errors = $userValidator->getErrors();
        foreach($errors as $error) {
            echo $error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Update</title>
</head>
<body>
    
    <h1 style='width:500px;'>Modifier un utilisateur</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        Nom:<input type="text" name="username" value="<?php echo $user->getUsername();?>"><br>
        Email:<input type="email" name="email" value="<?php echo $user->getEmail();?>"><br>
        Password:<input type="password" name="password"><br>
        Photo:<input type="file" name="file" id="file"><br>
        <input type="submit" value="Modifier">
    </form>

    <a href='index.php'>Liste des posts</a>

</body>
</html>

