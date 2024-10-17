<?php
    spl_autoload_register(function ($class_name) {
        $file = __DIR__ . '/' . str_replace("\\","/",$class_name) . '.php';

        if (file_exists($file)) {
            include $file;
        } else {
            echo "Le fichier pour la classe $class_name est introuvable : $file";
        }
    });
?>