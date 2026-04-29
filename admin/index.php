<?php
require_once __DIR__ . '/templates/header.php';
?>

<div class="card content-card">
    <div class="card-body p-4">
        <h1 class="fw-bold">Bienvenido a la bitácora sala #1</h1>
        <p>Hola, <?= e($user['nombre_completo'] ?? '') ?>.</p>
        <p>Rol: <?= e($user['rol'] ?? '') ?></p>

        <a href="<?= e(admin_url('bitacora.php')) ?>" class="btn btn-primary me-2">Ir a Bitácora Sala #1</a>
        <a href="<?= e(admin_url('cerrar.php')) ?>" class="btn btn-danger">Cerrar sesión</a>
    </div>
</div>

<?php
require_once __DIR__ . '/templates/footer.php';
?>
boos