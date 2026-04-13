<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$sql = "
    SELECT r.*, ts.nombre AS tipo_servicio
    FROM recursos r
    INNER JOIN tipos_servicio ts ON r.tipo_servicio_id = ts.id
    ORDER BY r.id DESC
";
$recursos = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar recursos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="navbar">
    <div class="container-nav">
        <h1>Administrar recursos</h1>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="nuevo_recurso.php">Nuevo recurso</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <h2 class="page-title">Recursos del sistema</h2>
    <div class="table-container">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Nombre</th>
                    <th>Ubicación</th>
                    <th>Capacidad</th>
                    <th>Precio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recursos as $r): ?>
                    <tr>
                        <td><?php echo $r['id']; ?></td>
                        <td><?php echo htmlspecialchars($r['tipo_servicio']); ?></td>
                        <td><?php echo htmlspecialchars($r['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($r['ubicacion']); ?></td>
                        <td><?php echo (int)$r['capacidad']; ?></td>
                        <td>RD$ <?php echo number_format((float)$r['precio'], 2); ?></td>
                        <td><?php echo htmlspecialchars($r['estado']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>