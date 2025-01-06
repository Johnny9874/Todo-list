<?php

class TaskDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // Ajouter une tâche
    public function addTask($title, $description, $userId) {
        $stmt = $this->pdo->prepare("INSERT INTO tasks (title, description, user_id) VALUES (:title, :description, :user_id)");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':user_id' => $userId,
        ]);
    }

    // Récupérer les tâches d'un utilisateur
    public function getTasksByUser($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mettre à jour une tâche
    public function updateTask($id, $title, $description) {
        $stmt = $this->pdo->prepare("UPDATE tasks SET title = :title, description = :description WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':description' => $description,
        ]);
    }

    // Supprimer une tâche
    public function deleteTask($id) {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
