<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';

$mensaje = '';
$tipoMensaje = '';

$reservaId = (int)($_GET['id'] ?? $_POST['reserva_id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT r.id, r.usuario_id, r.monto_total, r.fecha_inicio, r.fecha_fin,
           re.nombre AS recurso,
           ts.nombre AS tipo_servicio
    FROM reservas r
    INNER JOIN recursos re ON r.recurso_id = re.id
    INNER JOIN tipos_servicio ts ON re.tipo_servicio_id = ts.id
    WHERE r.id = ? AND r.usuario_id = ?
");
$stmt->execute([$reservaId, $_SESSION['usuario_id']]);
$reserva = $stmt->fetch();

if (!$reserva) {
    die('Reserva no encontrada.');
}

$stmtPago = $pdo->prepare("
    SELECT id, metodo_pago, monto, estado_pago, fecha_pago, referencia
    FROM pagos
    WHERE reserva_id = ?
    ORDER BY id DESC
    LIMIT 1
");
$stmtPago->execute([$reservaId]);
$pagoExistente = $stmtPago->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodoPago = $_POST['metodo_pago'] ?? '';
    $referencia = trim($_POST['referencia'] ?? '');

    if ($pagoExistente && $pagoExistente['estado_pago'] === 'pagado') {
        $mensaje = 'Esta reserva ya fue pagada.';
        $tipoMensaje = 'error';
    } elseif (!$metodoPago) {
        $mensaje = 'Debe seleccionar un método de pago.';
        $tipoMensaje = 'error';
    } else {
        $stmtInsert = $pdo->prepare("
            INSERT INTO pagos (reserva_id, metodo_pago, monto, estado_pago, referencia)
            VALUES (?, ?, ?, 'pagado', ?)
        ");
        $stmtInsert->execute([
            $reservaId,
            $metodoPago,
            $reserva['monto_total'],
            $referencia
        ]);

        $mensaje = 'Pago realizado correctamente.';
        $tipoMensaje = 'success';

        $stmtPago->execute([$reservaId]);
        $pagoExistente = $stmtPago->fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagar reserva</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <div class="container-nav">
        <h1>Sistema de Reservas</h1>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="mis_reservas.php">Mis reservas</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="form-container">
        <h2>Pagar reserva</h2>

        <?php if (!empty($mensaje)): ?>
            <div class="alert <?php echo $tipoMensaje === 'success' ? 'alert-success' : 'alert-error'; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <div class="form-info-box">
            <p><strong>ID Reserva:</strong> <?php echo $reserva['id']; ?></p>
            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($reserva['tipo_servicio']); ?></p>
            <p><strong>Recurso:</strong> <?php echo htmlspecialchars($reserva['recurso']); ?></p>
            <p><strong>Inicio:</strong> <?php echo htmlspecialchars($reserva['fecha_inicio']); ?></p>
            <p><strong>Fin:</strong> <?php echo htmlspecialchars($reserva['fecha_fin']); ?></p>
            <p><strong>Monto total:</strong> RD$ <?php echo number_format((float)$reserva['monto_total'], 2); ?></p>
        </div>

        <?php if ($pagoExistente && $pagoExistente['estado_pago'] === 'pagado'): ?>
            <div class="alert alert-success">
                Esta reserva ya fue pagada correctamente.
            </div>

            <div class="form-info-box">
                <p><strong>Método:</strong> <?php echo htmlspecialchars($pagoExistente['metodo_pago']); ?></p>
                <p><strong>Referencia:</strong> <?php echo htmlspecialchars($pagoExistente['referencia'] ?: 'N/A'); ?></p>
                <p><strong>Fecha de pago:</strong> <?php echo htmlspecialchars($pagoExistente['fecha_pago']); ?></p>
            </div>

            <div class="btn-row">
                <a href="mis_reservas.php" class="btn btn-primary">Volver a mis reservas</a>
                <a href="dashboard.php" class="btn btn-secondary">Volver al dashboard</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <input type="hidden" name="reserva_id" value="<?php echo $reserva['id']; ?>">

                <div class="form-group">
                    <label for="metodo_pago">Método de pago</label>
                    <select name="metodo_pago" id="metodo_pago" required>
                        <option value="">Seleccione</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="referencia">Referencia</label>
                    <input type="text" name="referencia" id="referencia" placeholder="Número de referencia o detalle del pago">
                </div>

                <div class="btn-row">
                    <button type="submit" class="btn btn-primary">Pagar reserva</button>
                    <a href="mis_reservas.php" class="btn btn-secondary">Volver a mis reservas</a>
                    <a href="dashboard.php" class="btn btn-secondary">Volver al dashboard</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>     