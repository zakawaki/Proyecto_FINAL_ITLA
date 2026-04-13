<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config/database.php';

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT estado FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if ($usuario) {
    $nuevoEstado = $usuario['estado'] === 'activo' ? 'inactivo' : 'activo';
    $update = $pdo->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
    $update->execute([$nuevoEstado, $id]);
}

header('Location: usuarios.php');
exit;