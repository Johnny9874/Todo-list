<?php
class Database {
    private $conn; // Variable qui contiendra l'objet de connexion PDO

    // Le constructeur est privé pour empêcher la création d'une nouvelle instance à l'extérieur de la classe
    private function __construct() {
        try {
            // Récupérer l'URL de la base de données à partir des variables d'environnement
            $database_url = getenv("DATABASE_URL");
            
            if (!$database_url) {
                throw new Exception("DATABASE_URL non défini dans les variables d'environnement.");
            }

            // Parser l'URL pour extraire les informations de connexion
            $db_parts = parse_url($database_url);
            $host = $db_parts['host'];
            $port = $db_parts['port'];
            $dbname = ltrim($db_parts['path'], '/');
            $username = $db_parts['user'];
            $password = $db_parts['pass'];

            // Créer une instance PDO pour la connexion à la base de données
            $this->conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
            // Définir l'attribut pour afficher les erreurs liées à PDO
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // En cas d'erreur de connexion, afficher un message d'erreur
            echo "Connection failed: " . $e->getMessage();
            exit;
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
            exit;
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
