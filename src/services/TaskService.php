<?php
require_once '../dao/TaskDAO.php';  // Inclure le fichier TaskDAO pour MySQL
require_once '../src/TodoManager.php';  // Inclure le fichier de gestion MongoDB

class TaskService {
    private $taskDAO;  // Gestion MySQL
    private $mongoManager; // Gestion MongoDB

    public function __construct() {
        $this->taskDAO = new TaskDAO();  // Instancier MySQL
        $this->mongoManager = new TodoManager(); // Instancier MongoDB
    }

    // Ajouter une tâche dans MySQL et MongoDB
    public function addTask($title, $description, $userId) {
        if (empty($title)) {
            throw new Exception("Le titre de la tâche est obligatoire.");
        }

        // Ajouter en MySQL
        $this->taskDAO->addTask($title, $description, $userId);

        // Ajouter en MongoDB
        $this->mongoManager->createTodo($title, $description);
    }

    // Récupérer les tâches de MySQL ou MongoDB
    public function getTasksByUser($userId, $source = "mysql") {
        if ($source === "mysql") {
            return $this->taskDAO->getTasksByUser($userId);
        } elseif ($source === "mongodb") {
            return $this->mongoManager->getTodos(); 
        } else {
            throw new Exception("Source inconnue !");
        }
    }

    // Supprimer une tâche en MySQL et MongoDB
    public function deleteTask($taskId) {
        $task = $this->taskDAO->getTaskById($taskId);
        if (!$task) {
            throw new Exception("Tâche non trouvée.");
        }

        // Supprimer en MySQL
        $this->taskDAO->deleteTask($taskId);

        // Supprimer en MongoDB
        $this->mongoManager->deleteTodo($taskId);
    }
}
?>
