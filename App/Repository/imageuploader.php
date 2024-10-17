<?php 

namespace App\Repository;

require 'autoload.php';

class ImageUploader {
    private string $path = "uploads/";

    public function create() : ?string {
        $fileName = uniqid() . "-" . basename($_FILES['file']['name']);
        $filePath = $this->path . $fileName;
        if (move_uploaded_file($_FILES['file']['tmp_name'],$filePath)) {
            echo "Fichier téléchargé avec succès ! <br>";
            return $filePath;
        } else {
            echo "Erreur lors du téléchargement du fichier. <br>";
            return null;
        }
    }

    public function delete(string $media) : void {
        if (!empty($media) && file_exists($media)) {
            unlink($media);
        }
    }
}