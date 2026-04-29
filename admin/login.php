<?php
// Carga el archivo funciones.php, donde están funciones reutilizables
// como iniciar sesión, redireccionar, validar login y construir rutas.
require_once __DIR__ . '/funciones.php';

// Carga la conexión a la base de datos.
// Aquí se crea la variable $conexion para poder consultar MySQL.
require_once __DIR__ . '/bd.php';

// Inicia la sesión solo si todavía no ha sido iniciada.
// Esto permite usar $_SESSION sin errores.
session_start_if_needed();

// Si el usuario ya inició sesión, no tiene sentido mostrarle otra vez el login.
// Por eso se redirige directamente al panel principal.
if (is_logged_in()) {
    redirect_to(admin_url('index.php'));
}

// Variable para guardar mensajes de error en caso de que el login falle.
$mensaje = '';

// Verifica si el formulario fue enviado por método POST.
// Esto pasa cuando el usuario da clic en el botón "Ingresar".
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Captura el usuario enviado desde el formulario.
    // trim() elimina espacios al inicio y al final.
    $usuario = trim($_POST['usuario'] ?? '');

    // Captura la contraseña enviada desde el formulario.
    $password = $_POST['password'] ?? '';

    // Prepara la consulta SQL para buscar un usuario activo
    // que coincida con el nombre de usuario ingresado.
    $stmt = $conexion->prepare('SELECT * FROM tbl_usuarios WHERE usuario = :usuario AND activo = 1 LIMIT 1');

    // Ejecuta la consulta enviando el valor del usuario.
    $stmt->execute([
        ':usuario' => $usuario,
    ]);

    // Obtiene el registro encontrado en la base de datos.
    // Si no encuentra nada, esta variable quedará vacía.
    $usuarioEncontrado = $stmt->fetch();

    // Verifica dos cosas:
    // 1. Que sí exista un usuario con ese nombre.
    // 2. Que la contraseña escrita coincida con la almacenada en la BD.
    if ($usuarioEncontrado && verify_login_password($password, $usuarioEncontrado['password'])) {

        // Si el login es correcto, se guardan en sesión
        // los datos principales del usuario autenticado.
        $_SESSION['user'] = [
            'id' => $usuarioEncontrado['ID'],
            'usuario' => $usuarioEncontrado['usuario'],
            'nombre_completo' => $usuarioEncontrado['nombre_completo'],
            'rol' => $usuarioEncontrado['rol'],
        ];

        // Marca que el usuario ya quedó autenticado en el sistema.
        $_SESSION['logueado'] = true;

        // Redirige al panel principal después de iniciar sesión correctamente.
        redirect_to(admin_url('index.php'));
    }

    // Si no encontró usuario o la contraseña no coincide,
    // se guarda este mensaje para mostrarlo en pantalla.
    $mensaje = 'Usuario o contraseña incorrectos.';
}
?>

<!doctype html>
<html lang="es">

<head>
    <!-- Define el tipo de documento como HTML5 -->
    <meta charset="UTF-8">

    <!-- Hace que la página se adapte bien a celulares y pantallas pequeñas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Título que aparece en la pestaña del navegador -->
    <title>Login | Bitacora SALA_1</title>

    <!-- Conecta Bootstrap desde CDN para usar estilos rápidos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/minipanel_escolar/assets/css/style.css">


</head>

<body class="login-page">

    <!--
    Container principal del login.
    - container: crea un contenedor de Bootstrap con márgenes automáticos.
    - min-vh-100: hace que el contenedor tenga como mínimo el 100% de la altura de la ventana.
    - d-flex: activa flexbox en el contenedor.
    - justify-content-center: centra el contenido horizontalmente.
    - align-items-center: centra el contenido verticalmente.
    En conjunto, esta línea permite que la tarjeta del login quede centrada en la pantalla.
-->
    <div class="container min-vh-100 d-flex justify-content-center align-items-center">

        <!-- Tarjeta visual del formulario -->
        <div class="card login-card">

            <!-- Cuerpo interno de la tarjeta -->
            <div class="card-body p-4">

                <!-- Título principal del formulario -->
                <h2 class="fw-bold mb-3 text-center">Login bitacora sala #1</h2>

                <!-- Si existe un mensaje de error, lo muestra en una alerta roja -->
                <?php if ($mensaje): ?>
                    <div class="alert alert-danger"><?= e($mensaje) ?></div>
                <?php endif; ?>

                <!-- Formulario que envía los datos por POST -->
                <form method="post">

                    <!-- Campo para escribir el usuario -->
                    <div class="mb-3">
                        <label class="form-label">Usuario</label>
                        <input type="text" name="usuario" class="form-control" required>
                    </div>

                    <!-- Campo para escribir la contraseña -->
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <!-- Botón que envía el formulario -->
                    <button type="submit" class="btn btn-primary w-100">Ingresar</button>

                    <!-- Botón-enlace para ir al formulario de registro -->
                    <a href="<?= e(admin_url('registro.php')) ?>" class="btn class= btn btn-success w-100 mt-2">
                        Registrar usuario
                    </a>

                </form>

                <!-- Bloque de texto con credenciales de prueba -->
                <div class="mt-3 text-muted small">
                    Usuario: admin <br>
                    Contraseña: Admin123*
                </div>
            </div>
        </div>
    </div>

</body>

</html>