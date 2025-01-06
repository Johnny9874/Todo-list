<?php

// On va créer un service qui interagit avec UserDAO pour ajouter, récupérer, et manipuler les utilisateurs.
require_once __DIR__ . '/../dao/UserDAO.php';  // Utilisez __DIR__ pour spécifier le chemin absolu
require_once __DIR__ . '/../models/User.php';  // Modifier le chemin si nécessaire


class UserService {
    private $userDAO;

    public function __construct() {
        $this->userDAO = new UserDAO(); // Initialisation du DAO pour interagir avec la base de données
    }

    // Méthode d'inscription
    public function register($username, $email, $password) {
        echo "register() de UserService appelé<br>";
    
        // Vérifier si l'email existe déjà
        if ($this->userDAO->emailExists($email)) {
            echo "L'email existe déjà<br>";
            return false; // L'email existe déjà
        }
    
        // Hacher le mot de passe pour la sécurité
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
        // Créer l'utilisateur dans la base de données
        $user = new User($username, $email, $hashedPassword); // Créer un objet User
    
        // Ajouter l'utilisateur via le DAO
        echo "Ajout de l'utilisateur dans la base de données<br>";
        return $this->userDAO->addUser($user);
    }
    
}

