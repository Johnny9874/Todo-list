<?php
require '../src/config/Database.php';
require '../src/dao/UserDAO.php';
require '../src/dao/TaskDAO.php';

echo "<h1>Tests des composants DAO</h1>";

try {
    // Initialisation des DAO
    $userDAO = new UserDAO();
    $taskDAO = new TaskDAO();

    // Test de UserDAO
    echo "<h2>Test de UserDAO</h2>";
    $userDAO->addUser('test_user', 'test@example.com', 'password123');
    echo "Utilisateur ajouté avec succès.<br>";

    $user = $userDAO->getUserByEmail('test@example.com');
    echo "Utilisateur récupéré : " . $user['username'] . " (ID : " . $user['id'] . ")<br>";

    // Test de TaskDAO
    echo "<h2>Test de TaskDAO</h2>";
    $taskDAO->addTask('Test Task', 'Ceci est une tâche de test.', $user['id']);
    echo "Tâche ajoutée avec succès.<br>";

    $tasks = $taskDAO->getTasksByUser($user['id']);
    echo "Tâches récupérées pour l'utilisateur " . $user['username'] . " :<br>";
    foreach ($tasks as $task) {
        echo "- " . $task['title'] . " : " . $task['description'] . "<br>";
    }

    echo "<h2>Tests terminés avec succès !</h2>";

} catch (Exception $e) {
    echo "Erreur pendant les tests : " . $e->getMessage();
}
