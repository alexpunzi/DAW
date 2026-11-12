<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../lib/funciones.php';

// Revocar token de auto-login "recordarme" en la libreta del servidor
if (isset($_COOKIE['recordarme']) && is_string($_COOKIE['recordarme'])) {
    try {
        eliminarTokenRecordarme($_COOKIE['recordarme']);
    } catch (RuntimeException $error) {
        error_log($error->getMessage());
        flash_set('error', 'Se cerró la sesión, pero no se pudo revocar el acceso guardado.');
    }
}

// Vaciar la sesión, conservando el aviso si no se pudo revocar el recordatorio.
$errorCierre = $_SESSION['flash']['error'] ?? null;
$_SESSION = [];
if ($errorCierre !== null) {
    flash_set('error', $errorCierre);
}
session_regenerate_id(true);

// Borrar cookie "recordarme"
setcookie('recordarme', '', [
    'expires'  => time() - 3600,
    'path'     => '/',
    'httponly' => true,
    'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax'
]);

// Borrar cookie del panel "Últimos anuncios visitados"
if (isset($_COOKIE['ultimos_visitados'])) {
    setcookie('ultimos_visitados', '', [
        'expires'  => time() - 3600,
        'path'     => '/',
        'httponly' => true,
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax'
    ]);
    unset($_COOKIE['ultimos_visitados']);
}

// Redirigir a la portada pública
redirigir('index.php');