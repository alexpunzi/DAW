<?php

//valida el formulariuo de contacto y el envio de mensajes a anunciantes 
function validarMensaje($tipoMensaje, $textoMensaje) {
    $errores = [];
    $tiposPermitidos = ['informacion', 'cita', 'oferta', 'reclamacion'];

    if (!in_array($tipoMensaje, $tiposPermitidos, true)) {
        $errores[] = 'El tipo de mensaje seleccionado no es válido.';
    }

    if (trim($textoMensaje) === '') {
        $errores[] = 'El texto del mensaje no puede estar vacío ni contener solo espacios.';
    }

    return $errores;
}