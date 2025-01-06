<?php

//On va gérer l'ajout, la suppression, et l'affichage des tâches via le service TaskService.
require_once '../services/TaskService.php';

class TaskController {
    private $taskService;

    public function __construct() {
        $this->taskService = new TaskService();
    }

    // Ajouter une tâche
    public function addTask($title, $description, $userId) {
        try {
            $this->taskService->addTask($title, $description, $userId);
            echo "Tâche ajoutée avec succès.";
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Récupérer toutes les tâches d'un utilisateur
    public function getTasks($userId) {
        try {
            $tasks = $this->taskService->getTasksByUser($userId);
            print_r($tasks);
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Supprimer une tâche
    public function deleteTask($taskId) {
        try {
            $this->taskService->deleteTask($taskId);
            echo "Tâche supprimée avec succès.";
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
}
