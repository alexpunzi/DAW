<?php

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


// ----- LÓGICA DE AUTO-LOGIN (Opción "Recordarme") -----
// Si no hay sesión activa, pero el navegador nos trae la cookie de "recordarme"
if (!isset($_SESSION['usuario']) && isset($_COOKIE['recordarme'])) {
    
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
                && isset($registro['token_hash'], $registro['caducidad'], $registro['ultima_visita'])
                && is_string($registro['token_hash'])
                && is_numeric($registro['ultima_visita'])
                && hash_equals($registro['token_hash'], $hash_cookie)) {
                
                // Comprobamos que no hayan pasado los 90 días
                if (is_numeric($registro['caducidad']) && (int)$registro['caducidad'] > time()) {
                    
                    $_SESSION['usuario'] = $email;

                    // Cargar y guardar el estilo del usuario registrado
                    require_once __DIR__ . '/lib/funciones.php';
                    $uDatos = buscarUsuario($email);
                    if ($uDatos !== null && isset($uDatos['estilo'])) {
                        $_SESSION['estilo'] = $uDatos['estilo'];
                    }
                    
                    // Preparamos el mensaje con la fecha de su última visita
                    $fecha_visita = date('d/m/Y \a \l\a\s H:i', (int)$registro['ultima_visita']);
                    flash_set('exito', "¡Bienvenido de nuevo! Tu última conexión fue el $fecha_visita.");
                    
                    // Actualizamos la fecha de su visita a HOY en el archivo JSON
                    $datos[$email]['ultima_visita'] = time();
                    $contenido = json_encode($datos, JSON_PRETTY_PRINT);
                    if ($contenido === false || file_put_contents($ruta_archivo, $contenido, LOCK_EX) === false) {
                        error_log('No se pudo actualizar la última visita en recordar.json.');
                    }
 
                }
                break; // Dejamos de buscar en la lista
            }
        }
    } else {
        error_log('No se encontró el archivo de recordatorio de acceso.');
    }
}