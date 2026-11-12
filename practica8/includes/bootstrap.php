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

// Guarda un mensaje temporal en la sesión
// $clave es el identificador ('error','exito','aviso'...)
// $valor es el contenido real que se muestra ("Contraseña incorrecta", "El anuncio se ha guardado con éxito")
function flash_set($clave, $valor) {
    $_SESSION['flash'][$clave] = $valor;
}

// Recupera un mensaje temporal y lo destruye para que no vuelva a aparecer
function flash_get($clave) {
    if (isset($_SESSION['flash'][$clave])) {
        $valor = $_SESSION['flash'][$clave]; // Lo guarda en una variable dentro de la función
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
// Lee la cookie, busca el token en el servidor y autocompleta el inicio de sesión

$cookieValida = false; // Inicializamos por defecto para evitar warnings

// Si no hay sesión activa, pero el navegador nos trae la cookie de "recordarme"
if (!isset($_SESSION['usuario']) && isset($_COOKIE['recordarme'])) {
    $ruta_archivo = __DIR__ . '/../datos/recordar.json';
    
    // Si el archivo no existe, registramos el error y salimos
    if (!file_exists($ruta_archivo)) {
        error_log('No se encontró el archivo de recordatorio de acceso.');
    } else {
        // Leemos el contenido del archivo
        $contenido = file_get_contents($ruta_archivo);
        
        // Transformamos el JSON a array (si falla la lectura, ponemos null)
        if ($contenido === false) {
            $datos = null;
        } else {
            $datos = json_decode($contenido, true);
        }

        // Si los datos no tienen estructura de array válida, los inicializamos vacíos
        if (!is_array($datos)) {
            error_log('No se pudieron leer datos válidos de recordar.json (No es un array).');
            $datos = [];
        }
        
        // Encriptamos el token de la cookie para poder compararlo
        $hash_cookie = hash('sha256', $_COOKIE['recordarme']);
        
        // Recorremos la lista de usuarios de forma limpia
        foreach ($datos as $email => $registro) {
            
            // Validamos que el registro sea un array y tenga las claves necesarias
            $esRegistroValido = is_array($registro) 
                && isset($registro['token_hash'], $registro['caducidad']) 
                && is_string($registro['token_hash']);

            // Si es válido y los hashes coinciden...
            if ($esRegistroValido && hash_equals($registro['token_hash'], $hash_cookie)) {
                
                // Comprobamos que la caducidad sea un número válido y no haya expirado (90 días)
                $noHaCaducado = is_numeric($registro['caducidad']) && (int)$registro['caducidad'] > time();

                if ($noHaCaducado) {
                    require_once __DIR__ . '/lib/funciones.php';
                    $uDatos = buscarUsuario((string)$email);
                    
                    if ($uDatos === null) {
                        break;
                    }

                    session_regenerate_id(true);
                    $_SESSION['usuario'] = $email;

                    // Guardamos el estilo si existe
                    if (isset($uDatos['estilo'])) {
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
                
                break; // Rompemos el bucle porque ya encontramos al usuario
            }
        }
    }
}

// Si la cookie venía en el navegador pero no era válida (o expiró/falló), la borramos
if (isset($_COOKIE['recordarme']) && !$cookieValida && !isset($_SESSION['usuario'])) {
    setcookie('recordarme', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax'
    ]);
    unset($_COOKIE['recordarme']);
}