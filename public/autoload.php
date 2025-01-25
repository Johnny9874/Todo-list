<?php

spl_autoload_register(function ($class_name) {
    // Remplacer les \ par des / et rechercher dans le bon répertoire
    $class_path = str_replace('\\', '/', $class_name);

    // Inclure la classe depuis le bon dossier
    include __DIR__ . '/../src/' . $class_path . '.php';
});
?>
