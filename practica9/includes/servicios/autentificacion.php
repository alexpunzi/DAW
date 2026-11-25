<?php

require_once __DIR__ . '/../validaciones/valida_login.php';
require_once __DIR__ . '/../repositorios/carga_usuarios.php';
require_once __DIR__ . '/../lib/session.php';
require_once __DIR__ . '/../lib/recordar.php';

function autenticarUsuario(string $email, string $password): array
{
    $email = trim($email);

    if ($email === '' || $password === '') {
        return [
            'error' => 'campos_vacios',
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


    $claveGuardada = (string)($usuario['password'] ?? '');
    $claveValida = password_verify($password, $claveGuardada) || hash_equals($claveGuardada, $password);

    if (!$claveValida) {
        return [
            'error' => 'credenciales',
            'usuario' => null,
            'ultima_sesion' => null,
            'error_historial' => false,
        ];
    }

    session_regenerate_id(true);
    $_SESSION['id_usuario'] = $usuario['id'];
    $_SESSION['usuario']    = $usuario['email'];
    $_SESSION['nombre']     = $usuario['nombre'];
    $_SESSION['id_estilo']  = $usuario['id_estilo'];
    // Sin "Recordarme" no se aplica el estilo guardado: se decide en control_acceso.php
    unset($_SESSION['estilo'], $_SESSION['recordarme_activo'], $_SESSION['ultima_sesion_previa']);

    return [
        'error' => null,
        'usuario' => $usuario,
        'ultima_sesion' => null,
        'error_historial' => false,
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
    $recordatorioCerrado = true;
    if (isset($_COOKIE['recordarme']) && is_string($_COOKIE['recordarme'])) {
        try {
            marcarTokenSesionCerrada($_COOKIE['recordarme']);
        } catch (RuntimeException $error) {
            error_log($error->getMessage());
            $recordatorioCerrado = false;
        }
    }

    $_SESSION = [];
    session_regenerate_id(true);

    return $recordatorioCerrado;
}
