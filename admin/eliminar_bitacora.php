<?php
// Carga funciones reutilizables
require_once __DIR__ . '/funciones.php';

// Carga conexión a base de datos
require_once __DIR__ . '/bd.php';

// Inicia sesión si no existe
session_start_if_needed();

// Protege la página
require_login();

// Toma el id enviado por la URL
$id = (int) ($_GET['id'] ?? 0);

// Si no hay id válido, vuelve a bitácora
if ($id <= 0) {
    redirect_to(admin_url('bitacora.php'));
}

// Prepara el DELETE
$stmt = $conexion->prepare('DELETE FROM tbl_bitacora_sala1 WHERE ID = :id');

// Ejecuta la eliminación
$stmt->execute([
    ':id' => $id,
]);

// Redirige de nuevo a la bitácora con mensaje
redirect_to(admin_url('bitacora.php?ok=eliminado'));