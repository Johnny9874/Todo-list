<?php
require_once './controllers/TaskController.php';

$controller = new TaskController();

// 🔹 Récupérer les tâches depuis MongoDB
$controller->getTasks(1, "mongodb");
?>
