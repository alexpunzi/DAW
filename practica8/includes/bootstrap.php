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


// 4. LÓGICA DE AUTO-LOGIN (Opción "Recordarme")
// Si no hay sesión activa, pero el navegador nos trae la cookie de "recordarme"
if (!isset($_SESSION['usuario']) && isset($_COOKIE['recordarme'])) {
    
    $ruta_archivo = __DIR__ . '/../datos/recordar.json';
    
    if (file_exists($ruta_archivo)) {
        // Leemos nuestra libreta del servidor
        $datos = json_decode(file_get_contents($ruta_archivo), true) ?: [];
        
        // Encriptamos el token que nos trae la cookie para poder compararlo
        $hash_cookie = hash('sha256', $_COOKIE['recordarme']);
        
        // Buscamos al usuario en nuestra libreta
        foreach ($datos as $email => $registro) {
            
            // Si los hashes coinciden, es nuestro usuario
            if ($registro['token_hash'] === $hash_cookie) {
                
                // Comprobamos que no hayan pasado los 90 días
                if ($registro['caducidad'] > time()) {
                    
                    $_SESSION['usuario'] = $email;
                    
                    // Preparamos el mensaje con la fecha de su última visita
                    $fecha_visita = date('d/m/Y \a \l\a\s H:i', $registro['ultima_visita']);
                    flash_set('exito', "¡Bienvenido de nuevo! Tu última conexión fue el $fecha_visita.");
                    
                    // Actualizamos la fecha de su visita a HOY en el archivo JSON
                    $datos[$email]['ultima_visita'] = time();
                    file_put_contents($ruta_archivo, json_encode($datos, JSON_PRETTY_PRINT));
 
                }
                break; // Dejamos de buscar en la lista
            }
        }
    }
}