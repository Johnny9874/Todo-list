<?php
require_once __DIR__ .'/../config/Database.php'; // Inclure le fichier Database.php pour se connecter à la base de données

class UserDAO {
    private $connection;  // Déclaration de la variable privée $connection pour stocker la connexion à la base de données

    public function __construct() {
        // Utilisation de la méthode getInstance() pour récupérer l'instance de la classe Database
        $db = Database::getInstance();  
        $this->connection = $db->getConnection();  // Récupérer la connexion PDO de la base de données via l'instance de Database
    }

    // Méthode pour ajouter un utilisateur dans la base de données
    public function addUser($user) {
        // Préparer la requête SQL pour insérer un utilisateur
        $stmt = $this->connection->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        
        // Exécuter la requête en passant les valeurs du nom d'utilisateur, de l'email et du mot de passe
        $stmt->execute([$user->getUsername(), $user->getEmail(), $user->getPassword()]);
        
        // Retourner l'ID de l'utilisateur ajouté (id auto-incrémenté de la base de données)
        return $this->connection->lastInsertId();
    }

    // Méthode pour vérifier si un email existe déjà dans la base de données
    public function emailExists($email) {
        // Préparer la requête SQL pour vérifier si l'email existe déjà
        $stmt = $this->connection->prepare("SELECT id FROM users WHERE email = ?");
        // Exécuter la requête en passant l'email comme paramètre
        $stmt->execute([$email]);
        
        // Si l'email existe, retourner true, sinon false
        return $stmt->fetch() ? true : false;
    }
}
?>
