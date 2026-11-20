<?php

function validarLogin($email, $password, array $usuariosValidos) {
    if (trim($email) === '' || trim($password) === '') {
        return 'vacio';
    }

    //comprueba si las credenciales coinciden con algun usuario 
    foreach ($usuariosValidos as $u) {
        if ($u['email'] === trim($email) && $u['password'] === trim($password)) {
            return null; //la autentiocacion es correcta
        }
    }

    return 'credenciales'; //devuelve si el usario o contraseña no fueron encontrados 
}