<?php

require_once __DIR__ . '/../includes/lib/funciones.php';
require_once __DIR__ . '/../includes/validaciones/valida_registro.php';


//recogida de los campos obligatorios y opcionales que aparecen en el formulario 
$nombre          = post('nombre');
$email           = post('email');
$password        = post('password');
$repassword      = post('confirmar_password');
$sexo            = post('sexo');
$fechaNacimiento = post('fecha_nacimiento');
$ciudad          = post('ciudad');
$pais            = post('pais');


//se ejectura el validador modular 
$errores = validarRegistro($_POST);

if (!empty($errores)) {
    //redirige de vuelta a registro.php devolviendo los errores 
    $params = [
        'errores'          => implode(',', $errores),
        'nombre'           => $nombre,
        'email'            => $email,
        'sexo'             => $sexo,
        'fecha_nacimiento' => $fechaNacimiento,
        'ciudad'           => $ciudad,
        'pais'             => $pais
    ];
    header("Location: ../registro.php?" . http_build_query($params));
    exit;
}


//si sale bien redirige a la vista de confirmación
$params = http_build_query([
    'exito'  => '1',
    'nombre' => $nombre,
    'email'  => $email
]);
header("Location: ../registro.php?" . $params);
exit;