<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';

$client = new MongoDB\Client(getenv("MONGODB_URI"));

try {
    $dbs = $client->listDatabases();
    echo "Connexion réussie à MongoDB Atlas !<br>";
    foreach ($dbs as $db) {
        echo "Base de données : " . $db->getName() . "<br>";
    }
} catch (Exception $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
