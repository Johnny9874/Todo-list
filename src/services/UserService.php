<?php

// On va créer un service qui interagit avec UserDAO pour ajouter, récupérer, et manipuler les utilisateurs.
// Le fichier UserDAO.php contient la logique d'accès à la base de données pour les utilisateurs
// Le fichier User.php est le modèle qui représente la structure d'un utilisateur
require_once __DIR__ . '/../dao/UserDAO.php';  // Utilisez __DIR__ pour spécifier le chemin absolu du fichier UserDAO
require_once __DIR__ . '/../models/User.php';  // Le modèle User qui est utilisé pour créer et manipuler les utilisateurs

// La classe UserService est responsable de la logique métier liée aux utilisateurs
class UserService {
    private $userDAO;  // Déclaration de la variable pour l'instance de UserDAO

    // Le constructeur de la classe UserService
    public function __construct() {
        $this->userDAO = new UserDAO(); // Initialisation du DAO (Data Access Object) pour interagir avec la base de données
    }

    // Méthode d'inscription des utilisateurs
    public function register($username, $email, $password) {
        echo "register() de UserService appelé<br>";  // Affiche un message pour indiquer que la méthode a été appelée

        // Vérifier si l'email existe déjà dans la base de données
        if ($this->userDAO->emailExists($email)) {
            echo "L'email existe déjà<br>";  // Affiche un message si l'email existe déjà dans la base de données
            return false; // Retourne false si l'email est déjà pris
        }

        // Hacher le mot de passe pour garantir la sécurité (le stockage du mot de passe en clair est à éviter)
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);  // Hash du mot de passe en utilisant l'algorithme BCRYPT pour la sécurité

        // Créer un objet utilisateur avec les informations passées (nom d'utilisateur, email et mot de passe haché)
        $user = new User($username, $email, $hashedPassword); // Création d'une nouvelle instance de l'utilisateur

        // Ajouter l'utilisateur dans la base de données en appelant la méthode d'ajout de l'objet UserDAO
        echo "Ajout de l'utilisateur dans la base de données<br>";  // Affiche un message pour indiquer que l'utilisateur est ajouté à la base de données
        return $this->userDAO->addUser($user);  // Retourne le résultat de l'ajout de l'utilisateur dans la base de données
    }
}
?>