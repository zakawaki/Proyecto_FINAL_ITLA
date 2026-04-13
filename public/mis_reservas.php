<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';

$sql = "
    SELECT res.id, res.fecha_inicio, res.fecha_fin, res.monto_total, res.fecha_registro,
           r.nombre AS recurso, ts.nombre AS tipo_servicio, er.nombre AS estado
    FROM reservas res
    INNER JOIN recursos r ON res.recurso_id = r.id
    INNER JOIN tipos_servicio ts ON r.tipo_servicio_id = ts.id
    INNER JOIN estados_reserva er ON res.estado_reserva_id = er.id
    WHERE res.usuario_id = ?
    ORDER BY res.fecha_inicio DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['usuario_id']]);
$reservas = $stmt->fetchAll();

$ahora = date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis reservas</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <div class="container-nav">
        <h1>Sistema de Reservas</h1>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="recursos.php">Recursos</a></li>
            <li><a href="mis_pagos.php">Mis pagos</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <h2 class="page-title">Mis reservas</h2>

    <div class="table-container">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Recurso</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Pago</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $reserva): ?>
                    <?php
                    $claseEstado = 'badge-pendiente';
                    $textoEstado = $reserva['estado'];

                    if ($reserva['estado'] === 'confirmada') {
                        if ($ahora >= $reserva['fecha_inicio'] && $ahora <= $reserva['fecha_fin']) {
                            $claseEstado = 'badge-curso';
                            $textoEstado = 'En curso';
                        } else {
                            $claseEstado = 'badge-confirmada';
                        }
                    } elseif ($reserva['estado'] === 'cancelada') {
                        $claseEstado = 'badge-cancelada';
                    } elseif ($reserva['estado'] === 'completada') {
                        $claseEstado = 'badge-completada';
                    }
                    ?>
                    <tr>
                        <td><?php echo $reserva['id']; ?></td>
                        <td><?php echo htmlspecialchars($reserva['tipo_servicio']); ?></td>
                        <td><?php echo htmlspecialchars($reserva['recurso']); ?></td>
                        <td><?php echo htmlspecialchars($reserva['fecha_inicio']); ?></td>
                        <td><?php echo htmlspecialchars($reserva['fecha_fin']); ?></td>
                        <td>RD$ <?php echo number_format((float)$reserva['monto_total'], 2); ?></td>
                        <td>
                            <span class="badge <?php echo $claseEstado; ?>">
                                <?php echo htmlspecialchars($textoEstado); ?>
                            </span>
                        </td>
                        <td>
                            <a class="btn btn-primary" href="pagar_reserva.php?id=<?php echo $reserva['id']; ?>">
                                Pagar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($reservas)): ?>
                    <tr>
                        <td colspan="8">No tienes reservas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>