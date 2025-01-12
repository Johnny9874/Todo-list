<?php

class User {
    private $username;  // Déclaration de la variable privée pour stocker le nom d'utilisateur
    private $email;     // Déclaration de la variable privée pour stocker l'email de l'utilisateur
    private $password;  // Déclaration de la variable privée pour stocker le mot de passe de l'utilisateur

    // Constructeur de la classe User, utilisé pour initialiser les propriétés de l'utilisateur
    public function __construct($username, $email, $password) {
        $this->username = $username;  // Initialiser la propriété $username avec la valeur fournie
        $this->email = $email;        // Initialiser la propriété $email avec la valeur fournie
        $this->password = $password;  // Initialiser la propriété $password avec la valeur fournie
    }

    // Getter pour le nom d'utilisateur
    public function getUsername() {
        return $this->username;  // Retourne la valeur de la propriété $username
    }

    // Getter pour l'email
    public function getEmail() {
        return $this->email;  // Retourne la valeur de la propriété $email
    }

    // Getter pour le mot de passe
    public function getPassword() {
        return $this->password;  // Retourne la valeur de la propriété $password
    }
}
?>