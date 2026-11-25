<?php
require_once __DIR__ . '/../bootstrap.php';

//esta es una página que no es visible para el usuario
//controla el acceso a la parte privada
require_once __DIR__ . '/../servicios/autentificacion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('index.php');
}

$email     = post('login_email');
$password  = post('login_password');
$resultado = autenticarUsuario($email, $password);

if ($resultado['error'] !== null) {
    flash_set('error', $resultado['error']);
    flash_set('email_previo', $email);
    redirigir('index.php');
}

$emailUsuario   = $resultado['usuario']['email'];
$quiereRecordar = isset($_POST['recordarme']) && $_POST['recordarme'] === '1';

//cookie "recordarme" que pueda existir de un acceso anterior (aunque se cerrase la sesión)
$tokenAnterior    = (isset($_COOKIE['recordarme']) && is_string($_COOKIE['recordarme'])) ? $_COOKIE['recordarme'] : null;
$registroAnterior = registroCookieRecordarme();
$mismoUsuario     = $registroAnterior !== null
    && strcasecmp((string)($registroAnterior['email'] ?? ''), $emailUsuario) === 0;

if (!$quiereRecordar) {
    //el usuario no quiere que se le recuerde: se revoca el token y se eliminan las cookies de la aplicación
    if ($tokenAnterior !== null) {
        try {
            eliminarTokenRecordarme($tokenAnterior);
        } catch (RuntimeException $error) {
            error_log($error->getMessage());
        }
    }
    borrarCookiesApp();
}

if ($quiereRecordar) {
    try {
        if ($mismoUsuario) {
            //mismo usuario que cerró sesión antes: se reutiliza la cookie y se renuevan sus 90 días
            $token = $tokenAnterior;
            renovarTokenRecordatorio($token);
        } else {
            //otro usuario en el mismo equipo: se revoca el token anterior y se empieza de cero
            if ($tokenAnterior !== null) {
                try {
                    eliminarTokenRecordarme($tokenAnterior);
                } catch (RuntimeException $error) {
                    error_log($error->getMessage());
                }
            }
            borrarCookiesApp();
            $token = crearTokenRecordatorio($emailUsuario);
        }
        setcookie('recordarme', $token, opcionesCookie(time() + (RECORDAR_DIAS * 24 * 60 * 60)));

        //con cookies aceptadas se restaura el estilo guardado en el perfil del usuario
        if (!empty($resultado['usuario']['estilo'])) {
            $_SESSION['estilo'] = $resultado['usuario']['estilo'];
        }
        activarRecordarme($emailUsuario);
    } catch (RuntimeException $error) {
        error_log($error->getMessage());
        flash_set('exito', 'Has iniciado sesión, pero no se pudo guardar el acceso para próximas visitas.');
    }
}

redirigir('index_sesion.php');
