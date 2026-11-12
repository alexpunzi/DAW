<?php
require_once __DIR__ . '/../bootstrap.php';

require_once __DIR__ . '/../lib/funciones.php';
require_once __DIR__ . '/../validaciones/valida_registro.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('registro.php');
}

// Los datos se conservan temporalmente en sesión, excepto las contraseñas.
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
    flash_set('registro_errores', $errores);
    flash_set('registro_datos', $datos);
    flash_set('registro_exito', false);
    redirigir('registro.php');
}

flash_set('registro_errores', []);
flash_set('registro_datos', $datos);
flash_set('registro_exito', true);
redirigir('registro.php');
