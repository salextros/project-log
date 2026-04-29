<?php
// Carga funciones reutilizables
require_once __DIR__ . '/funciones.php';

// Carga la conexión a la base de datos
require_once __DIR__ . '/bd.php';

// Inicia sesión si aún no existe
session_start_if_needed();

// Protege la página
require_login();

// Obtiene el id del registro desde la URL
$id = (int) ($_GET['id'] ?? 0);

// Si el id no es válido, regresa
if ($id <= 0) {
    redirect_to(admin_url('bitacora.php'));
}

// Busca el registro actual en la base de datos
$stmt = $conexion->prepare('SELECT * FROM tbl_bitacora_sala1 WHERE ID = :id LIMIT 1');
$stmt->execute([
    ':id' => $id,
]);

$registro = $stmt->fetch();

// Si no existe el registro, regresa
if (!$registro) {
    redirect_to(admin_url('bitacora.php'));
}

$mensaje = '';
$tipo = 'danger';

// Si el formulario se envía por POST, actualiza el registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    if (
        $nombre_responsable === '' || $fecha === '' || $hora_entrada === '' || $hora_salida === '' ||
        $curso === '' || $actividad_realizar === '' || $sala_organizada === '' ||
        $luces_apagadas === '' || $computadores_apagados === '' || $sin_problemas === '' ||
        $sala_cerrada === '' || $aa_apagado === ''
    ) {
        $mensaje = 'Todos los campos obligatorios deben estar completos.';
    } else {
        $stmtUpdate = $conexion->prepare("
            UPDATE tbl_bitacora_sala1 SET
                nombre_responsable = :nombre_responsable,
                fecha = :fecha,
                hora_entrada = :hora_entrada,
                hora_salida = :hora_salida,
                curso = :curso,
                actividad_realizar = :actividad_realizar,
                sala_organizada = :sala_organizada,
                luces_apagadas = :luces_apagadas,
                computadores_apagados = :computadores_apagados,
                sin_problemas = :sin_problemas,
                sala_cerrada = :sala_cerrada,
                aa_apagado = :aa_apagado,
                observaciones = :observaciones
            WHERE ID = :id
        ");

        $stmtUpdate->execute([
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
            ':id' => $id,
        ]);

        redirect_to(admin_url('bitacora.php?ok=editado'));
    }

    // Si hubo error de validación, mantenemos los datos escritos
    $registro = [
        'ID' => $id,
        'nombre_responsable' => $nombre_responsable,
        'fecha' => $fecha,
        'hora_entrada' => $hora_entrada,
        'hora_salida' => $hora_salida,
        'curso' => $curso,
        'actividad_realizar' => $actividad_realizar,
        'sala_organizada' => $sala_organizada,
        'luces_apagadas' => $luces_apagadas,
        'computadores_apagados' => $computadores_apagados,
        'sin_problemas' => $sin_problemas,
        'sala_cerrada' => $sala_cerrada,
        'aa_apagado' => $aa_apagado,
        'observaciones' => $observaciones,
    ];
}
?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

    <div class="card bitacora-card">
        <div class="card-body p-4 p-md-5">
            <h1 class="fw-bold mb-3 text-center">Editar Bitácora Sala #1</h1>
            <p class="text-muted text-center mb-4">
                Modifica la información del registro seleccionado.
            </p>

            <?php if ($mensaje): ?>
                <div class="alert alert-<?= e($tipo) ?>"><?= e($mensaje) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Nombre del responsable</label>
                        <input type="text" name="nombre_responsable" class="form-control" value="<?= e($registro['nombre_responsable']) ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="<?= e($registro['fecha']) ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Curso</label>
                        <input type="text" name="curso" class="form-control" value="<?= e($registro['curso']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Hora de entrada</label>
                        <input type="time" name="hora_entrada" class="form-control" value="<?= e($registro['hora_entrada']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Hora de salida</label>
                        <input type="time" name="hora_salida" class="form-control" value="<?= e($registro['hora_salida']) ?>" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Actividad a realizar</label>
                        <input type="text" name="actividad_realizar" class="form-control" value="<?= e($registro['actividad_realizar']) ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sala organizada</label>
                        <select name="sala_organizada" class="form-select" required>
                            <option value="si" <?= $registro['sala_organizada'] === 'si' ? 'selected' : '' ?>>Sí</option>
                            <option value="no" <?= $registro['sala_organizada'] === 'no' ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Luces apagadas</label>
                        <select name="luces_apagadas" class="form-select" required>
                            <option value="si" <?= $registro['luces_apagadas'] === 'si' ? 'selected' : '' ?>>Sí</option>
                            <option value="no" <?= $registro['luces_apagadas'] === 'no' ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Computadores apagados</label>
                        <select name="computadores_apagados" class="form-select" required>
                            <option value="si" <?= $registro['computadores_apagados'] === 'si' ? 'selected' : '' ?>>Sí</option>
                            <option value="no" <?= $registro['computadores_apagados'] === 'no' ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sin problemas detectados</label>
                        <select name="sin_problemas" class="form-select" required>
                            <option value="si" <?= $registro['sin_problemas'] === 'si' ? 'selected' : '' ?>>Sí</option>
                            <option value="no" <?= $registro['sin_problemas'] === 'no' ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sala cerrada</label>
                        <select name="sala_cerrada" class="form-select" required>
                            <option value="si" <?= $registro['sala_cerrada'] === 'si' ? 'selected' : '' ?>>Sí</option>
                            <option value="no" <?= $registro['sala_cerrada'] === 'no' ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">A/A apagado</label>
                        <select name="aa_apagado" class="form-select" required>
                            <option value="si" <?= $registro['aa_apagado'] === 'si' ? 'selected' : '' ?>>Sí</option>
                            <option value="no" <?= $registro['aa_apagado'] === 'no' ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Observaciones o novedades</label>
                        <textarea name="observaciones" class="form-control" rows="4"><?= e($registro['observaciones']) ?></textarea>
                    </div>

                    <div class="col-12 d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                        <a href="<?= e(admin_url('bitacora.php')) ?>" class="btn btn-outline-secondary">
                            Volver
                        </a>
                        <button type="submit" class="btn btn-warning">
                            Actualizar registro
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
<?php require_once __DIR__ . '/templates/footer.php'; ?>