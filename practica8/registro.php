<?php
require_once "includes/lib/funciones.php";

//configuracion de las vartiables especificas 
$title        = "Registro";
$esPartePrivada = false; // muestra la navegación pública 

// Mantenemos los scripts de validación JavaScript frontend
$scriptsExtra = scriptsValidacion([
    'js/utilidades.js',
    'js/validadores.js',
    'js/registro.js'
]);


require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";


$exito        = get('exito');
$erroresRaw   = get('errores');
$listaErrores = !empty($erroresRaw) ? explode(',', $erroresRaw) : [];

// Mapeo de códigos de error a mensajes legibles
$mensajesError = [
    'e_usuario_vacio'     => 'El nombre de usuario es obligatorio.',
    'e_password_vacio'    => 'La contraseña es obligatoria.',
    'e_repassword_vacio'  => 'Debes repetir la contraseña.',
    'e_password_mismatch' => 'Las contraseñas introducidas no coinciden.'
];

// Comprobaciones individuales por cada campo según los códigos recibidos
$errorNombre   = in_array('e_usuario_vacio', $listaErrores);
$errorPassword = in_array('e_password_vacio', $listaErrores);
$errorRepass   = in_array('e_repassword_vacio', $listaErrores) || in_array('e_password_mismatch', $listaErrores);

// Mensajes específicos para mostrar debajo de cada caja
$msgNombre = $errorNombre ? $mensajesError['e_usuario_vacio'] : '';
$msgPassword = $errorPassword ? $mensajesError['e_password_vacio'] : '';
$msgRepass = '';
if (in_array('e_repassword_vacio', $listaErrores)) {
    $msgRepass = $mensajesError['e_repassword_vacio'];
} elseif (in_array('e_password_mismatch', $listaErrores)) {
    $msgRepass = $mensajesError['e_password_mismatch'];
}
?>

<h2>Registro de Usuario</h2>

<p><small>* Campos obligatorios</small></p>


<?php if ($exito === '1'): ?>
    <section class="alerta-exito" role="alert">
        <p>¡Registro completado con éxito! Bienvenido/a, <strong><?= e(get('nombre')); ?></strong>.</p>
    </section>

    <!-- Datos recibidos (por seguridad no se muestran las contraseñas; la foto se tratará en otra práctica) -->
    <section>
        <h3>Datos introducidos</h3>
        <ul>
            <li><strong>Nombre:</strong> <?= e(get('nombre')); ?></li>
            <li><strong>Apellido/s:</strong> <?= e(get('apellidos')); ?></li>
            <li><strong>Email:</strong> <?= e(get('email')); ?></li>
            <li><strong>Sexo:</strong> <?= e(ucfirst(get('sexo'))); ?></li>
            <li><strong>Fecha de nacimiento:</strong> <?= e(get('fecha_nacimiento')); ?></li>
            <li><strong>Ciudad:</strong> <?= e(get('ciudad')); ?></li>
            <li><strong>País:</strong> <?= e(paises()[get('pais')] ?? get('pais')); ?></li>
        </ul>
        <p><a href="index.php">Ir a la página principal para iniciar sesión</a></p>
    </section>
<?php endif; ?>


