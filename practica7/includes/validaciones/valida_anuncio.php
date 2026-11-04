<?php
require_once __DIR__ . '/../lib/funciones.php';


function validarAnuncio(array $datos): array {
    $errores = [];

    
    $titulo       = trim($datos['titulo'] ?? '');
    $precio       = trim($datos['precio'] ?? '');
    $ciudad       = trim($datos['ciudad'] ?? '');
    $pais         = trim($datos['pais'] ?? '');
    $descripcion  = trim($datos['descripcion'] ?? '');
    $tipoVivienda = trim($datos['tipo_vivienda'] ?? '');
    $tipoAnuncio  = trim($datos['tipo_anuncio'] ?? '');
    $superficie   = trim($datos['superficie'] ?? '');
    $habitaciones = trim($datos['habitaciones'] ?? '');
    $banos        = trim($datos['banos'] ?? '');

    //validacion del titulo 
    if ($titulo === '') {
        $errores[] = 'El título del anuncio es obligatorio.';
    } elseif (mb_strlen($titulo) < 5) {
        $errores[] = 'El título debe tener al menos 5 caracteres.';
    }

    //validacion de precio 
    if ($precio === '') {
        $errores[] = 'El precio es obligatorio.';
    } elseif (!is_numeric($precio) || (float)$precio <= 0) {
        $errores[] = 'El precio debe ser un número mayor que cero.';
    }

    //validacion de la localizacion 
    if ($ciudad === '') {
        $errores[] = 'La ciudad es obligatoria.';
    }

    //validacion de la descripcion 
    if ($descripcion === '') {
        $errores[] = 'La descripción es obligatoria.';
    } elseif (mb_strlen($descripcion) < 10) {
        $errores[] = 'La descripción debe tener al menos 10 caracteres.';
    }

    //validacion de las listas desplegbles 
    $tiposViviendaValidos = ['Piso', 'Chalet', 'Ático', 'Estudio', 'Casa', 'Duplex'];
    if ($tipoVivienda !== '' && !in_array($tipoVivienda, $tiposViviendaValidos, true)) {
        $errores[] = 'El tipo de vivienda seleccionado no es válido.';
    }

    $tiposAnuncioValidos = ['Venta', 'Alquiler', 'Alquiler con opción a compra'];
    if ($tipoAnuncio !== '' && !in_array($tipoAnuncio, $tiposAnuncioValidos, true)) {
        $errores[] = 'El tipo de anuncio seleccionado no es válido.';
    }

    
    if ($superficie !== '' && (!is_numeric($superficie) || (int)$superficie <= 0)) {
        $errores[] = 'La superficie debe ser un número entero positivo.';
    }

    if ($habitaciones !== '' && (!is_numeric($habitaciones) || (int)$habitaciones < 0)) {
        $errores[] = 'El número de habitaciones no puede ser negativo.';
    }

    if ($banos !== '' && (!is_numeric($banos) || (int)$banos < 0)) {
        $errores[] = 'El número de baños no puede ser negativo.';
    }

    return $errores;
}