<?php
    session_start();

 if(!isset($_SESSION['username']) && !isset($_SESSION['id'])) {
    header('Location: ../tp/login.php');
 } else {
    session_unset();
    session_destroy();
    header('Location: ../tp/login.php');
 }
?>