<?php
spl_autoload_register(function ($class_name) {
    // Remplacer le namespace pour obtenir le bon chemin
    $class_path = str_replace('\\', '/', $class_name);

    // Inclure la classe en cherchant dans le dossier controllers
    // Le chemin est relatif à /public, donc ../src/controllers/
    include __DIR__ . '/../src/controllers/' . $class_path . '.php';
});
?>