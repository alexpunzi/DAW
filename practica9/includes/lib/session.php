<?php

function registrarUltimaSesion(string $email): ?int
{
    $rutaArchivo = __DIR__ . '/../../datos/ultimas_sesiones.json';
    $sesiones = [];

    if (file_exists($rutaArchivo)) {
        $contenido = file_get_contents($rutaArchivo);
        if ($contenido === false) {
            throw new RuntimeException('No se pudo leer el historial de sesiones.');
        }

        $sesiones = json_decode($contenido, true);
        if (!is_array($sesiones)) {
            throw new RuntimeException('El historial de sesiones no contiene datos válidos.');
        }
    }

    $ultimaSesion = $sesiones[$email] ?? null;
    $ultimaSesion = is_numeric($ultimaSesion) && (int)$ultimaSesion > 0
        ? (int)$ultimaSesion
        : null;

    $sesiones[$email] = time();
    $contenido = json_encode($sesiones, JSON_PRETTY_PRINT);

    if ($contenido === false || file_put_contents($rutaArchivo, $contenido, LOCK_EX) === false) {
        throw new RuntimeException('No se pudo guardar el historial de sesiones.');
    }

    return $ultimaSesion;
}
