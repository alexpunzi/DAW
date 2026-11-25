<?php

require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/fechas.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../repositorios/carga_usuarios.php';

const RECORDAR_DIAS = 90;
const RECORDAR_RUTA = __DIR__ . '/../../datos/recordar.json';

function opcionesCookie(int $expira): array
{
    return [
        'expires'  => $expira,
        'path'     => '/',
        'httponly' => true,
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
    ];
}

/** El usuario "acepta las cookies" únicamente si ha marcado "Recordarme" y su sesión lo refleja. */
function cookiesAceptadas(): bool
{
    return !empty($_SESSION['usuario']) && !empty($_SESSION['recordarme_activo']);
}

function borrarCookie(string $nombre): void
{
    if (!headers_sent()) {
        setcookie($nombre, '', opcionesCookie(time() - 3600));
    }
    unset($_COOKIE[$nombre]);
}

/** Elimina todas las cookies propias de la aplicación (salvo la de sesión de PHP). */
function borrarCookiesApp(): void
{
    foreach (['recordarme', 'ultimos_visitados', 'ultima_visita'] as $c) {
        if (isset($_COOKIE[$c])) {
            borrarCookie($c);
        }
    }
}

function leerTokens(): array
{
    if (!file_exists(RECORDAR_RUTA)) {
        return [];
    }
    $contenido = file_get_contents(RECORDAR_RUTA);
    if ($contenido === false) {
        throw new RuntimeException('No se pudo leer el archivo de recordatorio.');
    }
    if (trim($contenido) === '') {
        return [];
    }
    $datos = json_decode($contenido, true);
    if (!is_array($datos)) {
        throw new RuntimeException('El archivo de recordatorio no contiene datos válidos.');
    }
    // Se descartan los tokens caducados
    return array_filter($datos, static function ($r) {
        return is_array($r) && isset($r['caducidad']) && (int)$r['caducidad'] > time();
    });
}

function guardarTokens(array $tokens): void
{
    $contenido = json_encode($tokens, JSON_PRETTY_PRINT);
    if ($contenido === false || file_put_contents(RECORDAR_RUTA, $contenido, LOCK_EX) === false) {
        throw new RuntimeException('No se pudo guardar el recordatorio de acceso.');
    }
}

/** Guarda el token (solo su hash) asociado al email; admite varios equipos/usuarios a la vez. */
function registrarTokenEnServidor(string $email, string $token): void
{
    $tokens = leerTokens();
    $tokens[hash('sha256', $token)] = [
        'email'     => $email,
        'caducidad' => time() + (RECORDAR_DIAS * 24 * 60 * 60),
    ];
    guardarTokens($tokens);
}

function eliminarTokenRecordarme(string $token): void
{
    $tokens = leerTokens();
    $hash = hash('sha256', $token);
    if (isset($tokens[$hash])) {
        unset($tokens[$hash]);
        guardarTokens($tokens);
    }
}

function eliminarCookieRecordarme(): void
{
    borrarCookie('recordarme');
}

/**
 * Cierra la "sesión recordada" SIN borrar la cookie: el token sigue guardado en el servidor
 * (y en el navegador) hasta que pasen los 90 días, pero marcado como cerrado para que no
 * vuelva a iniciar sesión automáticamente.
 */
function marcarTokenSesionCerrada(string $token): void
{
    $tokens = leerTokens();
    $hash = hash('sha256', $token);
    if (isset($tokens[$hash])) {
        $tokens[$hash]['cerrada'] = true;
        guardarTokens($tokens);
    }
}

/** Vuelve a activar un token existente y renueva sus 90 días de caducidad. */
function renovarTokenRecordatorio(string $token): void
{
    $tokens = leerTokens();
    $hash = hash('sha256', $token);
    if (isset($tokens[$hash])) {
        $tokens[$hash]['cerrada']   = false;
        $tokens[$hash]['caducidad'] = time() + (RECORDAR_DIAS * 24 * 60 * 60);
        guardarTokens($tokens);
    }
}

