<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$sql = "
    SELECT u.id, u.nombre, u.apellido, u.correo, u.telefono, u.estado, r.nombre AS rol
    FROM usuarios u
    INNER JOIN roles r ON u.rol_id = r.id
    ORDER BY u.id DESC
";
$usuarios = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de usuarios</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="navbar">
    <div class="container-nav">
        <h1>Gestión de usuarios</h1>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </div>
</div>

<div class="container">
    <h2 class="page-title">Usuarios registrados</h2>

    <div class="table-container">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?php echo $u['id']; ?></td>
                        <td><?php echo htmlspecialchars($u['nombre'] . ' ' . $u['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($u['correo']); ?></td>
                        <td><?php echo htmlspecialchars($u['telefono'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($u['rol']); ?></td>
                        <td><?php echo htmlspecialchars($u['estado']); ?></td>
                        <td>
                            <a class="btn btn-secondary" href="toggle_usuario.php?id=<?php echo $u['id']; ?>">
                                Cambiar estado
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