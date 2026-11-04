<?php

require_once __DIR__ . '/../includes/lib/funciones.php';
require_once __DIR__ . '/../includes/validaciones/valida_foto.php';

$idAnuncio = post('anuncio');
$textoAlt  = post('alt');
$titulo    = post('titulo_foto');

$errores = validarFoto($idAnuncio, $textoAlt);

if (!empty($errores)) {
    $params = [
        'errores' => implode('|', $errores),
        'alt'     => $textoAlt,
        'titulo'  => $titulo
    ];
    header("Location: ../anadir_foto.php?id=" . urlencode($idAnuncio) . "&" . http_build_query($params));
    exit;
}

header("Location: ../ver_anuncio.php?id=" . urlencode($idAnuncio) . "&foto_exito=1");
exit;