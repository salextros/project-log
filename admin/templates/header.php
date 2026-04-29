<?php
require_once __DIR__ . '/../funciones.php';

session_start_if_needed();
require_login();

$user = current_user();
$currentPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel | MiniPanel Escolar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/minipanel_escolar/assets/css/style.css">



</head>

<body class="admin-header-page">

    <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= e(admin_url('index.php')) ?>">MiniPanel Escolar</a>
            <div class="d-flex align-items-center gap-3">
                <span class="small text-muted">
                    <?= e($user['nombre_completo'] ?? '') ?> · <?= e(ucfirst($user['rol'] ?? '')) ?>
                </span>
                <a class="btn btn-outline-danger btn-sm" href="<?= e(admin_url('cerrar.php')) ?>">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <aside class="col-lg-2 sidebar p-3">
                <div class="text-white mb-4 mt-2">
                    <div class="fw-bold fs-5">Panel principal</div>
                    <div class="small opacity-75">Gestión del sistema</div>
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link <?= str_contains($currentPath, '/admin/index.php') ? 'active' : '' ?>" href="<?= e(admin_url('index.php')) ?>">
                        Inicio
                    </a>

                    <a class="nav-link <?= str_contains($currentPath, '/admin/bitacora.php') ? 'active' : '' ?>" href="<?= e(admin_url('bitacora.php')) ?>">
                        Bitácora Sala #1
                    </a>

                    <a class="nav-link" href="<?= e(public_url('index.php')) ?>">
                        Ver sitio
                    </a>
                </nav>
            </aside>

            <main class="col-lg-10 p-4">