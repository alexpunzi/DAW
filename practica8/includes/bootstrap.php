<?php

// Configuramos seguridad de la cookie de sesión antes de iniciarla
session_set_cookie_params([
    'httponly' => true, // Evita que JS acceda a la cookie
    'samesite' => 'Lax' // Previene ataques CSRF básicos
]);

// Iniciar sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ----- FUNCIONES PARA MENSAJES FLASH (FLASHDATA) -----

/**
 * Guarda un mensaje temporal en la sesión.
 * $clave Identificador del mensaje (ej: 'error', 'exito').
 * $valor El texto del mensaje.
 */
function flash_set($clave, $valor) {
    $_SESSION['flash'][$clave] = $valor;
}

// Recupera un mensaje temporal y lo destruye para que no vuelva a aparecer
function flash_get($clave) {
    if (isset($_SESSION['flash'][$clave])) {
        $valor = $_SESSION['flash'][$clave];
        unset($_SESSION['flash'][$clave]); // Se elimina tras leerlo
        return $valor;
    }
    return null;
}