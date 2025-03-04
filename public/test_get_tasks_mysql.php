<?php
require_once './controllers/TaskController.php';

$controller = new TaskController();

// 🔹 Récupérer les tâches depuis MySQL
$controller->getTasks(1, "mysql");
?>
