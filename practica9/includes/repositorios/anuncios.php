<?php

function cargarAnuncios(): array
{
    $fichero = __DIR__ . '/../../datos/anuncios.php';
    if (!is_readable($fichero)) {
        return [];
    }

    require $fichero;
    return isset($anunciosFicticios) && is_array($anunciosFicticios)
        ? $anunciosFicticios
        : [];
}

function buscarAnuncio(int $id): ?array
{
    return cargarAnuncios()[$id] ?? null;
}

function anunciosDeUsuario(string $email): array
{
    $propios = [];
    foreach (cargarAnuncios() as $anuncio) {
        if (($anuncio['usuario'] ?? '') === $email) {
            $propios[] = $anuncio;
        }
    }

    return $propios;
}
