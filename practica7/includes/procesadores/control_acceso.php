<?php

//cargamos las librerias 
require_once __DIR__ . '/../includes/lib/funciones.php';
require_once __DIR__ . '/../datos/usuarios.php';
require_once __DIR__ . '/../includes/validaciones/valida_login.php';

//recogida de datos pasados por POST
$email    = post('login_email');
$password = post('login_password');


$error = validarLogin($email, $password, $usuariosValidos);

if ($error !== null) {
    
    //redirige al usuario a la parte publica si da error 
    $params = http_build_query([
        'error'        => $error,
        'email_previo' => $email
    ]);
    header("Location: ../index.php?" . $params);
    exit;
}

//redirige al usuario a la parte privada
$params = http_build_query(['usuario' => $email]);
header("Location: ../index_sesion.php?" . $params);
exit;