<?php

namespace Controllers;

// Vérifier si la variable d'environnement JAWSDB existe
if (getenv('JAWSDB_URL')) {
    $url = parse_url(getenv('JAWSDB_URL'));

    $servername = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $dbname = substr($url["path"], 1); // Enlever le "/" avant le nom de la base de données

    // Afficher pour débogage
    error_log("Connexion à la base de données JAWSDB: hôte=$servername, utilisateur=$username, base=$dbname");

    // Connexion à la base de données MySQL sur Heroku (JAWSDB)
    $conn = new mysqli($servername, $username, $password, $dbname);
} else {
    // Si la variable d'environnement n'est pas présente, tenter une connexion locale (si applicable)
    $conn = new mysqli('localhost', 'root', '', 'todo-list');
}

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
error_log("Connexion réussie à la base de données.");
?>
