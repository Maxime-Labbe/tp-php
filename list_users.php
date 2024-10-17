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

$users = $userRepo->getAll();
?>

<table style="border:1px;">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php
    foreach($users as $user) : ?>
    <tr>
        <td><?php echo $user->getId() ?></td>
        <td><?php echo $user->getUsername() ?></td>
        <td><?php echo $user->getEmail() ?></td>
        <td><a href='read_user.php?id=<?php echo $user->getId() ?>'>Consulter le profil</a> <a href='delete_user.php?id=<?php echo $user->getId() ?>'>Supprimer</a> </td>
    </tr>
    <?php endforeach;?>
</table>
</body>
</html>