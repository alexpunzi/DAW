<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../validaciones/valida_registro.php';

if (empty($_SESSION['usuario'])) {
    flash_set('error', 'Debes iniciar sesión para acceder a tus datos.');
    redirigir('index.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('mis_datos.php');
}

//conservamos los datos del formulario en sesión (excepto las contraseñas)
$datos = [
    'nombre'           => post('nombre'),
    'email'            => post('email'),
    'sexo'             => post('sexo'),
    'fecha_nacimiento' => post('fecha_nacimiento'),
    'ciudad'           => post('ciudad'),
    'pais'             => post('pais'),
];

$errores = validarRegistro($_POST, true);

if (!empty($errores)) {
    flash_set('mis_datos_errores', $errores);
    flash_set('mis_datos_form', $datos);
    flash_set('mis_datos_exito', false);
    redirigir('mis_datos.php');
}

flash_set('mis_datos_errores', []);
flash_set('mis_datos_form', $datos);
flash_set('mis_datos_exito', true);
redirigir('mis_datos.php');