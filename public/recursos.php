<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';

$sql = "
    SELECT r.id, r.nombre, r.descripcion, r.ubicacion, r.capacidad, r.precio, r.estado,
           ts.nombre AS tipo_servicio
    FROM recursos r
    INNER JOIN tipos_servicio ts ON r.tipo_servicio_id = ts.id
    WHERE r.estado = 'disponible'
    ORDER BY ts.nombre, r.nombre
";

$recursos = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recursos disponibles</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <div class="container-nav">
        <h1>Sistema de Reservas</h1>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="mis_reservas.php">Mis reservas</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <h2 class="page-title">Recursos disponibles</h2>

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
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recursos as $recurso): ?>
                    <tr>
                        <td><?php echo $recurso['id']; ?></td>
                        <td><?php echo htmlspecialchars($recurso['tipo_servicio']); ?></td>
                        <td><?php echo htmlspecialchars($recurso['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($recurso['ubicacion']); ?></td>
                        <td><?php echo (int)$recurso['capacidad']; ?></td>
                        <td>RD$ <?php echo number_format((float)$recurso['precio'], 2); ?></td>
                        <td>
                            <a class="btn btn-primary" href="reservar.php?recurso_id=<?php echo $recurso['id']; ?>">
                                Reservar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>