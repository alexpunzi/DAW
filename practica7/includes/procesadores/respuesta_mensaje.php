<?php

require_once __DIR__ . '/../includes/lib/funciones.php';
require_once __DIR__ . '/../includes/validaciones/valida_mensaje.php';

$tipoMensaje  = post('tipo_mensaje');
$textoMensaje = post('texto_mensaje');
$idAnuncio    = post('id_anuncio');

$errores = validarMensaje($tipoMensaje, $textoMensaje);

if (!empty($errores)) {
    $params = [
        'errores'    => implode('|', $errores),
        'tipo'       => $tipoMensaje,
        'texto'      => $textoMensaje,
        'id_anuncio' => $idAnuncio
    ];
    header("Location: ../mensaje.php?" . http_build_query($params));
    exit;
}

// confirmación de mensaje enviado con éxito
$params = http_build_query([
    'exito' => '1',
    'tipo'  => $tipoMensaje,
    'texto' => $textoMensaje
]);
header("Location: ../mensaje.php?" . $params);
exit;