<?php
require_once __DIR__ . '/../bootstrap.php';

require_once __DIR__ . '/../lib/funciones.php';
require_once __DIR__ . '/../validaciones/valida_mensaje.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('mensaje.php');
}

$tipoMensaje  = post('tipo_mensaje');
$textoMensaje = post('texto_mensaje');
$idAnuncio    = post('id_anuncio');

$errores = validarMensaje($tipoMensaje, $textoMensaje);

if (!empty($errores)) {
    redirigir('mensaje.php', [
        'errores'    => implode('|', $errores),
        'tipo'       => $tipoMensaje,
        'texto'      => $textoMensaje,
        'id_anuncio' => $idAnuncio
    ]);
}

// mensaje correcto, página de confirmación con los datos introducidos
redirigir('mensaje_enviado.php', [
    'tipo'  => $tipoMensaje,
    'texto' => $textoMensaje
]);
