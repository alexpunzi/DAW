<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../servicios/autentificacion.php';

if (!cerrarSesionActual()) {
    flash_set('error', 'Se cerró la sesión, pero no se pudo revocar el acceso guardado.');
}

redirigir('index.php');