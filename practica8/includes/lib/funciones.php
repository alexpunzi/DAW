<?php

//aqui se declaran las funciones auxiliares que son COMUNES en cada pagina 

// true = se cargan los scripts de validación JavaScript.
// Para comprobar las validaciones de PHP debe estar a false.
if (!defined('VALIDACION_JS')) {
    define('VALIDACION_JS', false);
}

require_once __DIR__ . '/datos.php';

// Escapa caracteres especiales de HTML para prevenir XSS
function e($texto) {
    if ($texto === null) {
        return '';
    }
    return htmlspecialchars((string)$texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// lee un parámetro de $_POST 
function post($clave) {
    return (isset($_POST[$clave]) && is_scalar($_POST[$clave])) ? trim((string)$_POST[$clave]) : '';
}

// lee un parámetro de $_GET 
function get($clave) {
    return (isset($_GET[$clave]) && is_scalar($_GET[$clave])) ? trim((string)$_GET[$clave]) : '';
}


function euros($cantidad) {
    return number_format((float)$cantidad, 2, ',', '.') . ' €';
}


function campoVacio($texto) {
    return trim((string)$texto) === ''; //comprueba si un texto esta vacio o si tiene espacios en blanco hechos con tabuladores 
}


function paises(): array {
    return ['argentina' => 'Argentina', 'espana' => 'España', 'mexico' => 'México']; //devuelve los paises disponibles en el seleccionado 
}

// Devuelve los scripts de validación solo si VALIDACION_JS está activada
function scriptsValidacion(array $scripts): array {
    return VALIDACION_JS ? $scripts : [];
}

function registrarUltimaSesion(string $email): ?int {
    $rutaArchivo = __DIR__ . '/../../datos/ultimas_sesiones.json';
    $sesiones = [];

    if (file_exists($rutaArchivo)) {
        $contenido = file_get_contents($rutaArchivo);
        if ($contenido === false) {
            throw new RuntimeException('No se pudo leer el historial de sesiones.');
        }
        $sesiones = json_decode($contenido, true);
        if (!is_array($sesiones)) {
            throw new RuntimeException('El historial de sesiones no contiene datos válidos.');
        }
    }

    $ultimaSesion = $sesiones[$email] ?? null;
    $ultimaSesion = is_numeric($ultimaSesion) && (int)$ultimaSesion > 0
        ? (int)$ultimaSesion
        : null;

    $sesiones[$email] = time();
    $contenido = json_encode($sesiones, JSON_PRETTY_PRINT);
    if ($contenido === false || file_put_contents($rutaArchivo, $contenido, LOCK_EX) === false) {
        throw new RuntimeException('No se pudo guardar el historial de sesiones.');
    }

    return $ultimaSesion;
}

function formatearFechaHora(int $timestamp): string {
    return date('d/m/Y \a \l\a\s H:i', $timestamp);
}

// ruta base de la aplicación 
function urlBase(): string {
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $dir = preg_replace('#/includes/procesadores$#', '', $dir);
    return rtrim($dir, '/');
}

// Redirige al usuario a una página determinada dentro del sitio web
function redirigir(string $pagina, array $params = []): void {
    unset($params['usuario']);
    // Detecta protocolo y construye la URL
    $esquema = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $url = $esquema . '://' . $_SERVER['HTTP_HOST'] . urlBase() . '/' . ltrim($pagina, '/');

    // Si se enviaron parámetros GET adicionales, los concatena a la URL
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    // Envía la cabecera HTTP al navegador
    header('Location: ' . $url);
    exit;
}

// Obtiene los datos del usuario actualmente identificado
function usuarioActual(): ?array {
    // Almacena en caché el resultado dentro de la misma petición y evitar múltiples búsquedas
    static $actual = false;

    // Atajo por si ya se ha determinado el usuario en esta petición
    if ($actual !== false) { 
        return $actual;
    }

    // Si es la primera vez que se llama a la función en la página
    $actual = null;

    // La identidad procede únicamente de la sesión del servidor.
    if (!empty($_SESSION['usuario'])) {
        $actual = buscarUsuario((string)$_SESSION['usuario']);
    }
    
    return $actual;
}

function estaIdentificado(): bool {
    return usuarioActual() !== null; //comprueba si el usuario esta identificado o no 
}

// esta funcion es ara páginas solo de la parte privada, si no hay usuario, vuelve al formulario de acceso
function requiereUsuario(): array {
    $usuario = usuarioActual();
    if ($usuario === null) { // si no hay usuario 
        flash_set('error', 'acceso');
        redirigir('index.php');
    }
    return $usuario;
}

// Construye enlaces hacia páginas privadas
function urlPrivada(string $pagina, array $params = []): string {
    unset($params['usuario']);
    return $pagina . (!empty($params) ? '?' . http_build_query($params) : '');
}

// Enlace a la página de inicio según el usuario esté identificado o no
function urlInicio(): string {
    return estaIdentificado() ? urlPrivada('index_sesion.php') : 'index.php';
}


function obtenerErrorCampo($nombreCampo, array $errores) {
    foreach ($errores as $err) {
        $errLower = mb_strtolower($err);
        if (str_contains($errLower, mb_strtolower($nombreCampo))) {
            return $err;
        }
    }
    return '';
}

function saludoPorHora(): string {
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

function registrarTokenEnServidor($email, $token) {
    
    // Preparar el archivo
    $ruta_archivo = __DIR__ . '/../../datos/recordar.json';

    // Solo se mantiene un usuario recordado a la vez.
    $datos = [];
    if (file_exists($ruta_archivo)) {
        $contenido = file_get_contents($ruta_archivo);
        if ($contenido === false) {
            throw new RuntimeException('No se pudo leer el archivo de recordatorio.');
        }
        $datos = json_decode($contenido, true);
        if (!is_array($datos)) {
            throw new RuntimeException('El archivo de recordatorio no contiene datos válidos.');
        }
    }

    $ultimaVisita = $datos[$email]['ultima_visita'] ?? null;
    $ultimaVisita = is_numeric($ultimaVisita) && (int)$ultimaVisita > 0
        ? (int)$ultimaVisita
        : null;

    // Proteger la clave (hash)
    $token_hash = hash('sha256', $token);

    // Preparamos los datos del usuario
    $registro = [
        'email'         => $email,
        'token_hash'    => $token_hash,
        'caducidad'     => time() + (90 * 24 * 60 * 60), // 90 días
        'ultima_visita' => time()                        // Fecha actual (timestamp)
    ];

    // Si el usuario ya tenía un token viejo de otro día, se sobrescribe
    $datos = [$email => $registro];

    // Guardar los cambios en el disco duro
    $contenido = json_encode($datos, JSON_PRETTY_PRINT);
    if ($contenido === false || file_put_contents($ruta_archivo, $contenido, LOCK_EX) === false) {
        throw new RuntimeException('No se pudo guardar el recordatorio de acceso.');
    }

    return $ultimaVisita;
}

function eliminarTokenRecordarme(string $token): void {
    $ruta_archivo = __DIR__ . '/../../datos/recordar.json';
    if (!file_exists($ruta_archivo)) {
        return;
    }

    $contenido = file_get_contents($ruta_archivo);
    if ($contenido === false) {
        throw new RuntimeException('No se pudo leer el archivo de recordatorio.');
    }
    $datos = json_decode($contenido, true);
    if (!is_array($datos)) {
        throw new RuntimeException('El archivo de recordatorio no contiene datos válidos.');
    }

    $hashToken = hash('sha256', $token);
    foreach ($datos as $email => $registro) {
        if (isset($registro['token_hash'])
            && is_string($registro['token_hash'])
            && hash_equals($registro['token_hash'], $hashToken)) {
            unset($datos[$email]);
            $contenido = json_encode($datos, JSON_PRETTY_PRINT);
            if ($contenido === false || file_put_contents($ruta_archivo, $contenido, LOCK_EX) === false) {
                throw new RuntimeException('No se pudo eliminar el recordatorio de acceso.');
            }
            return;
        }
    }
}