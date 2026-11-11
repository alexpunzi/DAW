<?php

require_once __DIR__ . '/../lib/funciones.php';
require_once __DIR__ . '/../validaciones/valida_anuncio.php';

requiereUsuario();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('crear_anuncio.php');
}

$campos = ['tipo_anuncio', 'tipo_vivienda', 'titulo', 'descripcion', 'precio', 'ciudad', 'pais',
           'superficie', 'habitaciones', 'banos', 'planta', 'ano_construccion'];

$datos = [];
foreach ($campos as $campo) {
    $datos[$campo] = post($campo);
}

$errores = validarAnuncio($_POST);

if (!empty($errores)) {
    redirigir('crear_anuncio.php', $datos + ['errores' => implode('|', $errores)]);
}

// Todavía no se guarda en base de datos: se muestran los datos recibidos
redirigir('crear_anuncio.php', $datos + ['exito' => '1']);
