<?php

const TIPOS_VIVIENDA = ['Piso', 'Chalet', 'Ático', 'Estudio', 'Casa', 'Duplex'];
const TIPOS_ANUNCIO  = ['Venta', 'Alquiler', 'Alquiler con opción a compra'];

// Valida el formulario de crear anuncio y devuelve la lista de errores.
function validarAnuncio(array $datos): array {
    $errores = []; //creamos un array porque pueden haber muchos errores y se deben mostrar todos 

    $campo = function (string $nombre) use ($datos): string {
        return isset($datos[$nombre]) && is_scalar($datos[$nombre]) ? trim((string)$datos[$nombre]) : '';
    };

    $titulo       = $campo('titulo');
    $precio       = $campo('precio');
    $ciudad       = $campo('ciudad');
    $pais         = $campo('pais');
    $descripcion  = $campo('descripcion');
    $tipoVivienda = $campo('tipo_vivienda');
    $tipoAnuncio  = $campo('tipo_anuncio');
    $superficie   = $campo('superficie');
    $habitaciones = $campo('habitaciones');
    $banos        = $campo('banos');
    $anyo         = $campo('ano_construccion');

    if ($titulo === '') {
        $errores[] = 'El título del anuncio es obligatorio.';
    } elseif (mb_strlen($titulo) < 5) {
        $errores[] = 'El título debe tener al menos 5 caracteres.';
    }


    if ($precio === '') {
        $errores[] = 'El precio es obligatorio.';
    } elseif (!is_numeric($precio) || (float)$precio <= 0) {
        $errores[] = 'El precio debe ser un número mayor que cero.';
    }


    if ($ciudad === '') {
        $errores[] = 'La ciudad es obligatoria.';
    }


    if ($pais === '') {
        $errores[] = 'El país es obligatorio.';
    } elseif (!array_key_exists($pais, paises())) {
        $errores[] = 'El país seleccionado no es válido.';
    }


    if ($descripcion === '') {
        $errores[] = 'La descripción es obligatoria.';
    } elseif (mb_strlen($descripcion) < 10) {
        $errores[] = 'La descripción debe tener al menos 10 caracteres.';
    }


    if ($tipoAnuncio === '') {
        $errores[] = 'Debes seleccionar el tipo de anuncio.';
    } elseif (!in_array($tipoAnuncio, TIPOS_ANUNCIO, true)) {
        $errores[] = 'El tipo de anuncio seleccionado no es válido.';
    }


    if ($tipoVivienda === '') {
        $errores[] = 'Debes seleccionar el tipo de vivienda.';
    } elseif (!in_array($tipoVivienda, TIPOS_VIVIENDA, true)) {
        $errores[] = 'El tipo de vivienda seleccionado no es válido.';
    }


    if ($superficie !== '' && (!ctype_digit($superficie) || (int)$superficie <= 0)) {
        $errores[] = 'La superficie debe ser un número entero positivo.';
    }


    if ($habitaciones !== '' && !ctype_digit($habitaciones)) {
        $errores[] = 'El número de habitaciones debe ser un número entero no negativo.';
    }


    if ($banos !== '' && !ctype_digit($banos)) {
        $errores[] = 'El número de baños debe ser un número entero no negativo.';
    }


    if ($anyo !== '' && (!ctype_digit($anyo) || (int)$anyo < 1800 || (int)$anyo > (int)date('Y'))) {
        $errores[] = 'El año de construcción debe estar entre 1800 y ' . date('Y') . '.';
    }

    return $errores;
}
