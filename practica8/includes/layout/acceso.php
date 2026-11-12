<?php
require_once __DIR__ . '/../lib/funciones.php';

// Los datos flash se consumen una sola vez en index.php.
$errorActual = $codigoError ?? null;
$emailPrevio = $emailPrevio ?? '';
$tieneError = ($errorActual === 'vacio' || $errorActual === 'credenciales');

// Definimos los mensajes específicos por campo 
$mensajeEmail = '';
$mensajePassword = '';

if ($errorActual === 'vacio') {
    $mensajeEmail = 'Este campo es obligatorio.';
    $mensajePassword = 'Este campo es obligatorio.';
} elseif ($errorActual === 'credenciales') {
    $mensajeEmail = 'El correo electrónico o la contraseña son incorrectos.';
    $mensajePassword = 'El correo electrónico o la contraseña son incorrectos.';
}
?>

<section id="acceso">
    <h2>Iniciar sesión</h2>

    <p><small>* Campos obligatorios</small></p>

    <form id="form_login" action="includes/procesadores/control_acceso.php" method="post" novalidate>

        <p>
            <label for="login_email">
                <span class="icono-unicode" aria-hidden="true">&#9993;</span>
                Correo electrónico *
            </label>
            <input 
                type="text" 
                id="login_email" 
                name="login_email"
                value="<?= e($emailPrevio); ?>"
                class="<?= $tieneError ? 'input-error' : ''; ?>"
                data-ayuda="ayuda_login_email" 
                aria-describedby="ayuda_login_email"
            >
            <small id="ayuda_login_email" class="ayuda">
                Ejemplo: usuario@ejemplo.com
            </small>

            <!-- Mensaje de error específico para el email -->
            <?php if ($tieneError): ?>
                <span class="error-text" style="color: red; display: block; font-size: 0.85em; margin-top: 4px;">
                    <?= e($mensajeEmail); ?>
                </span>
            <?php endif; ?>
        </p>

        <p>
            <label for="login_password">
                <span class="material-symbols-outlined" aria-hidden="true">lock</span>
                Contraseña *
            </label>
           
            <input 
                type="password" 
                id="login_password" 
                name="login_password"
                class="<?= $tieneError ? 'input-error' : ''; ?>"
            >

            <!-- Mensaje de error específico para la contraseña -->
            <?php if ($tieneError): ?>
                <span class="error-text" style="color: red; display: block; font-size: 0.85em; margin-top: 4px;">
                    <?= e($mensajePassword); ?>
                </span>
            <?php endif; ?>
        </p>

        <p class="opcion-recordarme">
            <label for="recordarme">
                <span class="material-symbols-outlined" aria-hidden="true">devices</span>
                <span>Recordarme en este equipo durante 90 días</span>
            </label>
            <input type="checkbox" id="recordarme" name="recordarme" value="1">
        </p>

        <button type="submit">
            <span class="material-symbols-outlined" aria-hidden="true">login</span>
            Entrar
        </button>

    </form>
</section>