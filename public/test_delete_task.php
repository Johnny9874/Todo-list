<?php
require_once './controllers/TaskController.php';

$controller = new TaskController();

// Remplace par un ID valide récupéré via test_get_tasks_mysql.php ou test_get_tasks_mongodb.php
$taskId = "ID_DE_LA_TACHE_A_SUPPRIMER";

// 🔹 Supprimer une tâche
$controller->deleteTask($taskId);
?>
