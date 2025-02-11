<?php
namespace Controllers;

// Inclure le fichier de connexion à la base de données
require_once __DIR__ . '/../db.php';  

class UserController {

    // Méthode pour inscrire un utilisateur
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $conn;
            // Vérifier que les champs sont remplis
            if (!isset($_POST['username'], $_POST['email'], $_POST['password'])) {
                die("Erreur : Tous les champs doivent être remplis.");
            }

            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            global $conn;

            // Vérifier que la connexion est bien active
            if (!$conn) {
                die("Erreur : La connexion à la base de données est introuvable.");
            }

            // Préparer la requête SQL
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                die("Erreur lors de la préparation de la requête : " . $conn->error);
            }

            $stmt->bind_param("sss", $username, $email, $password);

            if ($stmt->execute()) {
                echo "Utilisateur inscrit avec succès!";
            } else {
                echo "Erreur lors de l'inscription : " . $stmt->error;
            }

            $stmt->close();
        } else {
            include __DIR__ . '/../public/html/register.html';
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            global $conn;

            if (!$conn) {
                die("La connexion à la base de données a échoué.");
            }

            $sql = "SELECT id, username, password FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Erreur lors de la préparation de la requête : " . $conn->error);
            }

            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    echo "Connexion réussie !";
                    // Redirection vers le tableau de bord ou une autre page après connexion
                    header("Location: /html/main.html");
                    exit();
                } else {
                    echo "Mot de passe incorrect.";
                }
            } else {
                echo "Aucun utilisateur trouvé avec cet e-mail.";
            }
        }
    }

    public function getProfile() {
        session_start();
    
        if (!isset($_SESSION['user_id'])) {
            // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
            header("Location: /html/login.html");
            exit();
        }
    
        // L'utilisateur est connecté, on peut afficher la page de profil
        header("Location: /html/profile.html");
        exit();
    }

    // Méthode pour mettre à jour le profil d'un utilisateur
    public function updateProfile() {
        session_start();
        session_regenerate_id(true);  // 🔄 Sécurise la session
    
        var_dump($_SESSION);
        var_dump($_POST);
    
        if (!isset($_SESSION['user_id'])) {
            die("Erreur : L'utilisateur n'est pas connecté.");
        }
    
        if (!isset($_POST['username'], $_POST['email'])) {
            die("Erreur : Informations utilisateur incomplètes.");
        }
    
        $userId = $_SESSION['user_id'];
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = isset($_POST['password']) && !empty($_POST['password']) 
            ? password_hash($_POST['password'], PASSWORD_BCRYPT) 
            : null;
    
        global $conn;
    
        // Vérifier que la connexion est bien active
        if (!$conn) {
            die("Erreur : La connexion à la base de données est introuvable.");
        }
    
        // Construire la requête SQL
        if ($password) {
            $sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
        } else {
            $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        }
    
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erreur lors de la préparation de la requête : " . $conn->error);
        }
    
        // Liaison des paramètres
        if ($password) {
            $stmt->bind_param("sssi", $username, $email, $password, $userId);
        } else {
            $stmt->bind_param("ssi", $username, $email, $userId);
        }
    
        // Exécuter la requête
        if ($stmt->execute()) {
            header("Location: index.php?action=profile");  
            exit();
        } else {
            die("Erreur lors de la mise à jour du profil : " . $stmt->error);
        }
    
        $stmt->close();
    }
}
?>
