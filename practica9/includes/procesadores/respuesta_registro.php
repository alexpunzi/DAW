<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../validaciones/valida_registro.php';
require_once __DIR__ . '/../repositorios/carga_usuarios.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('registro.php');
}

//los datos se conservan temporalmente en sesión, excepto las contraseñas.
$datos = [
    'nombre'           => post('nombre'),
    'apellidos'        => post('apellidos'),
    'email'            => post('email'),
    'sexo'             => post('sexo'),
    'fecha_nacimiento' => post('fecha_nacimiento'),
    'ciudad'           => post('ciudad'),
    'pais'             => post('pais'),
];

$errores = validarRegistro($_POST);

if (empty($errores)) {
    $errorDuplicado = existeUsuarioOEmail($datos['nombre'], $datos['email']);
    if ($errorDuplicado !== null) {
        $errores[] = 'e_duplicado';
        flash_set('registro_error_general', $errorDuplicado);
    }
}

if (!empty($errores)) {
    flash_set('registro_errores', $errores);
    flash_set('registro_datos', $datos);
    flash_set('registro_exito', false);
    redirigir('registro.php');
}

//insertamos el usuario en MySQL
$datosInsercion = $datos;
$datosInsercion['password'] = post('password');

$nuevoId = registrarUsuario($datosInsercion);

if ($nuevoId === null) {
    flash_set('registro_errores', ['e_bd']);
    flash_set('registro_error_general', 'No se pudo completar el registro en la base de datos.');
    flash_set('registro_datos', $datos);
    flash_set('registro_exito', false);
    redirigir('registro.php');
}

flash_set('registro_errores', []);
flash_set('registro_datos', $datos);
flash_set('registro_exito', true);
redirigir('registro.php');