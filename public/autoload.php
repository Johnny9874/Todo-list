<?php
spl_autoload_register(function ($class_name) {
    // Convertit le namespace en chemin relatif
    $class_path = str_replace('\\', '/', $class_name);
    
    // Inclut le fichier correspondant dans src/controllers
    include __DIR__ . '/../src/controllers/' . $class_path . '.php';
});
?>

