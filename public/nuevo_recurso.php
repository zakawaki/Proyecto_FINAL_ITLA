<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$mensaje = '';
$tipoMensaje = '';

$tipos = $pdo->query("SELECT id, nombre FROM tipos_servicio ORDER BY nombre")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = (int)($_POST['tipo_servicio_id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $ubicacion = trim($_POST['ubicacion'] ?? '');
    $capacidad = (int)($_POST['capacidad'] ?? 1);
    $precio = (float)($_POST['precio'] ?? 0);
    $estado = $_POST['estado'] ?? 'disponible';

    if ($tipo && $nombre && $ubicacion) {
        $stmt = $pdo->prepare("
            INSERT INTO recursos (tipo_servicio_id, nombre, descripcion, ubicacion, capacidad, precio, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$tipo, $nombre, $descripcion, $ubicacion, $capacidad, $precio, $estado]);

        $mensaje = 'Recurso creado correctamente.';
        $tipoMensaje = 'success';
    } else {
        $mensaje = 'Complete los campos obligatorios.';
        $tipoMensaje = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo recurso</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="navbar">
    <div class="container-nav">
        <h1>Nuevo recurso</h1>
        <ul>
            <li><a href="admin_recursos.php">Volver</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <div class="form-container">
        <h2>Registrar recurso</h2>

        <?php if ($mensaje): ?>
            <div class="alert <?php echo $tipoMensaje === 'success' ? 'alert-success' : 'alert-error'; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Tipo de servicio</label>
                <select name="tipo_servicio_id" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion"></textarea>
            </div>

            <div class="form-group">
                <label>Ubicación</label>
                <input type="text" name="ubicacion" required>
            </div>

            <div class="form-group">
                <label>Capacidad</label>
                <input type="number" name="capacidad" min="1" value="1">
            </div>

            <div class="form-group">
                <label>Precio</label>
                <input type="number" step="0.01" name="precio" min="0" value="0">
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado">
                    <option value="disponible">Disponible</option>
                    <option value="mantenimiento">Mantenimiento</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="admin_recursos.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>