<?php

//funciones para el acceso a los datos simulados 

function cargarAnuncios(): array {
    $fichero = __DIR__ . '/../../datos/anuncios.php'; // se carga el array de anuncios definidos en esta direccion  datos/anuncios.php ('impar' y 'par')
    if (!is_readable($fichero)) {
        return [];
    }
    require $fichero; // define $anunciosFicticios (variable local de esta función)
    return (isset($anunciosFicticios) && is_array($anunciosFicticios)) ? $anunciosFicticios : [];
}

// devuelve el array de usuarios definido en datos/usuarios.php
function cargarUsuarios(): array {
    $fichero = __DIR__ . '/../../datos/usuarios.php';
    if (!is_readable($fichero)) {
        return [];
    }
    require $fichero; // define $usuariosValidos
    return (isset($usuariosValidos) && is_array($usuariosValidos)) ? $usuariosValidos : [];
}

// Convierte un valor en un id entero positivo, o null si no es válido
function leerId($valor): ?int {
    $id = filter_var($valor, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    return $id === false ? null : $id;
}



function buscarAnuncio(int $id): ?array {
    return cargarAnuncios()[$id] ?? null;
}

function anunciosDeUsuario(string $email): array {
    $propios = [];
    foreach (cargarAnuncios() as $anuncio) {
        if (($anuncio['usuario'] ?? '') === $email) {
            $propios[] = $anuncio;
        }
    }
    return $propios;
}

function buscarUsuario(string $email): ?array {
    foreach (cargarUsuarios() as $usuario) {
        if ($usuario['email'] === $email) {
            return $usuario;
        }
    }
    return null;
}
