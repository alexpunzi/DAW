<?php

//escapa caractaeres especiales en HTML para prevenir vulnerabilidades 
function e($texto) {
    if ($texto === null) {
        return '';
    }
    return htmlspecialchars((string)$texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

//lee un parametro $_POST
function post($clave) {
    return isset($_POST[$clave]) ? trim((string)$_POST[$clave]) : '';
}

//lee un parametro $_GET
function get($clave) {
    return isset($_GET[$clave]) ? trim((string)$_GET[$clave]) : '';
}

//convierte un numero en una representacion el el signo del euro 
function euros($cantidad) {
    return number_format((float)$cantidad, 2, ',', '.') . ' €';
}

//comprueba si una variable o cadena contiene exclusivamente espacios en blanco 
function campoVacio($texto) {
    return trim((string)$texto) === '';
}