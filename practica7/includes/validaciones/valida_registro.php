<?php

//valida los campos del fromulariuo de registro de un usuario

function validarRegistro(array $datos) {
    $errores = [];

    $usuario    = trim($datos['nombre'] ?? '');
    $password   = trim($datos['password'] ?? '');
    $repassword = trim($datos['confirmar_password'] ?? '');

    //validar nombre de usuario obligatorio
    if ($usuario === '') {
        $errores[] = 'e_usuario_vacio';
    }

    //validar contraseña obligatoria
    if ($password === '') {
        $errores[] = 'e_password_vacio';
    }

    //validar coincidencia de contraseñas
    if ($password !== $repassword) {
        $errores[] = 'e_password_mismatch';
    }

    return $errores;
}