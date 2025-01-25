<?php
// Vérifier si l'on est sur Heroku, sinon utiliser la base de données locale
$servername = "localhost"; // Valeur par défaut pour la connexion locale
$username = "root";        // Nom d'utilisateur par défaut pour local
$password = "";            // Mot de passe par défaut pour local
$dbname = "todo-list";     // Nom de la base de données locale

// Si on est sur Heroku, on utilise les variables d'environnement
if (getenv('CLEARDB_DATABASE_URL')) {
    $url = parse_url(getenv('CLEARDB_DATABASE_URL'));
    $servername = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $dbname = substr($url["path"], 1); // On retire le slash devant le nom de la DB
}

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
?>
