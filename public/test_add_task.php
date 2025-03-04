<?php
require_once './controllers/TaskController.php';

$controller = new TaskController();

// 🔹 Tester l'ajout d'une tâche
$controller->addTask("Acheter du lait", "Aller au supermarché", 1);
?>
