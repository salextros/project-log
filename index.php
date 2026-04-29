<?php
require_once __DIR__ . '/admin/funciones.php';
require_once __DIR__ . '/admin/bd.php';

$totalUsuarios = 0;

try {
    $totalUsuarios = (int) $conexion->query('SELECT COUNT(*) FROM tbl_usuarios')->fetchColumn();
} catch (Throwable $e) {
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiniPanel Escolar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

</head>

<body class="home-page">
    <div class="container py-5">
        <div class="hero">
            <h1 class="fw-bold mb-3">MiniPanel Escolar</h1>
            <p class="lead">
                Proyecto de práctica para reforzar estructura de carpetas, conexión a base de datos,
                login, sesiones y panel administrativo.
            </p>

            <a href="<?= e(admin_url('login.php')) ?>" class="btn btn-primary btn-lg">Ingresar</a>

            <div class="mt-4">
                <strong>Total de usuarios:</strong> <?= e($totalUsuarios) ?>
            </div>
        </div>
    </div>
</body>

</html>