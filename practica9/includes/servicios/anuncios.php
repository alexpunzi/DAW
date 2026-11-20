<?php

require_once __DIR__ . '/../repositorios/anuncios.php';

function usuarioEsPropietarioDeAnuncio(int $idAnuncio, string $email): bool
{
    $anuncio = buscarAnuncio($idAnuncio);
    return $anuncio !== null && ($anuncio['usuario'] ?? null) === $email;
}
