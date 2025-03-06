<?php
// Connexion à la base de données
$url = getenv('DATABASE_URL');

if (!$url) {
    die("Erreur : La variable d'environnement DATABASE_URL n'est pas définie.");
}

$url = parse_url($url);

$host = $url['host'];
$port = isset($url['port']) ? $url['port'] : 3306;
$dbname = ltrim($url['path'], '/');
$username = $url['user'];
$password = $url['pass'];

// Connexion à MySQL
$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
} else {
    echo "Connexion à la base de données réussie !";
}
?>
