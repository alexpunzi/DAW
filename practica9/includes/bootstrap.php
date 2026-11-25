<?php

date_default_timezone_set('Europe/Madrid');

// Configuramos seguridad de la cookie de sesión antes de iniciarla
session_set_cookie_params([
    'httponly' => true, // Evita que JS acceda a la cookie
    'samesite' => 'Lax' // Previene ataques CSRF básicos
]);

// Iniciar sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../datos/conexion.php';

require_once __DIR__ . '/lib/flash.php';
require_once __DIR__ . '/lib/url.php';
require_once __DIR__ . '/lib/session.php';
require_once __DIR__ . '/lib/fechas.php';
require_once __DIR__ . '/lib/request.php';
require_once __DIR__ . '/lib/html.php';
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/lib/recordar.php';
require_once __DIR__ . '/repositorios/carga_anuncios.php';

recuperarSesionRecordada();