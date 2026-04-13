<?php
declare(strict_types=1);

function recursoDisponible(PDO $pdo, int $recursoId, string $fechaInicio, string $fechaFin): bool
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM reservas
        WHERE recurso_id = ?
          AND estado_reserva_id IN (1, 2, 4)
          AND (
                (? < fecha_fin) AND (? > fecha_inicio)
          )
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$recursoId, $fechaInicio, $fechaFin]);
    $resultado = $stmt->fetch();

    return (int)$resultado['total'] === 0;
}

function calcularMontoTotal(PDO $pdo, int $recursoId, string $fechaInicio, string $fechaFin): float
{
    $stmt = $pdo->prepare("SELECT precio FROM recursos WHERE id = ?");
    $stmt->execute([$recursoId]);
    $recurso = $stmt->fetch();

    if (!$recurso) {
        return 0.00;
    }

    $precio = (float)$recurso['precio'];

    $inicio = new DateTime($fechaInicio);
    $fin = new DateTime($fechaFin);

    $diferenciaSegundos = $fin->getTimestamp() - $inicio->getTimestamp();
    $horas = max(1, ceil($diferenciaSegundos / 3600));

    return $precio * $horas;
}