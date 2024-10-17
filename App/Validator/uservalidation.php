<?php 

namespace App\Validator;

class UserValidation {
    private array $errors = [];

    public function getErrors() : array {
        return $this->errors;
    }

    public function setErrors(array $errors) : void {
        $this->errors = $errors;
    }

    public function validText(string $attr) : bool {
        $value = htmlspecialchars($_POST[$attr]);
        switch ($attr) {
            case 'email' :
                return filter_var($value,FILTER_VALIDATE_EMAIL);
            case 'username' :
                return gettype($value) === "string";
            case 'password' :
                return gettype($value) === "string" && strlen($value) >= 8;
        }
    }

    public function checkAttr(string $attr, array $errors) : void {
        $value = htmlspecialchars($_POST[$attr]); 
        if (array_key_exists($attr,$_POST) && $attr !== 'file') {
            if ($value !== "") {
                if (!$this->validText($attr)) {
                    $errors[] = $attr . " invalide ! <br>";
                }
            } else {
                $errors[] = "Aucun " . $attr . " envoyé. <br>" ;
            }
        } else {
            $errors[] = "L'attribut " . $attr . " n'existe pas dans le formulaire ! <br>";
        }
        $this->setErrors($errors);
    }

    public function checkFiles(array $errors) : void {
        $files_types = ['image/png','image/jpeg','image/jpg'];
        if (!isset($_FILES['file']) || !$_FILES['file']['name']) {
            $errors[] = "Aucun fichier envoyé. <br>";
        }
        if ($_FILES['file']['error'] !== 0) {
            $errors[] = "Erreur lors de l'envoi du fichier. <br>";
        }
        if ($_FILES['file']['size'] > 2000000) {
            $errors[] = "Fichier trop volumineux. <br>";
        }
        if (!in_array($_FILES['file']['type'],$files_types)) {
            $errors[] = "Fichier non supporté. <br>";
        }
        $this->setErrors($errors);
    }
}