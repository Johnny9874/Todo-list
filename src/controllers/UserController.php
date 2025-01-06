<?php

// On va gérer l'inscription et l'authentification des utilisateurs via le service UserService.

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "UserController.php chargé"; // Débogage

require_once __DIR__ . '/../services/UserService.php'; // Utiliser le chemin absolu relatif au fichier

class UserController {

    private $userService;

    public function __construct() {
        $this->userService = new UserService(); // Initialiser UserService
    }

    public function register() {
        echo "register() appelé<br>";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Afficher les données reçues pour le débogage
            echo "<pre>";
            print_r($_POST);  // Afficher tout ce que l'on reçoit dans $_POST
            echo "</pre>";

            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            echo "Données reçues : $username, $email, $password<br>";

            // Appeler la méthode d'enregistrement dans UserService
            $result = $this->userService->register($username, $email, $password);

            if ($result) {
                // Rediriger après une inscription réussie
                header("Location: ../public/html/success.php");
                exit();  // Assurez-vous de bien arrêter l'exécution après la redirection
            } else {
                echo "L'email est déjà utilisé.<br>";
            }
        }
    }
}    

// Vérifier l'action et appeler la méthode appropriée
$controller = new UserController();

if (isset($_GET['action']) && $_GET['action'] === 'register') {
    $controller->register();  // Appeler la méthode d'inscription
}
?>
