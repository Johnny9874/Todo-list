<?php
// Connexion à la base de données MySQL
namespace Dao;  // Assure-toi que le namespace est 'Services' si c'est ainsi que tu l'as défini dans composer.json

use Exception;

class TaskDAO {

    public function addTask($title, $description, $userId, $priority, $status, $due_date, $task_data) {
        global $conn;
        
        // Vérifier si l'utilisateur existe
        $sql = "SELECT id FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("L'utilisateur avec l'ID {$userId} n'existe pas.");
        }
    
        // L'utilisateur existe, on peut insérer la tâche
        $sql = "INSERT INTO tasks (title, description, user_id, priority, status, due_date, task_data) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
    
        if ($stmt === false) {
            throw new Exception("Error preparing the query: " . $conn->error);
        }
    
        $stmt->bind_param("sssisss", $title, $description, $userId, $priority, $status, $due_date, $task_data);
    
        if (!$stmt->execute()) {
            throw new Exception("Error executing the query: " . $stmt->error);
        }
    
        $stmt->close();
    }
    
    

    // Récupérer les tâches de MySQL par utilisateur
    public function getTasksByUser($userId) {
        global $conn;
    
        $sql = "SELECT * FROM tasks WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
    
        if ($stmt === false) {
            die("Erreur lors de la préparation de la requête : " . $conn->error);
        }
    
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $tasks = [];
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
    
        $stmt->close();
        return $tasks;
    }
    

    // Supprimer une tâche en MySQL
    public function deleteTask($taskId) {
        global $conn;

        $sql = "DELETE FROM tasks WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Erreur lors de la préparation de la requête : " . $conn->error);
        }

        $stmt->bind_param("i", $taskId);
        if (!$stmt->execute()) {
            die("Erreur lors de l'exécution de la requête : " . $stmt->error);
        }

        $stmt->close();
    }

    // Récupérer une tâche par son ID
    public function getTaskById($taskId) {
        global $conn;

        $sql = "SELECT * FROM tasks WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Erreur lors de la préparation de la requête : " . $conn->error);
        }

        $stmt->bind_param("i", $taskId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null; // Aucune tâche trouvée
        }

        $task = $result->fetch_assoc();
        $stmt->close();
        return $task;
    }
}
?>
