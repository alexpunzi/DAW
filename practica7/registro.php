<?php

require_once "includes/lib/funciones.php";

//configuracion de las vartiables especificas 
$title        = "Registro";
$esPartePrivada = false; // muestra la navegación pública 

// Mantenemos los scripts de validación JavaScript frontend
$scriptsExtra = [
    'js/utilidades.js',
    'js/validadores.js',
    'js/registro.js'
];


require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";


$exito        = get('exito');
$erroresRaw   = get('errores');
$listaErrores = !empty($erroresRaw) ? explode(',', $erroresRaw) : [];

// Mapeo de códigos de error a mensajes legibles
$mensajesError = [
    'e_usuario_vacio'     => 'El nombre de usuario es obligatorio.',
    'e_password_vacio'    => 'La contraseña es obligatoria.',
    'e_password_mismatch' => 'Las contraseñas introducidas no coinciden.'
];
?>

<h2>Registro de Usuario</h2>

<p><small>* Campos obligatorios</small></p>


<?php if ($exito === '1'): ?>
    <section class="alerta-exito" role="alert">
        <p>¡Registro completado con éxito! Bienvenido/a, <strong><?= e(get('nombre')); ?></strong>.</p>
    </section>
<?php endif; ?>


<?php if (!empty($listaErrores)): ?>
    <section class="alerta-error" role="alert">
        <p><strong>Por favor, corrige los siguientes errores:</strong></p>
        <ul>
            <?php foreach ($listaErrores as $codError): ?>
                <li><?= e($mensajesError[$codError] ?? 'Se ha producido un error en los datos introducidos.'); ?></li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endif; ?>


<form id="form_registro" action="procesadores/respuesta_registro.php" method="post" enctype="multipart/form-data" novalidate>

    <p>
        <label for="nombre">
            <span class="material-symbols-outlined" aria-hidden="true">person</span>
            Nombre *
        </label>
        <input type="text" id="nombre" name="nombre"
            value="<?= e(get('nombre')); ?>"
            data-ayuda="ayuda_nombre" aria-describedby="ayuda_nombre">
        <small id="ayuda_nombre" class="ayuda">
            3 a 15 caracteres: letras inglesas y números. No puede empezar por número.
        </small>
    </p>

    <p>
        <label for="apellidos">
            <span class="material-symbols-outlined" aria-hidden="true">badge</span>
            Apellido/s
        </label>
        <input type="text" id="apellidos" name="apellidos"
            value="<?= e(get('apellidos')); ?>"
            data-ayuda="ayuda_apellidos" aria-describedby="ayuda_apellidos">
        <small id="ayuda_apellidos" class="ayuda">
            Opcional. Solo letras (con tildes), espacios y guiones.
        </small>
    </p>

    <p>
        <label for="email">
            <span class="icono-unicode" aria-hidden="true">&#9993;</span>
            Email *
        </label>
        <input type="text" id="email" name="email"
            value="<?= e(get('email')); ?>"
            data-ayuda="ayuda_email" aria-describedby="ayuda_email">
        <small id="ayuda_email" class="ayuda">
            Formato: parte-local@dominio (ej.: ana.lopez@ua.es).
        </small>
    </p>

    <p>
        <label for="password">
            <span class="material-symbols-outlined" aria-hidden="true">lock</span>
            Contraseña *
        </label>
        <input type="password" id="password" name="password"
            data-ayuda="ayuda_password" aria-describedby="ayuda_password">
        <small id="ayuda_password" class="ayuda">
            6 a 15 caracteres: letras inglesas, números, - y _. Al menos una mayúscula,
            una minúscula y un número.
        </small>
    </p>

    <p>
        <label for="confirmar_password">
            <span class="material-symbols-outlined" aria-hidden="true">lock</span>
            Repetir contraseña *
        </label>
        <input type="password" id="confirmar_password" name="confirmar_password"
            data-ayuda="ayuda_confirmar" aria-describedby="ayuda_confirmar">
        <small id="ayuda_confirmar" class="ayuda">
            Escríbela otra vez, igual que arriba.
        </small>
    </p>

    <p>
        <label for="sexo">Sexo *</label>
        <select id="sexo" name="sexo">
            <option value="">Selecciona una opción...</option>
            <option value="mujer" <?= get('sexo') === 'mujer' ? 'selected' : ''; ?>>Mujer</option>
            <option value="hombre" <?= get('sexo') === 'hombre' ? 'selected' : ''; ?>>Hombre</option>
        </select>
    </p>

    <p>
        <label for="fecha_nacimiento">Fecha nacimiento *</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
            value="<?= e(get('fecha_nacimiento')); ?>"
            data-ayuda="ayuda_fecha" aria-describedby="ayuda_fecha">
        <small id="ayuda_fecha" class="ayuda">
            Elige la fecha con el calendario. Debes tener 18 años cumplidos.
        </small>
    </p>

    <p>
        <label for="ciudad">Ciudad de residencia</label>
        <input type="text" id="ciudad" name="ciudad"
            value="<?= e(get('ciudad')); ?>">
    </p>

    <p>
        <label for="pais">País de residencia</label>
        <select id="pais" name="pais">
            <option value="">Selecciona un país...</option>
            <option value="argentina" <?= get('pais') === 'argentina' ? 'selected' : ''; ?>>Argentina</option>
            <option value="espana" <?= get('pais') === 'espana' ? 'selected' : ''; ?>>España</option>
            <option value="mexico" <?= get('pais') === 'mexico' ? 'selected' : ''; ?>>México</option>
        </select>
    </p>

    <p>
        <label for="foto">Foto de Perfil</label>
        <input type="file" id="foto" name="foto" accept="image/*">
    </p>

    <button type="submit">
        <span class="material-symbols-outlined" aria-hidden="true">person_add</span>
        Registrarse
    </button>

</form>

<?php
require_once "includes/layout/pie.php";
?>