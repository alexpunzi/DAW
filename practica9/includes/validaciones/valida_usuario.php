<?php

function validarDatosUsuario(array $datos, bool $esEdicion = false): array
{
    $errores = []; 

    //comprobar nombre de usuario 
    $nombre = trim($datos['nombre'] ?? '');
    if ($nombre === '' || !preg_match('/^[a-zA-Z][a-zA-Z0-9]{2,14}$/', $nombre)) {
        $errores['nombre'] = 'El nombre debe tener entre 3 y 15 caracteres alfanuméricos y empezar por letra.';
    }

    //contrasenya
    $pass1 = $datos['password'] ?? '';
    $pass2 = $datos['password2'] ?? '';
    if (!$esEdicion || $pass1 !== '' || $pass2 !== '') {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d_-]{6,15}$/', $pass1)) {
            $errores['password'] = 'La contraseña debe tener 6-15 caracteres, una mayúscula, una minúscula y un número.';
        } elseif ($pass1 !== $pass2) {
            $errores['password2'] = 'Las contraseñas no coinciden.';
        }
    }

    //email
    $email = trim($datos['email'] ?? '');
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'Introduce un correo electrónico válido.';
    }

    //sexo
    if (!in_array((string)($datos['sexo'] ?? ''), ['1', '2', '3'], true)) {
        $errores['sexo'] = 'Selecciona una opción válida.';
    }

    //fecha nacimiento 
    $fnac = $datos['fnacimiento'] ?? '';
    if ($fnac === '') {
        $errores['fnacimiento'] = 'La fecha de nacimiento es obligatoria.';
    } else {
        $fecha = DateTime::createFromFormat('Y-m-d', $fnac);
        $hoy = new DateTime();
        if (!$fecha || $fecha->diff($hoy)->y < 18) {
            $errores['fnacimiento'] = 'Debes ser mayor de 18 años.';
        }
    }

    return $errores;

}