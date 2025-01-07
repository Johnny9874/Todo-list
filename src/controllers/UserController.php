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

    // Méthode pour la connexion d'un utilisateur
    public function login() {
        // Vérifiez si la méthode de la requête est POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $usernameOrEmail = $_POST['username'];
            $password = $_POST['password'];

            // Connexion à la base de données
            $conn = new mysqli('localhost', 'root', '', 'todo-list');
            if ($conn->connect_error) {
                die("Erreur de connexion : " . $conn->connect_error);
            }

            // Requête pour vérifier l'existence de l'utilisateur par username ou email
            $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
            $stmt->execute();
            $result = $stmt->get_result();

            // Si l'utilisateur existe
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Vérifier le mot de passe
                if (password_verify($password, $user['password'])) {
                    // Connexion réussie, démarrer la session
                    session_start();
                    $_SESSION['user_id'] = $user['id']; // Stocker l'ID de l'utilisateur
                    $_SESSION['username'] = $user['username']; // Stocker le nom d'utilisateur
                    
                    // Rediriger vers la page d'accueil ou tableau de bord
                    header('Location: ../public/html/main.html');
                    exit();
                } else {
                    echo "Mot de passe incorrect.";
                }
            } else {
                echo "Aucun utilisateur trouvé avec ces informations.";
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>
