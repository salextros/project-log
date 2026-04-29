<?php
require_once __DIR__ . '/funciones.php';
require_once __DIR__ . '/bd.php';

session_start_if_needed();
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to(admin_url('bitacora.php?ok=metodo_invalido'));
}

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    redirect_to(admin_url('bitacora.php?ok=csrf'));
}

$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    redirect_to(admin_url('bitacora.php?ok=id_invalido'));
}

$stmt = $conexion->prepare('DELETE FROM tbl_bitacora_sala1 WHERE ID = :id LIMIT 1');

$stmt->execute([
    ':id' => $id,
]);

redirect_to(admin_url('bitacora.php?ok=eliminado'));
