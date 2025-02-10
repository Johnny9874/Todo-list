<?php
// Récupérer les informations de connexion depuis l'environnement Heroku (JawsDB)
$url = parse_url(getenv('JAWSDB_URL'));

$host = $url['host'];
$port = $url['port'];
$dbname = ltrim($url['path'], '/');  // Le nom de la base de données se trouve après le '/'
$username = $url['user'];
$password = $url['pass'];

// Connexion à la base de données MySQL via mysqli
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
?>
