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



// ruta base de la aplicación 
function urlBase(): string {
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $dir = preg_replace('#/includes/procesadores$#', '', $dir);
    return rtrim($dir, '/');
}

// Redirección en el servidor con URL absoluta
// Si hay un usuario identificado, se conserva automáticamente en la URL.
function redirigir(string $pagina, array $params = []): void {
    $usuario = usuarioActual();
    if ($usuario !== null && !isset($params['usuario'])) {
        $params['usuario'] = $usuario['email'];
    }
    $esquema = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $url = $esquema . '://' . $_SERVER['HTTP_HOST'] . urlBase() . '/' . ltrim($pagina, '/');
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

// Devuelve los datos del usuario identificado o null
function usuarioActual(): ?array {
    static $actual = false;
    if ($actual !== false) {
        return $actual;
    }
    $actual = null;
    if (!empty($_SESSION['usuario'])) {
        $actual = buscarUsuario((string)$_SESSION['usuario']);
    }
    if ($actual !== null) {
        return $actual;
    }
    $email = get('usuario');
    if ($email !== '') {
        $actual = buscarUsuario($email);
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
        redirigir('index.php', ['error' => 'acceso']); //redirige al index.pho sin sesion 
    }
    return $usuario;
}

// construye un enlace conservando el usuario identificado (si lo hay)
function urlPrivada(string $pagina, array $params = []): string {
    $usuario = usuarioActual();
    if ($usuario !== null) {
        $params['usuario'] = $usuario['email'];
    }
    return $pagina . (!empty($params) ? '?' . http_build_query($params) : '');
}

// Campo oculto para los formularios GET que deben conservar el usuario
function campoUsuario(): string {
    $usuario = usuarioActual();
    if ($usuario === null) {
        return '';
    }
    return '<input type="hidden" name="usuario" value="' . e($usuario['email']) . '">';
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

function registrarTokenEnServidor($email, $token) {
    
    // Preparar el archivo
    $ruta_archivo = __DIR__ . '/../../datos/recordar.json';

    // Leer los datos que ya existen
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
    $datos[$email] = $registro;

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