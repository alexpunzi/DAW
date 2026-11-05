<?php

// valida el formulario de añadir una foto a un anuncio.
// recibe el array con los datos enviados ($_POST) y devuelve la lista de errores.
function validarFoto(array $datos): array {
    $errores = [];

    $idAnuncio = trim((string)($datos['anuncio'] ?? ''));
    $textoAlt  = trim((string)($datos['alt'] ?? ''));
    $titulo    = trim((string)($datos['titulo_foto'] ?? ''));

    if ($idAnuncio === '' || !ctype_digit($idAnuncio) || (int)$idAnuncio <= 0) {
        $errores[] = 'Debes seleccionar un anuncio válido.';
    }

    // el texto alternativo es obligatorio y debe tener al menos 10 caracteres
    if ($textoAlt === '') {
        $errores[] = 'El texto alternativo es obligatorio.';
    } elseif (mb_strlen($textoAlt) < 10) {
        $errores[] = 'El texto alternativo debe tener una longitud mínima de 10 caracteres.';
    }

    if ($titulo === '') {
        $errores[] = 'El título de la foto es obligatorio.';
    }

    return $errores;
}
