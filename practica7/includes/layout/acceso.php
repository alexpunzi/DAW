<?php

require_once __DIR__ . '/../lib/funciones.php';
?>

<section id="acceso">
    <h2>Iniciar sesión</h2>

    <p><small>* Campos obligatorios</small></p>

    <form id="form_login" action="/daw/practica7/procesadores/control_acceso.php" method="post" novalidate>

        <p>
            <label for="login_email">
                <span class="icono-unicode" aria-hidden="true">&#9993;</span>
                Correo electrónico *
            </label>
            <!-- Si venimos de un intento fallido, recuperamos el email tecleado con get('email_previo') -->
            <input 
                type="text" 
                id="login_email" 
                name="login_email"
                value="<?= e(get('email_previo')); ?>"
                data-ayuda="ayuda_login_email" 
                aria-describedby="ayuda_login_email"
            >
            <small id="ayuda_login_email" class="ayuda">
                Ejemplo: usuario@ejemplo.com
            </small>
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
            >
        </p>

        <button type="submit">
            <span class="material-symbols-outlined" aria-hidden="true">login</span>
            Entrar
        </button>

    </form>
</section>