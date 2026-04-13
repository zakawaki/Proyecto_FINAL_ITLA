<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funciones_reservas.php';

$mensaje = '';
$tipoMensaje = '';
$recursoId = isset($_GET['recurso_id']) ? (int)$_GET['recurso_id'] : (int)($_POST['recurso_id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT r.*, ts.nombre AS tipo_servicio
    FROM recursos r
    INNER JOIN tipos_servicio ts ON r.tipo_servicio_id = ts.id
    WHERE r.id = ?
");
$stmt->execute([$recursoId]);
$recurso = $stmt->fetch();

if (!$recurso) {
    die('Recurso no encontrado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fechaInicio = $_POST['fecha_inicio'] ?? '';
    $fechaFin = $_POST['fecha_fin'] ?? '';
    $cantidadPersonas = (int)($_POST['cantidad_personas'] ?? 1);
    $observaciones = trim($_POST['observaciones'] ?? '');

    if (!$fechaInicio || !$fechaFin) {
        $mensaje = 'Debe indicar fecha y hora de inicio y fin.';
        $tipoMensaje = 'error';
    } elseif ($fechaFin <= $fechaInicio) {
        $mensaje = 'La fecha final debe ser mayor que la inicial.';
        $tipoMensaje = 'error';
    } elseif (!recursoDisponible($pdo, $recursoId, $fechaInicio, $fechaFin)) {
        $mensaje = 'El recurso ya está reservado en ese horario.';
        $tipoMensaje = 'error';
    } else {
        $montoTotal = calcularMontoTotal($pdo, $recursoId, $fechaInicio, $fechaFin);

        $sql = "
            INSERT INTO reservas (
                usuario_id, recurso_id, estado_reserva_id,
                fecha_inicio, fecha_fin, cantidad_personas,
                monto_total, observaciones
            ) VALUES (?, ?, 2, ?, ?, ?, ?, ?)
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_SESSION['usuario_id'],
            $recursoId,
            $fechaInicio,
            $fechaFin,
            $cantidadPersonas,
            $montoTotal,
            $observaciones
        ]);

        $mensaje = 'Reserva realizada correctamente.';
        $tipoMensaje = 'success';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar recurso</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <div class="container-nav">
        <h1>Sistema de Reservas</h1>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="recursos.php">Recursos</a></li>
            <li><a href="mis_reservas.php">Mis reservas</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="form-container">
        <h2>Reservar recurso</h2>

        <?php if (!empty($mensaje)): ?>
            <div class="alert <?php echo $tipoMensaje === 'success' ? 'alert-success' : 'alert-error'; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <div class="form-info-box">
            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($recurso['tipo_servicio']); ?></p>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($recurso['nombre']); ?></p>
            <p><strong>Precio:</strong> RD$ <?php echo number_format((float)$recurso['precio'], 2); ?></p>
        </div>

        <form method="POST">
            <input type="hidden" name="recurso_id" value="<?php echo $recursoId; ?>">

            <div class="form-group">
                <label for="fecha_inicio">Fecha y hora de inicio</label>
                <input type="datetime-local" name="fecha_inicio" id="fecha_inicio" required>
            </div>

            <div class="form-group">
                <label for="fecha_fin">Fecha y hora de fin</label>
                <input type="datetime-local" name="fecha_fin" id="fecha_fin" required>
            </div>

            <div class="form-group">
                <label for="cantidad_personas">Cantidad de personas</label>
                <input type="number" name="cantidad_personas" id="cantidad_personas" min="1" value="1">
            </div>

            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea name="observaciones" id="observaciones"></textarea>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn btn-primary">Confirmar reserva</button>
                <a href="dashboard.php" class="btn btn-secondary">Volver al dashboard</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>