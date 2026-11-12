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


// ----- FUNCIONES PARA MENSAJES FLASH (FLASHDATA) -----

/**
 * Guarda un mensaje temporal en la sesión.
 * $clave Identificador del mensaje (ej: 'error', 'exito').
 * $valor El texto del mensaje.
 */
function flash_set($clave, $valor) {
    $_SESSION['flash'][$clave] = $valor;
}

// Recupera un mensaje temporal y lo destruye para que no vuelva a aparecer
function flash_get($clave) {
    if (isset($_SESSION['flash'][$clave])) {
        $valor = $_SESSION['flash'][$clave];
        unset($_SESSION['flash'][$clave]); // Se elimina tras leerlo
        return $valor;
    }
    return null;
}

// Recupera y elimina de una vez varios valores flash.
function flash_get_many(array $claves): array {
    $valores = [];
    foreach ($claves as $clave) {
        $valores[$clave] = flash_get($clave);
    }
    return $valores;
}


// ----- LÓGICA DE AUTO-LOGIN (Opción "Recordarme") -----
// Si no hay sesión activa, pero el navegador nos trae la cookie de "recordarme"
if (!isset($_SESSION['usuario']) && isset($_COOKIE['recordarme'])) {
    $cookieValida = false;
    $ruta_archivo = __DIR__ . '/../datos/recordar.json';
    
    if (file_exists($ruta_archivo)) {
        // Leemos nuestra libreta del servidor
        $contenido = file_get_contents($ruta_archivo);
        $datos = $contenido === false ? null : json_decode($contenido, true);
        if ($contenido === false || !is_array($datos)) {
            error_log('No se pudieron leer datos válidos de recordar.json.');
            $datos = [];
        }
        
        // Encriptamos el token que nos trae la cookie para poder compararlo
        $hash_cookie = hash('sha256', $_COOKIE['recordarme']);
        
        // Buscamos al usuario en nuestra libreta
        foreach (is_array($datos) ? $datos : [] as $email => $registro) {
            
            // Si los hashes coinciden, es nuestro usuario
            if (is_array($registro)
                && isset($registro['token_hash'], $registro['caducidad'])
                && is_string($registro['token_hash'])
                && hash_equals($registro['token_hash'], $hash_cookie)) {
                
                // Comprobamos que no hayan pasado los 90 días
                if (is_numeric($registro['caducidad']) && (int)$registro['caducidad'] > time()) {
                    require_once __DIR__ . '/lib/funciones.php';
                    $uDatos = buscarUsuario((string)$email);
                    if ($uDatos === null) {
                        break;
                    }

                    session_regenerate_id(true);
                    $_SESSION['usuario'] = $email;

                    // Cargar y guardar el estilo del usuario registrado
                    if ($uDatos !== null && isset($uDatos['estilo'])) {
                        $_SESSION['estilo'] = $uDatos['estilo'];
                    }
                    
                    $cookieValida = true;

                    try {
                        $ultimaSesion = registrarUltimaSesion((string)$email);
                        if ($ultimaSesion === null && isset($registro['ultima_visita']) && is_numeric($registro['ultima_visita'])) {
                            $ultimaSesion = (int)$registro['ultima_visita'];
                        }
                        if ($ultimaSesion !== null) {
                            $nombre = $uDatos['nombre'];
                            flash_set('exito', "¡Bienvenido de nuevo, $nombre! Tu última conexión fue el " . formatearFechaHora($ultimaSesion) . '.');
                        }
                    } catch (RuntimeException $error) {
                        error_log($error->getMessage());
                        flash_set('exito', 'Has iniciado sesión, pero no se pudo consultar ni guardar la hora de tu última sesión.');
                    }
 
                }
                break; // Dejamos de buscar en la lista
            }
        }
    } else {
        error_log('No se encontró el archivo de recordatorio de acceso.');
    }

    if (!$cookieValida) {
        setcookie('recordarme', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true,
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'samesite' => 'Lax'
        ]);
        unset($_COOKIE['recordarme']);
    }
}