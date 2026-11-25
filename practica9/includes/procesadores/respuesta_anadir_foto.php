<?php
require_once __DIR__ . '/../bootstrap.php';

require_once __DIR__ . '/../validaciones/valida_foto.php';
require_once __DIR__ . '/../servicios/anuncios.php';

$usuario = requiereUsuario();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('anadir_foto.php');
}

$idAnuncio = post('anuncio');
$textoAlt  = post('alt');
$titulo    = post('titulo_foto');
$fijo      = post('fijo') === '1'; //el anuncio venía bloqueado en el formulario

$errores = validarFoto($_POST);

//el anuncio debe pertenecer al usuario
if (empty($errores)) {
    if (!usuarioEsPropietarioDeAnuncio((int)$idAnuncio, $usuario['email'])) {
        $errores[] = 'El anuncio seleccionado no es válido.';
    }
}

if (!empty($errores)) {
    $params = [
        'errores'     => implode('|', $errores),
        'alt'         => $textoAlt,
        'titulo_foto' => $titulo
    ];
    $params[$fijo ? 'anuncio' : 'seleccionado'] = $idAnuncio;
    redirigir('anadir_foto.php', $params);
}
redirigir('ver_anuncio.php', ['id' => (int)$idAnuncio, 'foto_exito' => '1']);
