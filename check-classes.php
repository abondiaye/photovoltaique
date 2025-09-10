<?php
require __DIR__ . '/vendor/autoload.php';

$classes = get_declared_classes();

// Charger toutes les classes PSR-4 d’App\Entity
$dir = __DIR__ . '/src/Entity';
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        require_once $file->getPathname();
    }
}

// Maintenant on re-liste
$newClasses = get_declared_classes();
$entities = array_diff($newClasses, $classes);

foreach ($entities as $class) {
    if (str_starts_with($class, 'App\\Entity')) {
        echo $class, PHP_EOL;
    }
}