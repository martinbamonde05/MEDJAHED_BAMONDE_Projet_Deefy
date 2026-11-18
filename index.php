<?php
declare(strict_types=1);

session_start();

// Autoloader PSR-4 simple (ou require_once __DIR__ . '/vendor/autoload.php'; si tu utilises Composer)
spl_autoload_register(function (string $class): void {
    $prefix = 'deefy\\classes\\';
    $baseDir = __DIR__ . '/src/classes/';
    if (str_starts_with($class, $prefix)) {
        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

use deefy\classes\Repository\DeefyRepository;
use deefy\classes\Dispatch\Dispatcher;

try {
    DeefyRepository::setConfig(__DIR__ . '/conf/config.db.ini');
    $dispatcher = new Dispatcher();
    $dispatcher->run();
} catch (\Exception $e) {
    http_response_code(500);
    echo "<h1>Erreur</h1><p>" . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
}