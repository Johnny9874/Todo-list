<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'todo-list';
    private $username = 'root';
    private $password = '';
    private $conn;

    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Méthode pour obtenir l'instance de la connexion PDO
    public static function getInstance() {
        static $instance;
        if (!$instance) {
            $instance = new Database();
        }
        return $instance;
    }

    // Retourner la connexion PDO
    public function getConnection() {
        return $this->conn;
    }
}
?>
