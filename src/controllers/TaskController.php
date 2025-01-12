<?php

//On va gérer l'ajout, la suppression, et l'affichage des tâches via le service TaskService.
require_once '../services/TaskService.php';

class TaskController {
    private $taskService;

    // Le constructeur initialise une instance du service TaskService
    public function __construct() {
        $this->taskService = new TaskService();
    }

    // Méthode pour ajouter une tâche
    public function addTask($title, $description, $userId) {
        try {
            // Appeler la méthode addTask du service TaskService
            $this->taskService->addTask($title, $description, $userId);
            echo "Tâche ajoutée avec succès.";  // Afficher un message de succès
        } catch (Exception $e) {
            // En cas d'erreur, afficher le message d'exception
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Méthode pour récupérer toutes les tâches d'un utilisateur
    public function getTasks($userId) {
        try {
            // Appeler la méthode getTasksByUser du service TaskService pour récupérer les tâches de l'utilisateur
            $tasks = $this->taskService->getTasksByUser($userId);
            // Afficher les tâches récupérées (ici, les afficher sous forme de tableau avec print_r)
            print_r($tasks);
        } catch (Exception $e) {
            // En cas d'erreur, afficher le message d'exception
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Méthode pour supprimer une tâche
    public function deleteTask($taskId) {
        try {
            // Appeler la méthode deleteTask du service TaskService pour supprimer la tâche
            $this->taskService->deleteTask($taskId);
            echo "Tâche supprimée avec succès.";  // Afficher un message de succès
        } catch (Exception $e) {
            // En cas d'erreur, afficher le message d'exception
            echo "Erreur : " . $e->getMessage();
        }
    }
}
?>