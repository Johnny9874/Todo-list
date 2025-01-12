<?php
class Database {
    private $host = 'localhost';        // Hôte de la base de données (généralement 'localhost' pour la base locale)
    private $dbname = 'todo-list';      // Nom de la base de données
    private $username = 'root';         // Nom d'utilisateur pour la connexion à la base de données (par défaut 'root' sur XAMPP)
    private $password = '';             // Mot de passe pour la connexion à la base de données (vide pour XAMPP)
    private $conn;                      // Variable qui contiendra l'objet de connexion PDO

    // Le constructeur est privé pour empêcher la création d'une nouvelle instance à l'extérieur de la classe
    private function __construct() {
        try {
            // Créer une instance PDO pour la connexion à la base de données
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            // Définir l'attribut pour afficher les erreurs liées à PDO
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // En cas d'erreur de connexion, afficher un message d'erreur
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Méthode statique pour obtenir l'instance unique de la connexion PDO
    public static function getInstance() {
        static $instance;
        // Si l'instance n'a pas encore été créée, la créer
        if (!$instance) {
            $instance = new Database();
        }
        // Retourner l'instance de la connexion PDO
        return $instance;
    }

    // Retourner l'objet de connexion PDO
    public function getConnection() {
        return $this->conn;
    }
}
?>
