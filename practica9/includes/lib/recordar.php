<?php

require_once __DIR__ . '/datos.php';
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/fechas.php';
require_once __DIR__ . '/session.php';

function registrarTokenEnServidor(string $email, string $token): ?int
{
    $rutaArchivo = __DIR__ . '/../../datos/recordar.json';
    $datos = [];

    if (file_exists($rutaArchivo)) {
        $contenido = file_get_contents($rutaArchivo);
        if ($contenido === false) {
            throw new RuntimeException('No se pudo leer el archivo de recordatorio.');
        }

        $datos = json_decode($contenido, true);
        if (!is_array($datos)) {
            throw new RuntimeException('El archivo de recordatorio no contiene datos válidos.');
        }
    }

    $ultimaVisita = $datos[$email]['ultima_visita'] ?? null;
    $ultimaVisita = is_numeric($ultimaVisita) && (int)$ultimaVisita > 0
        ? (int)$ultimaVisita
        : null;

    $datos = [
        $email => [
            'email' => $email,
            'token_hash' => hash('sha256', $token),
            'caducidad' => time() + (90 * 24 * 60 * 60),
            'ultima_visita' => time(),
        ],
    ];

    $contenido = json_encode($datos, JSON_PRETTY_PRINT);
    if ($contenido === false || file_put_contents($rutaArchivo, $contenido, LOCK_EX) === false) {
        throw new RuntimeException('No se pudo guardar el recordatorio de acceso.');
    }

    return $ultimaVisita;
}

function eliminarTokenRecordarme(string $token): void
{
    $rutaArchivo = __DIR__ . '/../../datos/recordar.json';
    if (!file_exists($rutaArchivo)) {
        return;
    }

    $contenido = file_get_contents($rutaArchivo);
    if ($contenido === false) {
        throw new RuntimeException('No se pudo leer el archivo de recordatorio.');
    }

    $datos = json_decode($contenido, true);
    if (!is_array($datos)) {
        throw new RuntimeException('El archivo de recordatorio no contiene datos válidos.');
    }

    $hashToken = hash('sha256', $token);
    foreach ($datos as $email => $registro) {
        if (isset($registro['token_hash'])
            && is_string($registro['token_hash'])
            && hash_equals($registro['token_hash'], $hashToken)) {
            unset($datos[$email]);

            $contenido = json_encode($datos, JSON_PRETTY_PRINT);
            if ($contenido === false || file_put_contents($rutaArchivo, $contenido, LOCK_EX) === false) {
                throw new RuntimeException('No se pudo eliminar el recordatorio de acceso.');
            }
            return;
        }
    }
}

function eliminarCookieRecordarme(): void
{
    setcookie('recordarme', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);
    unset($_COOKIE['recordarme']);
}

function recuperarSesionRecordada(): bool
{
    if (isset($_SESSION['usuario']) || !isset($_COOKIE['recordarme'])) {
        return false;
    }

    $cookieValida = false;
    $rutaArchivo = __DIR__ . '/../../datos/recordar.json';
    if (!file_exists($rutaArchivo)) {
        error_log('No se encontró el archivo de recordatorio de acceso.');
    } else {
        $contenido = file_get_contents($rutaArchivo);
        $datos = $contenido === false ? null : json_decode($contenido, true);
        if (!is_array($datos)) {
            error_log('No se pudieron leer datos válidos de recordar.json.');
        } else {
            $hashCookie = hash('sha256', $_COOKIE['recordarme']);

            foreach ($datos as $email => $registro) {
                $registroValido = is_array($registro)
                    && isset($registro['token_hash'], $registro['caducidad'])
                    && is_string($registro['token_hash']);

                if (!$registroValido || !hash_equals($registro['token_hash'], $hashCookie)) {
                    continue;
                }

                $noHaCaducado = is_numeric($registro['caducidad'])
                    && (int)$registro['caducidad'] > time();

                if (!$noHaCaducado) {
                    break;
                }

                $usuario = buscarUsuario((string)$email);
                if ($usuario === null) {
                    break;
                }

                session_regenerate_id(true);
                $_SESSION['usuario'] = $email;
                if (isset($usuario['estilo'])) {
                    $_SESSION['estilo'] = $usuario['estilo'];
                }

                $cookieValida = true;
                try {
                    $ultimaSesion = registrarUltimaSesion((string)$email);
                    if ($ultimaSesion === null
                        && isset($registro['ultima_visita'])
                        && is_numeric($registro['ultima_visita'])) {
                        $ultimaSesion = (int)$registro['ultima_visita'];
                    }

                    if ($ultimaSesion !== null) {
                        flash_set(
                            'exito',
                            '¡Bienvenido de nuevo, ' . $usuario['nombre']
                            . '! Tu última conexión fue el '
                            . formatearFechaHora($ultimaSesion) . '.'
                        );
                    }
                } catch (RuntimeException $error) {
                    error_log($error->getMessage());
                    flash_set(
                        'exito',
                        'Has iniciado sesión, pero no se pudo consultar ni guardar la hora de tu última sesión.'
                    );
                }

                break;
            }
        }
    }

    if (!$cookieValida && !isset($_SESSION['usuario'])) {
        eliminarCookieRecordarme();
    }

    return $cookieValida;
}