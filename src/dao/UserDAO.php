<?php

class UserDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // Ajouter un utilisateur
    public function addUser($username, $email, $password) {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT), // Sécurisation du mot de passe
        ]);
    }

    // Récupérer un utilisateur par email
    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
