<?php
// On va créer un service qui interagit avec TaskDAO pour ajouter, récupérer, et manipuler les tâches.
require_once '../dao/TaskDAO.php';  // Inclure le fichier TaskDAO pour accéder aux méthodes qui interagissent avec la base de données

class TaskService {
    private $taskDAO;  // Déclaration de la variable qui va contenir une instance de TaskDAO

    public function __construct() {
        $this->taskDAO = new TaskDAO();  // Instancier le DAO pour interagir avec la base de données
    }

    // Ajouter une tâche
    public function addTask($title, $description, $userId) {
        // Si le titre de la tâche est vide, une exception est lancée
        if (empty($title)) {
            throw new Exception("Le titre de la tâche est obligatoire.");
        }

        // Ajouter la tâche à la base de données via le DAO
        $this->taskDAO->addTask($title, $description, $userId);
    }

    // Récupérer les tâches d'un utilisateur
    public function getTasksByUser($userId) {
        // Appel de la méthode du DAO pour récupérer les tâches associées à un utilisateur
        return $this->taskDAO->getTasksByUser($userId);
    }

    // Marquer une tâche comme terminée
    public function completeTask($taskId) {
        // Vérifier si la tâche existe, puis la marquer comme terminée
        $task = $this->taskDAO->getTaskById($taskId);  // Récupérer la tâche par son ID
        if (!$task) {  // Si la tâche n'existe pas, une exception est lancée
            throw new Exception("Tâche non trouvée.");
        }
        // Mettre à jour le statut de la tâche en 'completed'
        $this->taskDAO->updateTaskStatus($taskId, 'completed');
    }

    // Supprimer une tâche
    public function deleteTask($taskId) {
        // Vérifier si la tâche existe, puis la supprimer
        $task = $this->taskDAO->getTaskById($taskId);  // Récupérer la tâche par son ID
        if (!$task) {  // Si la tâche n'existe pas, une exception est lancée
            throw new Exception("Tâche non trouvée.");
        }
        // Supprimer la tâche de la base de données
        $this->taskDAO->deleteTask($taskId);
    }
}
?>