<?php

// On va gérer l'inscription et l'authentification des utilisateurs via le service UserService.

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../services/UserService.php'; // Utiliser le chemin absolu relatif au fichier

class UserController {

    private $userService;

    public function __construct() {
        $this->userService = new UserService(); // Initialiser UserService
    }

    // Méthode pour l'inscription de l'utilisateur
    public function register() {
        echo "register() appelé<br>";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Afficher les données reçues pour le débogage
            echo "<pre>";
            print_r($_POST);  // Afficher tout ce que l'on reçoit dans $_POST
            echo "</pre>";

            // Récupérer les données de l'utilisateur envoyées via le formulaire
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
                // Si l'email est déjà utilisé, afficher un message d'erreur
                echo "L'email est déjà utilisé.<br>";
            }
        }
    }

    // Méthode pour la connexion d'un utilisateur
    public function login() {
        // Vérifier si la méthode de la requête est POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire de connexion
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
                    header('Location: html/main.html');
                    exit();  // Assurez-vous d'ajouter exit pour stopper l'exécution du script
                } else {
                    // Afficher une erreur si le mot de passe est incorrect
                    echo "Mot de passe incorrect.";
                }
            } else {
                // Afficher une erreur si l'utilisateur n'est pas trouvé
                echo "Aucun utilisateur trouvé avec ces informations.";
            }

            $stmt->close();
            $conn->close();
        }
    }

    // Méthode pour mettre à jour le profil de l'utilisateur
    public function updateProfile() {
        session_start();
    
        // Vérifiez si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            echo "Vous devez être connecté pour modifier votre profil.";
            return;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire de mise à jour
            $userId = $_SESSION['user_id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
    
            // Connexion à la base de données
            $conn = new mysqli('localhost', 'root', '', 'todo-list');
            if ($conn->connect_error) {
                die("Erreur de connexion : " . $conn->connect_error);
            }
    
            // Préparer la requête SQL pour mettre à jour le profil
            if (!empty($password)) {
                // Si un nouveau mot de passe est fourni, le hacher avant de le sauvegarder
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $username, $email, $hashedPassword, $userId);
            } else {
                // Si aucun mot de passe n'est fourni, ne pas mettre à jour ce champ
                $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $username, $email, $userId);
            }

            // Exécuter la requête et vérifier si elle réussit
            if ($stmt->execute()) {

                // Rediriger vers main.html après la mise à jour du profil
                error_log("Redirection vers : /todo-list/html/main.html");
                header('Location: /todo-list/public/html/main.html');
                exit();  // Assurez-vous d'ajouter exit pour stopper l'exécution du script
            } else {
                // Si une erreur se produit, afficher un message d'erreur
                echo "Erreur lors de la mise à jour du profil : " . $conn->error;
            }
    
            $stmt->close();
            $conn->close();
        }
    }
    
    // Méthode pour récupérer le profil de l'utilisateur
    public function getProfile() {
        session_start();
    
        // Vérifiez si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            echo "Vous devez être connecté pour voir votre profil.";
            return;
        }
    
        // Récupérer l'ID de l'utilisateur depuis la session
        $userId = $_SESSION['user_id'];
    
        // Connexion à la base de données
        $conn = new mysqli('localhost', 'root', '', 'todo-list');
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }
    
        // Requête SQL pour récupérer le profil de l'utilisateur
        $sql = "SELECT username, email FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Si l'utilisateur existe, retourner ses informations
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user;
        } else {
            // Si l'utilisateur n'est pas trouvé, afficher un message d'erreur
            echo "Utilisateur introuvable.";
            return null;
        }
    
        $stmt->close();
        $conn->close();
    }    
}
?>