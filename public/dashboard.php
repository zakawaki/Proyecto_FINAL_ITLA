<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/session.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="navbar">
        <div class="container-nav">
            <h1>Sistema de Reservas</h1>
            <ul>
                <li><a href="dashboard.php">Inicio</a></li>
                <li><a href="recursos.php">Recursos</a></li>
                <li><a href="mis_reservas.php">Mis reservas</a></li>
                <li><a href="disponibilidad.php">Disponibilidad</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <li><a href="mis_pagos.php">Mis pagos</a></li>

                <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
                    <li><a href="usuarios.php">Usuarios</a></li>
                    <li><a href="admin_recursos.php">Admin recursos</a></li>
                    <li><a href="reportes.php">Reportes</a></li>
                <?php endif; ?>

                <li><a href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></h2>
            <p>Rol del usuario: <strong><?php echo htmlspecialchars($_SESSION['usuario_rol']); ?></strong></p>
            <p>Desde este panel puede gestionar reservas, consultar recursos disponibles, revisar disponibilidad y visualizar su historial.</p>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-box">
                <h3>Recursos disponibles</h3>
                <p>Consulta habitaciones, consultorios y canchas disponibles para reservar.</p>
                <a class="btn btn-primary mt-20" href="recursos.php">Ver recursos</a>
            </div>

            <div class="dashboard-box">
                <h3>Mis reservas</h3>
                <p>Revise el estado de sus reservas registradas y el historial de uso.</p>
                <a class="btn btn-primary mt-20" href="mis_reservas.php">Ver reservas</a>
            </div>

            <div class="dashboard-box">
                <h3>Disponibilidad</h3>
                <p>Consulte la disponibilidad de recursos por fecha y horario.</p>
                <a class="btn btn-primary mt-20" href="disponibilidad.php">Consultar</a>
            </div>

            <div class="dashboard-box">
                <h3>Mis pagos</h3>
                <p>Consulte el historial de pagos realizados en el sistema.</p>
                <a class="btn btn-primary mt-20" href="mis_pagos.php">Ver pagos</a>
            </div>

            <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
                <div class="dashboard-box">
                    <h3>Gestión de usuarios</h3>
                    <p>Administre usuarios registrados, roles y estado de acceso.</p>
                    <a class="btn btn-primary mt-20" href="usuarios.php">Administrar usuarios</a>
                </div>

                <div class="dashboard-box">
                    <h3>Gestión de recursos</h3>
                    <p>Administre habitaciones, consultorios y canchas del sistema.</p>
                    <a class="btn btn-primary mt-20" href="admin_recursos.php">Administrar recursos</a>
                </div>

                <div class="dashboard-box">
                    <h3>Reportes administrativos</h3>
                    <p>Visualice ingresos, reservas y recursos más utilizados.</p>
                    <a class="btn btn-primary mt-20" href="reportes.php">Ver reportes</a>
                </div>
            <?php endif; ?>

            <div class="dashboard-box">
                <h3>Cerrar sesión</h3>
                <p>Finalice la sesión actual de forma segura.</p>
                <a class="btn btn-danger mt-20" href="logout.php">Salir</a>
            </div>
            
        </div>
    </div>
</body>
</html>