<?php
require_once __DIR__ . '/../bootstrap.php';

// Esta es una página que no es visible para el usuario
// Controla el acceso a la parte privada
require_once __DIR__ . '/../servicios/autentificacion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('index.php');
}

$email    = post('login_email');
$password = post('login_password');
$resultado = autenticarUsuario($email, $password);

if ($resultado['error'] !== null) {
    flash_set('error', $resultado['error']);
    flash_set('email_previo', $email);
    redirigir('index.php');
}

if ($resultado['error_historial']) {
    flash_set('exito', 'Has iniciado sesión, pero no se pudo consultar ni guardar la hora de tu última sesión.');
} elseif ($resultado['ultima_sesion'] !== null) {
    $nombre = $resultado['usuario']['nombre'] ?? $email;
    flash_set(
        'exito',
        "¡Bienvenido de nuevo, $nombre! Tu última conexión fue el "
        . formatearFechaHora($resultado['ultima_sesion']) . '.'
    );
}

if (isset($_POST['recordarme']) && $_POST['recordarme'] === '1') {
    try {
        $token = crearTokenRecordatorio($email);
        setcookie('recordarme', $token, [
            'expires'  => time() + (90 * 24 * 60 * 60),
            'path'     => '/',
            'httponly' => true,
            'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'samesite' => 'Lax'
        ]);
    } catch (RuntimeException $error) {
        error_log($error->getMessage());
        flash_set('exito', 'Has iniciado sesión, pero no se pudo guardar el acceso para próximas visitas.');
    }
}

redirigir('index_sesion.php');