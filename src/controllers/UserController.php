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
       
                // Vérifier que la connexion à la base de données est active
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
       
                // Exécuter la requête
                if ($stmt->execute()) {
                    // Rediriger vers la page de connexion après l'inscription réussie
                    header("Location: /html/login.html");
                    exit();  // Assurer que l'exécution s'arrête après la redirection
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
                $username = $_POST['username'];  // Le username est aussi récupéré du formulaire
                $password = $_POST['password'];
        
                global $conn;
        
                // Affiche les données reçues pour vérifier
                echo "Email: " . $email . "<br>";
                echo "Username: " . $username . "<br>";
                echo "Password: " . $password . "<br>";
        
                if (!$conn) {
                    die("La connexion à la base de données a échoué.");
                }
        
                // Vérifier si l'email ou le username existe dans la base de données
                $sql = "SELECT id, username, password FROM users WHERE email = ? OR username = ?";
                $stmt = $conn->prepare($sql);
        
                if ($stmt === false) {
                    die("Erreur lors de la préparation de la requête : " . $conn->error);
                }
        
                $stmt->bind_param("ss", $email, $username);  // Nous passons email et username comme paramètres
                $stmt->execute();
                $result = $stmt->get_result();
        
                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        session_start();  // Démarrer la session ici pour l'utiliser
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
        
                        // Affiche que la connexion est réussie
                        echo "Connexion réussie !";
        
                        // Ajouter une redirection explicite
                        header("Location: /html/main.html");
                        exit();  // Assure-toi que l'exécution s'arrête après la redirection
                    } else {
                        echo "Mot de passe incorrect.";
                    }
                } else {
                    echo "Aucun utilisateur trouvé avec cet e-mail ou ce nom d'utilisateur.";
                }
            }
        }
        
        

        public function getProfile() {
            session_start();  // Vérifier que la session est déjà démarrée
            
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
            session_start();  // Démarrer la session ici aussi
            session_regenerate_id(true);  // 🔄 Sécurise la session
        
            // Vérifier si l'utilisateur est connecté
            if (!isset($_SESSION['user_id'])) {
                die("Erreur : L'utilisateur n'est pas connecté.");
            }
        
            // Vérifier si les données sont envoyées via POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
                // Vérifier que les champs nécessaires sont présents
                if (!isset($_POST['username'], $_POST['email'])) {
                    die("Erreur : Informations utilisateur incomplètes.");
                }
        
                // Récupérer les données du formulaire
                $userId = $_SESSION['user_id'];
                $username = trim($_POST['username']);
                $email = trim($_POST['email']);
                $password = isset($_POST['password']) && !empty($_POST['password']) 
                    ? password_hash($_POST['password'], PASSWORD_BCRYPT) 
                    : null;
        
                global $conn;
        
                // Vérifier que la connexion à la base de données est active
                if (!$conn) {
                    die("Erreur : La connexion à la base de données est introuvable.");
                }
        
                // Construire la requête SQL pour mettre à jour le profil
                if ($password) {
                    // Si un mot de passe est fourni, on l'ajoute à la requête
                    $sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
                } else {
                    // Sinon, on met à jour seulement le nom d'utilisateur et l'email
                    $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
                }
        
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    die("Erreur lors de la préparation de la requête : " . $conn->error);
                }
        
                // Lier les paramètres
                if ($password) {
                    $stmt->bind_param("sssi", $username, $email, $password, $userId);
                } else {
                    $stmt->bind_param("ssi", $username, $email, $userId);
                }
        
                // Exécuter la requête
                if ($stmt->execute()) {
                    // Rediriger vers la page de profil après la mise à jour réussie
                    header("Location: /html/profile.html");
                    exit();  // S'assurer que l'exécution s'arrête après la redirection
                } else {
                    die("Erreur lors de la mise à jour du profil : " . $stmt->error);
                }
        
                $stmt->close();
            }
        }
        
    }
    ?>
