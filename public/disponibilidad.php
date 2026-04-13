<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';

$recursos = $pdo->query("SELECT id, nombre FROM recursos WHERE estado = 'disponible' ORDER BY nombre")->fetchAll();

$recursoId = (int)($_GET['recurso_id'] ?? 0);
$fecha = $_GET['fecha'] ?? date('Y-m-d');
$reservas = [];

if ($recursoId > 0) {
    $stmt = $pdo->prepare("
        SELECT fecha_inicio, fecha_fin
        FROM reservas
        WHERE recurso_id = ?
          AND DATE(fecha_inicio) = ?
          AND estado_reserva_id IN (1,2,4)
        ORDER BY fecha_inicio ASC
    ");
    $stmt->execute([$recursoId, $fecha]);
    $reservas = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Disponibilidad</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="navbar">
    <div class="container-nav">
        <h1>Disponibilidad</h1>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="recursos.php">Recursos</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="form-container">
        <h2>Consultar disponibilidad</h2>
        <form method="GET">
            <div class="form-group">
                <label>Recurso</label>
                <select name="recurso_id" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($recursos as $r): ?>
                        <option value="<?php echo $r['id']; ?>" <?php echo $recursoId === (int)$r['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($r['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Fecha</label>
                <input type="date" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>" required>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn btn-primary">Consultar</button>
            </div>
        </form>
    </div>

    <?php if ($recursoId > 0): ?>
        <div class="table-container">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Hora inicio</th>
                        <th>Hora fin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($reservas): ?>
                        <?php foreach ($reservas as $res): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($res['fecha_inicio']); ?></td>
                                <td><?php echo htmlspecialchars($res['fecha_fin']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">No hay reservas para esa fecha.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>