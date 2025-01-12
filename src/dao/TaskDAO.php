<?php

class TaskDAO {
    private $pdo;  // Déclaration de la variable privée $pdo pour stocker la connexion à la base de données

    public function __construct() {
        // Récupération de l'instance de la base de données à l'aide de la méthode getInstance()
        $this->pdo = Database::getInstance(); 
    }

    // Méthode pour ajouter une tâche
    public function addTask($title, $description, $userId) {
        // Préparer la requête SQL pour insérer une tâche dans la table tasks
        $stmt = $this->pdo->prepare("INSERT INTO tasks (title, description, user_id) VALUES (:title, :description, :user_id)");
        
        // Exécuter la requête en passant les valeurs du titre, de la description et de l'ID de l'utilisateur
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':user_id' => $userId,
        ]);
    }

    // Méthode pour récupérer les tâches d'un utilisateur
    public function getTasksByUser($userId) {
        // Préparer la requête SQL pour récupérer toutes les tâches d'un utilisateur spécifié par user_id
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE user_id = :user_id");
        
        // Exécuter la requête en passant l'ID de l'utilisateur comme paramètre
        $stmt->execute([':user_id' => $userId]);
        
        // Retourner les résultats sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour mettre à jour une tâche
    public function updateTask($id, $title, $description) {
        // Préparer la requête SQL pour mettre à jour une tâche dans la table tasks
        $stmt = $this->pdo->prepare("UPDATE tasks SET title = :title, description = :description WHERE id = :id");
        
        // Exécuter la requête en passant l'ID, le titre et la description de la tâche
        $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':description' => $description,
        ]);
    }

    // Méthode pour supprimer une tâche
    public function deleteTask($id) {
        // Préparer la requête SQL pour supprimer une tâche dans la table tasks en fonction de son ID
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = :id");
        
        // Exécuter la requête en passant l'ID de la tâche à supprimer
        $stmt->execute([':id' => $id]);
    }
}
?>
