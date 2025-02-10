<?php
namespace Controllers;
class UserController {
    
    // Autres méthodes ici...

    // Méthode pour mettre à jour le profil d'un utilisateur
    public function updateProfile() {
        session_start();

        if (isset($_SESSION['user_id'], $_POST['username'], $_POST['email'])) {
            $userId = $_SESSION['user_id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

            global $conn;

            // Mettre à jour le profil de l'utilisateur
            if ($password) {
                // Si le mot de passe est fourni, on le met à jour aussi
                $sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $username, $email, $password, $userId);
            } else {
                // Si le mot de passe n'est pas fourni, on ne le met pas à jour
                $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $username, $email, $userId);
            }

            $stmt->execute();
            header("Location: index.php?action=profile");  // Rediriger vers la page de profil après la mise à jour
            exit();
        } else {
            echo "Erreur dans la mise à jour du profil.";
        }
    }
}
?>