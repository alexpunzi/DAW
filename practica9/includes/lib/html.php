<?php

if (!defined('VALIDACION_JS')) {
    define('VALIDACION_JS', false);
}

function e($texto): string
{
    if ($texto === null) {
        return '';
    }

    return htmlspecialchars((string)$texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function euros($cantidad): string
{
    return number_format((float)$cantidad, 2, ',', '.') . ' €';
}

function campoVacio($texto): bool
{
    return trim((string)$texto) === '';
}

function paises(): array
{
    return [
        'argentina' => 'Argentina',
        'espana' => 'España',
        'mexico' => 'México',
    ];
}

function scriptsValidacion(array $scripts): array
{
    return VALIDACION_JS ? $scripts : [];
}

function obtenerErrorCampo(string $nombreCampo, array $errores): string
{
    foreach ($errores as $error) {
        $errorTexto = (string)$error;
        if (mb_stripos($errorTexto, $nombreCampo) !== false) {
            return $errorTexto;
        }
    }

    return '';
}