<?php if ($exito !== '1'): ?>
<form id="form_registro" action="includes/procesadores/respuesta_registro.php" method="post" enctype="multipart/form-data" novalidate>

    <!-- NOMBRE -->
    <p>
        <label for="nombre">
            <span class="material-symbols-outlined" aria-hidden="true">person</span>
            Nombre *
        </label>
        <div class="campo-con-error">
            <input type="text" id="nombre" name="nombre"
                value="<?= e(get('nombre')); ?>"
                class="<?= $errorNombre ? 'input-error' : ''; ?>"
                data-ayuda="ayuda_nombre" aria-describedby="ayuda_nombre">
            <small id="ayuda_nombre" class="ayuda">
                3 a 15 caracteres: letras inglesas y números. No puede empezar por número.
            </small>
            <?php if ($errorNombre): ?>
                <span class="error-text"><?= e($msgNombre); ?></span>
            <?php endif; ?>
        </div>
    </p>

    <!-- APELLIDOS -->
    <p>
        <label for="apellidos">
            <span class="material-symbols-outlined" aria-hidden="true">badge</span>
            Apellido/s
        </label>
        <div class="campo-con-error">
            <input type="text" id="apellidos" name="apellidos"
                value="<?= e(get('apellidos')); ?>"
                data-ayuda="ayuda_apellidos" aria-describedby="ayuda_apellidos">
            <small id="ayuda_apellidos" class="ayuda">
                Opcional. Solo letras (con tildes), espacios y guiones.
            </small>
        </div>
    </p>

    <!-- EMAIL -->
    <p>
        <label for="email">
            <span class="icono-unicode" aria-hidden="true">&#9993;</span>
            Email *
        </label>
        <div class="campo-con-error">
            <input type="text" id="email" name="email"
                value="<?= e(get('email')); ?>"
                data-ayuda="ayuda_email" aria-describedby="ayuda_email">
            <small id="ayuda_email" class="ayuda">
                Formato: parte-local@dominio (ej.: ana.lopez@ua.es).
            </small>
        </div>
    </p>

    <!-- CONTRASEÑA -->
    <p>
        <label for="password">
            <span class="material-symbols-outlined" aria-hidden="true">lock</span>
            Contraseña *
        </label>
        <div class="campo-con-error">
            <input type="password" id="password" name="password"
                class="<?= $errorPassword ? 'input-error' : ''; ?>"
                data-ayuda="ayuda_password" aria-describedby="ayuda_password">
            <small id="ayuda_password" class="ayuda">
                6 a 15 caracteres: letras inglesas, números, - y _. Al menos una mayúscula,
                una minúscula y un número.
            </small>
            <?php if ($errorPassword): ?>
                <span class="error-text"><?= e($msgPassword); ?></span>
            <?php endif; ?>
        </div>
    </p>

    <!-- REPETIR CONTRASEÑA -->
    <p>
        <label for="confirmar_password">
            <span class="material-symbols-outlined" aria-hidden="true">lock</span>
            Repetir contraseña *
        </label>
        <div class="campo-con-error">
            <input type="password" id="confirmar_password" name="confirmar_password"
                class="<?= $errorRepass ? 'input-error' : ''; ?>"
                data-ayuda="ayuda_confirmar" aria-describedby="ayuda_confirmar">
            <small id="ayuda_confirmar" class="ayuda">
                Escríbela otra vez, igual que arriba.
            </small>
            <?php if ($errorRepass): ?>
                <span class="error-text"><?= e($msgRepass); ?></span>
            <?php endif; ?>
        </div>
    </p>

    <!-- SEXO -->
    <p>
        <label for="sexo">Sexo *</label>
        <div class="campo-con-error">
            <select id="sexo" name="sexo">
                <option value="">Selecciona una opción...</option>
                <option value="mujer" <?= get('sexo') === 'mujer' ? 'selected' : ''; ?>>Mujer</option>
                <option value="hombre" <?= get('sexo') === 'hombre' ? 'selected' : ''; ?>>Hombre</option>
            </select>
        </div>
    </p>

    <!-- FECHA NACIMIENTO -->
    <p>
        <label for="fecha_nacimiento">Fecha nacimiento *</label>
        <div class="campo-con-error">
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                value="<?= e(get('fecha_nacimiento')); ?>"
                data-ayuda="ayuda_fecha" aria-describedby="ayuda_fecha">
            <small id="ayuda_fecha" class="ayuda">
                Elige la fecha con el calendario. Debes tener 18 años cumplidos.
            </small>
        </div>
    </p>

    <!-- CIUDAD -->
    <p>
        <label for="ciudad">Ciudad de residencia</label>
        <div class="campo-con-error">
            <input type="text" id="ciudad" name="ciudad"
                value="<?= e(get('ciudad')); ?>">
        </div>
    </p>

    <!-- PAÍS -->
    <p>
        <label for="pais">País de residencia</label>
        <div class="campo-con-error">
            <select id="pais" name="pais">
                <option value="">Selecciona un país...</option>
                <option value="argentina" <?= get('pais') === 'argentina' ? 'selected' : ''; ?>>Argentina</option>
                <option value="espana" <?= get('pais') === 'espana' ? 'selected' : ''; ?>>España</option>
                <option value="mexico" <?= get('pais') === 'mexico' ? 'selected' : ''; ?>>México</option>
            </select>
        </div>
    </p>

    <!-- FOTO -->
    <p>
        <label for="foto">Foto de Perfil</label>
        <div class="campo-con-error">
            <input type="file" id="foto" name="foto" accept="image/*">
        </div>
    </p>

    <button type="submit">
        <span class="material-symbols-outlined" aria-hidden="true">person_add</span>
        Registrarse
    </button>

</form>
<?php endif; ?>

<?php
require_once "includes/layout/pie.php";
?>