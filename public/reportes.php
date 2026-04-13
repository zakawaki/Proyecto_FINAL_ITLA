<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$totalUsuarios = $pdo->query("SELECT COUNT(*) AS total FROM usuarios")->fetch()['total'];
$totalRecursos = $pdo->query("SELECT COUNT(*) AS total FROM recursos")->fetch()['total'];
$totalReservas = $pdo->query("SELECT COUNT(*) AS total FROM reservas")->fetch()['total'];
$totalIngresos = $pdo->query("
    SELECT IFNULL(SUM(monto_total),0) AS total
    FROM reservas
    WHERE estado_reserva_id IN (2,4)
")->fetch()['total'];

$reservasPorTipo = $pdo->query("
    SELECT ts.nombre AS tipo, COUNT(res.id) AS total
    FROM reservas res
    INNER JOIN recursos r ON res.recurso_id = r.id
    INNER JOIN tipos_servicio ts ON r.tipo_servicio_id = ts.id
    GROUP BY ts.nombre
")->fetchAll();

$recursosMasUsados = $pdo->query("
    SELECT r.nombre, COUNT(res.id) AS total
    FROM reservas res
    INNER JOIN recursos r ON res.recurso_id = r.id
    GROUP BY r.id, r.nombre
    ORDER BY total DESC
    LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes administrativos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="navbar">
    <div class="container-nav">
        <h1>Reportes administrativos</h1>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="dashboard-grid">
        <div class="dashboard-box">
            <h3>Total usuarios</h3>
            <p><?php echo $totalUsuarios; ?></p>
        </div>
        <div class="dashboard-box">
            <h3>Total recursos</h3>
            <p><?php echo $totalRecursos; ?></p>
        </div>
        <div class="dashboard-box">
            <h3>Total reservas</h3>
            <p><?php echo $totalReservas; ?></p>
        </div>
        <div class="dashboard-box">
            <h3>Ingresos</h3>
            <p>RD$ <?php echo number_format((float)$totalIngresos, 2); ?></p>
        </div>
    </div>

    <h2 class="page-title" style="margin-top:30px;">Reservas por tipo</h2>
    <div class="table-container">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservasPorTipo as $fila): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fila['tipo']); ?></td>
                        <td><?php echo $fila['total']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h2 class="page-title" style="margin-top:30px;">Recursos más usados</h2>
    <div class="table-container">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Recurso</th>
                    <th>Total reservas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recursosMasUsados as $fila): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                        <td><?php echo $fila['total']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>