<?php

// Valida los campos obligatorios del formulario de registro de usuario.
// Devuelve una lista de códigos de error (vacía si todo es correcto).
function validarRegistro(array $datos) {
    $errores = [];

    $usuario    = trim((string)($datos['nombre'] ?? ''));
    $apellidos  = trim((string)($datos['apellidos'] ?? ''));
    $password   = trim((string)($datos['password'] ?? ''));
    $repassword = trim((string)($datos['confirmar_password'] ?? ''));

    if ($usuario === '') {
        $errores[] = 'e_usuario_vacio';
    }

    if ($apellidos === '') {
        $errores[] = 'e_apellidos_vacios';
    }

    if ($password === '') {
        $errores[] = 'e_password_vacio';
    }

    if ($repassword === '') {
        $errores[] = 'e_repassword_vacio';
    }

    // solo se comparan si el usuario ha escrito ambas
    if ($password !== '' && $repassword !== '' && $password !== $repassword) {
        $errores[] = 'e_password_mismatch';
    }

    return $errores;
}