/** Registro del servidor asociado a la cookie "recordarme" (aunque la sesión esté cerrada), o null. */
function registroCookieRecordarme(): ?array
{
    if (!isset($_COOKIE['recordarme']) || !is_string($_COOKIE['recordarme'])) {
        return null;
    }
    try {
        $tokens = leerTokens();
    } catch (RuntimeException $error) {
        error_log($error->getMessage());
        return null;
    }
    $registro = $tokens[hash('sha256', $_COOKIE['recordarme'])] ?? null;
    return is_array($registro) ? $registro : null;
}

/** Email del usuario recordado en este equipo (para rellenar el formulario de acceso), o null. */
function emailRecordado(): ?string
{
    $registro = registroCookieRecordarme();
    $email = $registro !== null ? (string)($registro['email'] ?? '') : '';
    return $email !== '' ? $email : null;
}

/**
 * Activa el modo "recordado" en la sesión actual: guarda la última sesión previa para mostrarla
 * (una sola vez, en la página de inicio) y registra la sesión actual.
 */
function activarRecordarme(string $email): void
{
    $_SESSION['recordarme_activo'] = true;
    unset($_SESSION['ultima_sesion_previa']);
    try {
        $previa = registrarUltimaSesion($email);
        if ($previa !== null) {
            $_SESSION['ultima_sesion_previa'] = $previa;
        }
    } catch (RuntimeException $error) {
        error_log($error->getMessage());
        flash_set('exito', 'Has iniciado sesión, pero no se pudo consultar ni guardar la hora de tu última sesión.');
    }
}

function recuperarSesionRecordada(): bool
{
    if (isset($_SESSION['usuario']) || !isset($_COOKIE['recordarme']) || !is_string($_COOKIE['recordarme'])) {
        return false;
    }

    $recuperada = false;
    try {
        $tokens = leerTokens();
        $registro = $tokens[hash('sha256', $_COOKIE['recordarme'])] ?? null;

        // El usuario cerró sesión de forma voluntaria: la cookie se conserva (90 días),
        // pero no se inicia sesión automáticamente.
        if ($registro !== null && !empty($registro['cerrada'])) {
            return false;
        }

        $usuario = $registro !== null ? buscarUsuario((string)$registro['email']) : null;

        if ($usuario !== null) {
            session_regenerate_id(true);
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['usuario']    = $usuario['email'];
            $_SESSION['nombre']     = $usuario['nombre'];
            $_SESSION['id_estilo']  = $usuario['id_estilo'];
            if (!empty($usuario['estilo'])) {
                $_SESSION['estilo'] = $usuario['estilo'];
            }
            activarRecordarme($usuario['email']);
            $recuperada = true;
        }
    } catch (RuntimeException $error) {
        error_log($error->getMessage());
    }

    if (!$recuperada) {
        eliminarCookieRecordarme();
    }

    return $recuperada;
}

/** Añade un anuncio a la cookie de últimos visitados (solo si el usuario aceptó las cookies). */
function registrarAnuncioVisitado(int $id): void
{
    if (!cookiesAceptadas()) {
        return;
    }
    $visitados = leerVisitados();
    $visitados = array_values(array_diff($visitados, [$id]));
    array_unshift($visitados, $id);
    $visitados = array_slice($visitados, 0, 4);
    $json = json_encode($visitados);
    setcookie('ultimos_visitados', $json, opcionesCookie(time() + (RECORDAR_DIAS * 24 * 60 * 60)));
    $_COOKIE['ultimos_visitados'] = $json;
}

function leerVisitados(): array
{
    if (!cookiesAceptadas() || empty($_COOKIE['ultimos_visitados']) || !is_string($_COOKIE['ultimos_visitados'])) {
        return [];
    }
    $raw = json_decode($_COOKIE['ultimos_visitados'], true);
    $ids = [];
    if (is_array($raw)) {
        foreach ($raw as $v) {
            $id = leerId($v);
            if ($id !== null && !in_array($id, $ids, true)) {
                $ids[] = $id;
            }
        }
    }
    return $ids;
}
