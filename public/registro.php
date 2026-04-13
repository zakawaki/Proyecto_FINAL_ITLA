<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';

$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($nombre && $apellido && $correo && $password) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);

        if ($stmt->fetch()) {
            $mensaje = 'Ese correo ya está registrado.';
            $tipoMensaje = 'error';
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO usuarios (nombre, apellido, correo, telefono, password, rol_id)
                VALUES (?, ?, ?, ?, ?, 2)
            ");
            $stmt->execute([$nombre, $apellido, $correo, $telefono, $password]);

            $mensaje = 'Usuario registrado correctamente. Ya puedes iniciar sesión.';
            $tipoMensaje = 'success';
        }
    } else {
        $mensaje = 'Complete todos los campos obligatorios.';
        $tipoMensaje = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Registro de usuario</h2>

        <?php if (!empty($mensaje)): ?>
            <div class="alert <?php echo $tipoMensaje === 'success' ? 'alert-success' : 'alert-error'; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Ingrese su nombre" required>
            </div>

            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" name="apellido" id="apellido" placeholder="Ingrese su apellido" required>
            </div>

            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" name="correo" id="correo" placeholder="Ingrese su correo" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" id="telefono" placeholder="Ingrese su teléfono">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Ingrese su contraseña" required>
            </div>

            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>

        <div class="form-footer">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
        </div>
    </div>
</body>
</html>