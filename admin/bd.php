<?php
$config = require __DIR__ . '/config.php';

try {
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $config['db']['host'],
        $config['db']['dbname'],
        $config['db']['charset']
    );

    $conexion = new PDO(
        $dsn,
        $config['db']['user'],
        $config['db']['pass'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h2>Error de conexión</h2>';
    echo '<p>Revisa config.php o la base de datos.</p>';
    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    exit;
}