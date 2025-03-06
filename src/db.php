<?php
global $conn;

$url = getenv('DATABASE_URL');

if (!$url) {
    error_log("La variable d'environnement DATABASE_URL n'est pas définie.");
    die("Erreur : La variable d'environnement DATABASE_URL n'est pas définie.");
}

$url = parse_url($url);

$host = $url['host'];
$port = isset($url['port']) ? $url['port'] : 3306;
$dbname = ltrim($url['path'], '/');
$username = $url['user'];
$password = $url['pass'];

// Connexion à MySQL via mysqli
$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    error_log("Erreur de connexion à la base de données : " . $conn->connect_error); // Enregistrer l'erreur
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
} else {
    error_log("Connexion à la base de données réussie !");
}

?>
