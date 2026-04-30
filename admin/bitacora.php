<?php
// Carga funciones reutilizables del sistema
require_once __DIR__ . '/funciones.php';

// Carga la conexión a la base de datos
require_once __DIR__ . '/bd.php';

// Inicia la sesión si aún no existe
session_start_if_needed();

// Protege la página: solo entra quien haya iniciado sesión
require_login();

// Obtiene los datos del usuario autenticado
$user = current_user();

// Variable para autollenar el nombre del responsable
$nombreResponsable = $user['nombre_completo'] ?? '';

// Variables para mensajes

$mensaje = '';
$tipo = 'success';

// Mensajes cuando se regresa desde editar o eliminar
// Mensajes cuando se regresa desde crear, editar o eliminar
$ok = $_GET['ok'] ?? '';

if ($ok === 'creado') {
    $mensaje = 'La bitácora fue guardada correctamente.';
    $tipo = 'success';
} elseif ($ok === 'eliminado') {
    $mensaje = 'El registro fue eliminado correctamente.';
    $tipo = 'warning';
} elseif ($ok === 'editado') {
    $mensaje = 'El registro fue actualizado correctamente.';
    $tipo = 'success';
} elseif ($ok === 'metodo_invalido') {
    $mensaje = 'Método no permitido. La eliminación debe hacerse desde el botón del sistema.';
    $tipo = 'danger';
} elseif ($ok === 'csrf') {
    $mensaje = 'Solicitud no válida. Token de seguridad incorrecto.';
    $tipo = 'danger';
} elseif ($ok === 'id_invalido') {
    $mensaje = 'No se recibió un ID válido para eliminar.';
    $tipo = 'danger';
}

// Procesa el formulario cuando se envía por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura y limpia los datos enviados
    $nombre_responsable = trim($_POST['nombre_responsable'] ?? '');
    $fecha = $_POST['fecha'] ?? '';
    $hora_entrada = $_POST['hora_entrada'] ?? '';
    $hora_salida = $_POST['hora_salida'] ?? '';
    $curso = trim($_POST['curso'] ?? '');
    $actividad_realizar = trim($_POST['actividad_realizar'] ?? '');

    $sala_organizada = $_POST['sala_organizada'] ?? '';
    $luces_apagadas = $_POST['luces_apagadas'] ?? '';
    $computadores_apagados = $_POST['computadores_apagados'] ?? '';
    $sin_problemas = $_POST['sin_problemas'] ?? '';
    $sala_cerrada = $_POST['sala_cerrada'] ?? '';
    $aa_apagado = $_POST['aa_apagado'] ?? '';

    $observaciones = trim($_POST['observaciones'] ?? '');

    // Validación básica: revisa que los campos obligatorios no estén vacíos
    if (
        $nombre_responsable === '' || $fecha === '' || $hora_entrada === '' || $hora_salida === '' ||
        $curso === '' || $actividad_realizar === '' || $sala_organizada === '' ||
        $luces_apagadas === '' || $computadores_apagados === '' || $sin_problemas === '' ||
        $sala_cerrada === '' || $aa_apagado === ''
    ) {
        $mensaje = 'Todos los campos obligatorios deben ser completados.';
        $tipo = 'danger';
    } else {
        // Prepara el INSERT para guardar la bitácora
        $stmt = $conexion->prepare("
            INSERT INTO tbl_bitacora_sala1 (
                nombre_responsable,
                fecha,
                hora_entrada,
                hora_salida,
                curso,
                actividad_realizar,
                sala_organizada,
                luces_apagadas,
                computadores_apagados,
                sin_problemas,
                sala_cerrada,
                aa_apagado,
                observaciones,
                creado_por
            ) VALUES (
                :nombre_responsable,
                :fecha,
                :hora_entrada,
                :hora_salida,
                :curso,
                :actividad_realizar,
                :sala_organizada,
                :luces_apagadas,
                :computadores_apagados,
                :sin_problemas,
                :sala_cerrada,
                :aa_apagado,
                :observaciones,
                :creado_por
            )
        ");

        // Ejecuta la consulta enviando los valores
        $stmt->execute([
            ':nombre_responsable' => $nombre_responsable,
            ':fecha' => $fecha,
            ':hora_entrada' => $hora_entrada,
            ':hora_salida' => $hora_salida,
            ':curso' => $curso,
            ':actividad_realizar' => $actividad_realizar,
            ':sala_organizada' => $sala_organizada,
            ':luces_apagadas' => $luces_apagadas,
            ':computadores_apagados' => $computadores_apagados,
            ':sin_problemas' => $sin_problemas,
            ':sala_cerrada' => $sala_cerrada,
            ':aa_apagado' => $aa_apagado,
            ':observaciones' => $observaciones,
            ':creado_por' => $user['id'] ?? null,
        ]);

        // Redirige después de guardar para evitar duplicados al actualizar
        redirect_to(admin_url('bitacora.php?ok=creado'));
        exit;
    }
} else {
    // Valores iniciales cuando apenas se abre la página
    $fecha = '';
    $hora_entrada = '';
    $hora_salida = '';
    $curso = '';
    $actividad_realizar = '';
    $sala_organizada = '';
    $luces_apagadas = '';
    $computadores_apagados = '';
    $sin_problemas = '';
    $sala_cerrada = '';
    $aa_apagado = '';
    $observaciones = '';
}

