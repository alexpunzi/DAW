<?php
require_once __DIR__ . '/../bootstrap.php';

require_once __DIR__ . '/../lib/funciones.php';
require_once __DIR__ . '/../validaciones/valida_registro.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('registro.php');
}

// Datos que se devuelven por URL, las contraseñas y URL no
$datos = [
    'nombre'           => post('nombre'),
    'apellidos'        => post('apellidos'),
    'email'            => post('email'),
    'sexo'             => post('sexo'),
    'fecha_nacimiento' => post('fecha_nacimiento'),
    'ciudad'           => post('ciudad'),
    'pais'             => post('pais')
];

$errores = validarRegistro($_POST);

if (!empty($errores)) {
    // Vuelve al formulario con los errores y los datos ya escritos
    redirigir('registro.php', $datos + ['errores' => implode(',', $errores)]);
}

// si todo es correcto, se muestran los datos recibidos
redirigir('registro.php', $datos + ['exito' => '1']);
