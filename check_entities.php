<?php
require __DIR__ . '/vendor/autoload.php';

echo "🔎 Vérification des entités App\\Entity ...\n\n";

$declared = [];

// Scanner tous les fichiers de src/Entity
$dir = __DIR__ . '/src/Entity';
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());

        if (preg_match('/namespace\s+App\\\\Entity;/', $content) &&
            preg_match('/class\s+([A-Za-z0-9_]+)/', $content, $m)) {
            $class = "App\\Entity\\" . $m[1];
            $declared[$class][] = $file->getPathname();
        }
    }
}

// Affichage
foreach ($declared as $class => $files) {
    echo "➡️  $class\n";
    foreach ($files as $f) {
        echo "   - $f\n";
    }
    if (count($files) > 1) {
        echo "   ⚠️ ATTENTION : cette classe est déclarée plusieurs fois !\n";
    }
}

echo "\n✅ Vérification terminée.\n";