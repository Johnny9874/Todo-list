<?php
require 'vendor/autoload.php'; // Charge le client MongoDB

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->todo_db->tasks;

// Vérifier si un argument est fourni
if ($argc < 2) {
    echo "Usage:\n";
    echo "  php mongo_component.php list        # Afficher toutes les tâches\n";
    echo "  php mongo_component.php add 'Titre' # Ajouter une tâche\n";
    echo "  php mongo_component.php delete ID   # Supprimer une tâche\n";
    exit(1);
}

$command = $argv[1];

if ($command === "list") {
    $tasks = $collection->find();
    foreach ($tasks as $task) {
        echo "[{$task['_id']}] " . $task['title'] . " - " . ($task['completed'] ? "✔️" : "❌") . "\n";
    }
} elseif ($command === "add" && isset($argv[2])) {
    $title = $argv[2];
    $collection->insertOne(["title" => $title, "completed" => false]);
    echo "Tâche ajoutée : $title\n";
} elseif ($command === "delete" && isset($argv[2])) {
    $id = $argv[2];
    $collection->deleteOne(["_id" => new MongoDB\BSON\ObjectId($id)]);
    echo "Tâche supprimée : $id\n";
} else {
    echo "Commande invalide. Utilisez `php mongo_component.php` pour voir les options.\n";
}
?>
