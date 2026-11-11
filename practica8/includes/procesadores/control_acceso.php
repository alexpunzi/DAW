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

// Aquí es donde añadiremos la lógica de la casilla "Recordame"

// Redirigimos al panel privado de forma limpia
header('Location: ../index_sesion.php');
exit;