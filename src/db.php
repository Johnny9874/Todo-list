<?php
// Récupérer les informations de connexion depuis l'environnement Heroku (JawsDB)
$url = getenv('JAWSDB_URL');

if (!$url) {
    die("Erreur : La variable d'environnement JAWSDB_URL n'est pas définie.");
}

// Analyser l'URL de connexion
$url = parse_url($url);

$host = $url['host'];
$port = isset($url['port']) ? $url['port'] : 3306; // Port par défaut de MySQL
$dbname = ltrim($url['path'], '/');  // Supprimer le premier '/' du nom de la base de données
$username = $url['user'];
$password = $url['pass'];

// Connexion à MySQL via mysqli
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}
?>
