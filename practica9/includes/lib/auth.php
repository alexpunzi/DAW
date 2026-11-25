<?php

//Funciones que comprueban si existe un usuario u obtienen el usuario 

require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/url.php';



function usuarioActual(): ?array
{
    static $actual = false;

    if ($actual !== false) {
        return $actual;
    }

    $actual = null;
    if (!empty($_SESSION['usuario'])) {
        $actual = buscarUsuario((string)$_SESSION['usuario']);
    }

    return $actual;
}

function estaIdentificado(): bool
{
    return usuarioActual() !== null;
}

function requiereUsuario(): array
{
    $usuario = usuarioActual();
    if ($usuario === null) {
        flash_set('error', 'acceso');
        redirigir('index.php');
    }

    return $usuario;
}