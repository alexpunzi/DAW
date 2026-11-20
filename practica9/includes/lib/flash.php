<?php

//Definicion de las funciones flash 
function flash_set(string $clave, $valor): void
{
    $_SESSION['flash'][$clave] = $valor;
}

function flash_get(string $clave)
{
    if (!isset($_SESSION['flash'][$clave])) {
        return null;
    }

    $valor = $_SESSION['flash'][$clave];
    unset($_SESSION['flash'][$clave]);

    return $valor;
}

function flash_get_many(array $claves): array
{
    $valores = [];
    foreach ($claves as $clave) {
        $valores[$clave] = flash_get($clave);
    }
    return $valores;
}