<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: public/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Reservas</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .hero {
            text-align: center;
            padding: 80px 20px;
        }

        .hero h1 {
            font-size: 40px;
            margin-bottom: 15px;
            color: #0f172a;
        }

        .hero p {
            font-size: 18px;
            color: #475569;
            margin-bottom: 30px;
        }

        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .features {
            margin-top: 50px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .feature-box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            text-align: center;
            transition: 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-5px);
        }

        .footer {
            text-align: center;
            margin-top: 60px;
            padding: 20px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="container-nav">
            <h1>Sistema de Reservas</h1>
            <ul>
                <li><a href="public/login.php">Login</a></li>
                <li><a href="public/registro.php">Registro</a></li>
            </ul>
        </div>
    </div>

    <div class="container hero">
        <h1>Gestión inteligente de reservas</h1>
        <p>Administra hoteles, consultorios y canchas deportivas de manera rápida, segura y eficiente.</p>

        <div class="hero-buttons">
            <a href="public/login.php" class="btn btn-small">Iniciar sesión</a>
            <a href="public/registro.php" class="btn btn-secondary btn-small">Crear cuenta</a>
        </div>
    </div>

    <div class="container features">
        <h2 class="text-center mb-20">Funciones del sistema</h2>

        <div class="features-grid">
            <div class="feature-box">
                <h3>Reservas en tiempo real</h3>
                <p>Consulta disponibilidad y evita conflictos automáticamente.</p>
                <a href="public/login.php" class="btn btn-small mt-20">Probar</a>
            </div>

            <div class="feature-box">
                <h3>Gestión de recursos</h3>
                <p>Administra habitaciones, consultorios y canchas.</p>
                <a href="public/login.php" class="btn btn-small mt-20">Ver recursos</a>
            </div>

            <div class="feature-box">
                <h3>Mis reservas</h3>
                <p>Consulta y gestiona tus reservas fácilmente.</p>
                <a href="public/login.php" class="btn btn-small mt-20">Ir a reservas</a>
            </div>

            <div class="feature-box">
                <h3>Panel de control</h3>
                <p>Accede al dashboard del sistema.</p>
                <a href="public/login.php" class="btn btn-small mt-20">Ir al panel</a>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Proyecto Sistema de Reservas - ITLA  2026</p>
        <p> Zakawaki    </p>
    </div>
</body>
</html>