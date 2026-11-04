<?php
require_once __DIR__ . '/../lib/funciones.php';


function validarFoto(array $datos): array {
    $errores = [];

    //se extraen los campos 
    $idAnuncio = trim($datos['anuncio'] ?? '');
    $textoAlt  = trim($datos['alt'] ?? '');
    $titulo    = trim($datos['titulo_foto'] ?? '');

    //se valida el id del anuncio 
    if ($idAnuncio === '' || !is_numeric($idAnuncio) || (int)$idAnuncio <= 0) {
        $errores[] = 'Debes seleccionar un anuncio válido.';
    }

    
    if ($textoAlt === '') {
        $errores[] = 'El texto alternativo es obligatorio.';
    } elseif (mb_strlen($textoAlt) < 10) {
        $errores[] = 'El texto alternativo debe tener una longitud mínima de 10 caracteres.';[cite: 19]
    }

    
    if ($titulo === '') {
        $errores[] = 'El título de la foto es obligatorio.';
    }

    return $errores;
}