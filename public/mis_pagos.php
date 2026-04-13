<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';

$stmt = $pdo->prepare("
    SELECT p.id, p.metodo_pago, p.monto, p.estado_pago, p.fecha_pago, p.referencia,
           r.id AS reserva_id,
           re.nombre AS recurso
    FROM pagos p
    INNER JOIN reservas r ON p.reserva_id = r.id
    INNER JOIN recursos re ON r.recurso_id = re.id
    WHERE r.usuario_id = ?
    ORDER BY p.fecha_pago DESC
");
$stmt->execute([$_SESSION['usuario_id']]);
$pagos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis pagos</title>
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
    <h2 class="page-title">Mis pagos</h2>

    <div class="table-container">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>ID Pago</th>
                    <th>ID Reserva</th>
                    <th>Recurso</th>
                    <th>Método</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Referencia</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pagos as $pago): ?>
                    <tr>
                        <td><?php echo $pago['id']; ?></td>
                        <td><?php echo $pago['reserva_id']; ?></td>
                        <td><?php echo htmlspecialchars($pago['recurso']); ?></td>
                        <td><?php echo htmlspecialchars($pago['metodo_pago']); ?></td>
                        <td>RD$ <?php echo number_format((float)$pago['monto'], 2); ?></td>
                        <td><?php echo htmlspecialchars($pago['estado_pago']); ?></td>
                        <td><?php echo htmlspecialchars($pago['referencia'] ?: 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($pago['fecha_pago']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>