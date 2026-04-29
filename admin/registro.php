<?php
require_once __DIR__ . '/funciones.php';
require_once __DIR__ . '/bd.php';

$mensaje = '';
$tipo = 'danger';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_completo'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $edad = trim($_POST['edad'] ?? '');
    $password = $_POST['password'] ?? '';
    $rol = trim($_POST['rol'] ?? '');

    if ($nombre === '' || $usuario === ''|| $edad === '' || $password === '' || $rol === '') {
        $mensaje = 'Todos los campos son obligatorios.';
    } else {
        $stmt = $conexion->prepare('SELECT ID FROM tbl_usuarios WHERE usuario = :usuario LIMIT 1');
        $stmt->execute([
            ':usuario' => $usuario
        ]);

        $existe = $stmt->fetch();

        if ($existe) {
            $mensaje = 'Ese usuario ya existe. Intenta con otro.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conexion->prepare('
                INSERT INTO tbl_usuarios (nombre_completo, usuario,edad, password, rol, activo)
                VALUES (:nombre_completo, :usuario, :edad, :password, :rol, 1)
            ');

            $stmt->execute([
                ':nombre_completo' => $nombre,
                ':usuario' => $usuario,
                 ':edad' => $edad,
                ':password' => $hash,
                ':rol' => $rol
            ]);

            $mensaje = 'Usuario registrado correctamente.';
            $tipo = 'success';
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | MiniPanel Escolar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/minipanel_escolar/assets/css/style.css">
    
</head>
<body class="registro-page">
<div class="container">
    <div class="card registro-card">
        <div class="card-body p-4">
            <h2 class="fw-bold mb-3">Registrar usuario</h2>
            <p class="text-muted">Crea un nuevo usuario para ingresar al sistema.</p>

            <?php if ($mensaje): ?>
                <div class="alert alert-<?= e($tipo) ?>"><?= e($mensaje) ?></div>
            <?php endif; ?>

            <form method="post" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="nombre_completo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="usuario" class="form-control" required>
                </div>

                     <div class="mb-3">
                    <label class="form-label">Edad</label>
                    <input type="text" name="edad" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Rol</label>
                    <select name="rol" class="form-select" required>
                        <option value="">Seleccione un rol</option>
                        <option value="administrador">Administrador</option>
                        <option value="profesor">Profesor</option>
                        <option value="mediador">Mediador</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100">Guardar usuario</button>
                <a href="<?= e(admin_url('login.php')) ?>" class="btn btn-outline-secondary w-100 mt-2">Volver al login</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>