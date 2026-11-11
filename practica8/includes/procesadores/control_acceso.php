<?php


//esta es una pagina que no es visible para el usuario
//controla el acceso a la parte privada, es decir cuando ya el usuario se regitrsa que se muestren las funciones especificas 


require_once __DIR__ . '/../lib/funciones.php';
require_once __DIR__ . '/../validaciones/valida_login.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { //validamos el metodo de acceso, comprobando que si el usuario no entra por peticion POST que lo redirija a index.php 
    redirigir('index.php');
}


//recoge los datos del usuario que introducira en el formulario 
$email    = post('login_email');
$password = post('login_password');

//ejecuta la funciond e validacion pasandole los parametros 
$error = validarLogin($email, $password, cargarUsuarios());



// GESTIÓN DE ERRORES 
if ($error !== null) {
    redirigir('index.php', ['error' => $error, 'email_previo' => $email]);
}

if ($resultadoError !== null) {
    // Redirigimos enviando el error y el email introducido para no perderlo
    header("Location: ../index.php?error=" . $resultadoError . "&email_previo=" . urlencode($email));
    exit;
}

// Acceso correcto, SE REDIRIGE AL USUARIO AL index_sesion.php 
redirigir('index_sesion.php', ['usuario' => $email]);


