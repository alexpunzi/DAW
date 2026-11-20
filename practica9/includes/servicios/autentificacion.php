<?php

require_once __DIR__ . '/../validaciones/valida_login.php';
require_once __DIR__ . '/../repositorios/usuarios.php';
require_once __DIR__ . '/../lib/session.php';
require_once __DIR__ . '/../lib/recordar.php';

function autenticarUsuario(string $email, string $password): array
{
    $error = validarLogin($email, $password, cargarUsuarios());
    if ($error !== null) {
        return [
            'error' => $error,
            'usuario' => null,
            'ultima_sesion' => null,
            'error_historial' => false,
        ];
    }

    $usuario = buscarUsuario($email);
    if ($usuario === null) {
        return [
            'error' => 'credenciales',
            'usuario' => null,
            'ultima_sesion' => null,
            'error_historial' => false,
        ];
    }

    session_regenerate_id(true);
    $_SESSION['usuario'] = $email;
    if (isset($usuario['estilo'])) {
        $_SESSION['estilo'] = $usuario['estilo'];
    }

    $ultimaSesion = null;
    $errorHistorial = false;
    try {
        $ultimaSesion = registrarUltimaSesion($email);
    } catch (RuntimeException $error) {
        error_log($error->getMessage());
        $errorHistorial = true;
    }

    return [
        'error' => null,
        'usuario' => $usuario,
        'ultima_sesion' => $ultimaSesion,
        'error_historial' => $errorHistorial,
    ];
}

function crearTokenRecordatorio(string $email): string
{
    $token = bin2hex(random_bytes(32));
    registrarTokenEnServidor($email, $token);

    return $token;
}

function cerrarSesionActual(): bool
{
    $recordatorioRevocado = true;
    if (isset($_COOKIE['recordarme']) && is_string($_COOKIE['recordarme'])) {
        try {
            eliminarTokenRecordarme($_COOKIE['recordarme']);
        } catch (RuntimeException $error) {
            error_log($error->getMessage());
            $recordatorioRevocado = false;
        }
    }

    $errorCierre = $recordatorioRevocado
        ? null
        : ($_SESSION['flash']['error'] ?? null);
    $_SESSION = [];
    if ($errorCierre !== null) {
        flash_set('error', $errorCierre);
    }
    session_regenerate_id(true);

    setcookie('recordarme', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ]);

    if (isset($_COOKIE['ultimos_visitados'])) {
        setcookie('ultimos_visitados', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true,
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'samesite' => 'Lax',
        ]);
        unset($_COOKIE['ultimos_visitados']);
    }

    return $recordatorioRevocado;
}