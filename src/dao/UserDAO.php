<?php
require_once __DIR__ .'/../config/Database.php'; // Inclure le fichier Database.php

class UserDAO {
    private $connection;

    public function __construct() {
        // Utilisation de getInstance() pour récupérer l'instance de Database
        $db = Database::getInstance();
        $this->connection = $db->getConnection();  // Récupérer la connexion PDO
    }

    // Méthodes pour interagir avec la base de données (ex. ajouter un utilisateur)
    public function addUser($user) {
        $stmt = $this->connection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$user->getUsername(), $user->getEmail(), $user->getPassword()]);
        return $this->connection->lastInsertId();
    }

    // Exemple d'autres méthodes comme vérifier l'email, etc.
    public function emailExists($email) {
        $stmt = $this->connection->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ? true : false;
    }
}
?>