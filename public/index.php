<?php

require '../src/config/Database.php';
require '../src/dao/UserDAO.php';
require '../src/dao/TaskDAO.php';

$userDAO = new UserDAO();
$taskDAO = new TaskDAO();

// Ajouter un utilisateur
$userDAO->addUser('john_doe', 'john@example.com', 'securepassword');

// Récupérer l'utilisateur
$user = $userDAO->getUserByEmail('john@example.com');
echo 'Utilisateur : ' . $user['username'] . '<br>';

// Ajouter une tâche pour l'utilisateur
$taskDAO->addTask('Apprendre PHP', 'Créer une application de to-do list', $user['id']);

// Récupérer les tâches
$tasks = $taskDAO->getTasksByUser($user['id']);
foreach ($tasks as $task) {
    echo 'Tâche : ' . $task['title'] . '<br>';
}
