<?php

//Funcion que hace que la fecha que se pase por cadena se convierta en formato de fecha 
function formatearFechaHora(int $timestamp): string
{
    return date('d/m/Y \a \l\a\s H:i', $timestamp);
}

function saludoPorHora(): string
{
    $hora = (int)date('G');
    if ($hora >= 6 && $hora <= 11) {
        return 'Buenos días';
    }
    if ($hora >= 12 && $hora <= 15) {
        return 'Hola';
    }
    if ($hora >= 16 && $hora <= 19) {
        return 'Buenas tardes';
    }
    return 'Buenas noches';
}