// Consulta los registros guardados en la bitácora
$consultaRegistros = $conexion->query("
    SELECT 
        b.*,
        u.nombre_completo AS usuario_creador
    FROM tbl_bitacora_sala1 b
    LEFT JOIN tbl_usuarios u ON b.creado_por = u.ID
    ORDER BY b.ID DESC
");

// Guarda todos los registros en un arreglo
$registrosBitacora = $consultaRegistros->fetchAll();

?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<div class="card bitacora-card">
    <div class="card-body p-4 p-md-5">
        <h1 class="fw-bold mb-3 text-center">Bitácora Sala #1</h1>
        <p class="text-muted text-center mb-4">
            Registra la información de uso y estado de la sala.
        </p>

        <?php if ($mensaje): ?>
            <div class="alert alert-<?= e($tipo) ?>"><?= e($mensaje) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre del responsable</label>
                    <input
                        type="text"
                        name="nombre_responsable"
                        class="form-control"
                        value="<?= e($nombre_responsable ?? $nombreResponsable) ?>"
                        required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-control" value="<?= e($fecha) ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Curso</label>
                    <input type="text" name="curso" class="form-control" value="<?= e($curso) ?>" placeholder="Ej: 10-1" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Hora de entrada</label>
                    <input type="time" name="hora_entrada" class="form-control" value="<?= e($hora_entrada) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Hora de salida</label>
                    <input type="time" name="hora_salida" class="form-control" value="<?= e($hora_salida) ?>" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Actividad a realizar</label>
                    <input
                        type="text"
                        name="actividad_realizar"
                        class="form-control"
                        value="<?= e($actividad_realizar) ?>"
                        placeholder="Ej: Clase de programación, investigación, taller práctico..."
                        required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sala organizada</label>
                    <select name="sala_organizada" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="si" <?= $sala_organizada === 'si' ? 'selected' : '' ?>>Sí</option>
                        <option value="no" <?= $sala_organizada === 'no' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Luces apagadas</label>
                    <select name="luces_apagadas" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="si" <?= $luces_apagadas === 'si' ? 'selected' : '' ?>>Sí</option>
                        <option value="no" <?= $luces_apagadas === 'no' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Computadores apagados</label>
                    <select name="computadores_apagados" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="si" <?= $computadores_apagados === 'si' ? 'selected' : '' ?>>Sí</option>
                        <option value="no" <?= $computadores_apagados === 'no' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sin problemas detectados</label>
                    <select name="sin_problemas" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="si" <?= $sin_problemas === 'si' ? 'selected' : '' ?>>Sí</option>
                        <option value="no" <?= $sin_problemas === 'no' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sala cerrada</label>
                    <select name="sala_cerrada" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="si" <?= $sala_cerrada === 'si' ? 'selected' : '' ?>>Sí</option>
                        <option value="no" <?= $sala_cerrada === 'no' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">A/A apagado</label>
                    <select name="aa_apagado" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="si" <?= $aa_apagado === 'si' ? 'selected' : '' ?>>Sí</option>
                        <option value="no" <?= $aa_apagado === 'no' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Observaciones o novedades</label>
                    <textarea
                        name="observaciones"
                        class="form-control"
                        rows="4"
                        placeholder="Escribe aquí cualquier novedad encontrada en la sala..."><?= e($observaciones) ?></textarea>
                </div>

                <div class="col-12 d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                    <a href="<?= e(admin_url('index.php')) ?>" class="btn btn-outline-secondary">
                        Volver al panel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Guardar bitácora
                    </button>
                </div>

            </div>
        </form>

        <hr class="my-5">

        <h2 class="fw-bold mb-3">Registros guardados</h2>
        <p class="text-muted mb-4">Aquí puedes ver las bitácoras registradas en la sala.</p>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="tablaBitacora">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Responsable</th>
                        <th>Fecha</th>
                        <th>Curso</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Sin problemas</th>
                        <th>Sala cerrada</th>
                        <th>Creado por</th>

                        <th>Actividad a realizar</th>
                        <th>Sala organizada</th>
                        <th>Luces apagadas</th>
                        <th>Computadores apagados</th>
                        <th>Aire apagado</th>
                        <th>Observaciones</th>

                        <th class="no-export">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($registrosBitacora): ?>
                        <?php foreach ($registrosBitacora as $registro): ?>
                            <tr>
                                <td><?= e($registro['ID']) ?></td>
                                <td><?= e($registro['nombre_responsable']) ?></td>
                                <td><?= e($registro['fecha']) ?></td>
                                <td><?= e($registro['curso']) ?></td>
                                <td><?= e($registro['hora_entrada']) ?></td>
                                <td><?= e($registro['hora_salida']) ?></td>
                                <td><?= e($registro['sin_problemas']) ?></td>
                                <td><?= e($registro['sala_cerrada']) ?></td>
                                <td><?= e($registro['usuario_creador'] ?? 'Sin dato') ?></td>

                                <td><?= e($registro['actividad_realizar']) ?></td>
                                <td><?= e($registro['sala_organizada']) ?></td>
                                <td><?= e($registro['luces_apagadas']) ?></td>
                                <td><?= e($registro['computadores_apagados']) ?></td>
                                <td><?= e($registro['aa_apagado']) ?></td>
                                <td><?= e($registro['observaciones']) ?></td>
                                <td class="no-export d-flex gap-2">
                                    
                                    <a href="<?= e(admin_url('editar_bitacora.php?id=' . $registro['ID'])) ?>" class="btn btn-warning btn-sm">
                                        Editar
                                    </a>
                                    <!-- eliminar por POST -->
                                    <form
                                        method="post"
                                        action="<?= e(admin_url('eliminar_bitacora.php')) ?>"
                                        class="m-0"
                                        onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= e($registro['ID']) ?>">

                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?= e(csrf_token()) ?>">

                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                No hay registros guardados todavía.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


    </div>
</div>
<?php require_once __DIR__ . '/templates/footer.php'; ?>