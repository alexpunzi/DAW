<?php

function post(string $clave)
{
    return isset($_POST[$clave]) && is_scalar($_POST[$clave])
        ? trim((string)$_POST[$clave])
        : '';
}

function get(string $clave)
{
    return isset($_GET[$clave]) && is_scalar($_GET[$clave])
        ? trim((string)$_GET[$clave])
        : '';
}

function leerId($valor): ?int
{
    $id = filter_var(
        $valor,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    return $id === false ? null : $id;
}