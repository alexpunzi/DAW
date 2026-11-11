<?php
require_once __DIR__ . '/../bootstrap.php';

// Esta es una página que no es visible para el usuario
// Controla el acceso a la parte privada
require_once __DIR__ . '/../lib/funciones.php';
require_once __DIR__ . '/../validaciones/valida_login.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redirigimos de forma limpia, sin parámetros
    header('Location: ../index.php');
    exit;
}

// Recoge los datos del usuario
$email    = post('login_email');
$password = post('login_password');

// Ejecuta la validación
$error = validarLogin($email, $password, cargarUsuarios());


// ----- GESTIÓN DE ERRORES -----
if ($error !== null) {
    // Guardamos el error y el email en la sesión (flashdata)
    flash_set('error', $error);
    flash_set('email_previo', $email);

    // Redirigimos sin variables en la URL
    header('Location: ../index.php');
    exit;
}


// ----- ACCESO CORRECTO -----
$_SESSION['usuario'] = $email;

// Lógica de "Recordarme"
// Comprobamos si el usuario marcó la casila en el formulario
if (isset($_POST['recordarme']) && $_POST['recordarme'] === '1') {
    // Generamos el token aleatorio muy seguro
    $token = bin2hex(random_bytes(32));

    // Le entregaos el billete al navegador usando una cookie
    setcookie('recordarme', $token, [
        'expires'  => time() + (90 * 24 * 60 * 60), // Caducidad
        'path'     => '/', // En todas las carpetas de la web
        'httponly' => true // Bloque el acceso desde JS por seguridad
    ]);

    // Guardamos la matrícula de este billete en el servidor
    registrarTokenEnServidor($email, $token);
}

// Redirigimos al panel privado de forma limpia
header('Location: ../index_sesion.php');
exit;