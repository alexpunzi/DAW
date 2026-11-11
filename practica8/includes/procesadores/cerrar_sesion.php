<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../lib/funciones.php';

if (isset($_COOKIE['recordarme']) && is_string($_COOKIE['recordarme'])) {
    try {
        eliminarTokenRecordarme($_COOKIE['recordarme']);
    } catch (RuntimeException $error) {
        error_log($error->getMessage());
        flash_set('error', 'Se cerró la sesión, pero no se pudo revocar el acceso guardado.');
    }
}

$_SESSION = [];
session_destroy();

setcookie('recordarme', '', [
    'expires'  => time() - 3600,
    'path'     => '/',
    'httponly' => true,
    'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax'
]);
setcookie(session_name(), '', [
    'expires'  => time() - 3600,
    'path'     => '/',
    'httponly' => true,
    'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax'
]);

redirigir('index.php');
