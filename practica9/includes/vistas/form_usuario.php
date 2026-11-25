<?php
$esEdicion = ($modo ?? 'registro') === 'edicion';

$mensajesError = [
    'e_usuario_vacio'     => 'El nombre de usuario es obligatorio.',
    'e_apellidos_vacios'  => 'Debes escribir al menos un apellido.',
    'e_password_vacio'    => 'La contraseña es obligatoria.',
    'e_repassword_vacio'  => 'Debes repetir la contraseña.',
    'e_password_mismatch' => 'Las contraseñas introducidas no coinciden.',
];

$errorNombre    = in_array('e_usuario_vacio', $listaErrores);
$errorApellidos = in_array('e_apellidos_vacios', $listaErrores);
$errorPassword  = in_array('e_password_vacio', $listaErrores);
$errorRepass    = in_array('e_repassword_vacio', $listaErrores) || in_array('e_password_mismatch', $listaErrores);

$msgNombre    = $errorNombre ? $mensajesError['e_usuario_vacio'] : '';
$msgApellidos = $errorApellidos ? $mensajesError['e_apellidos_vacios'] : '';
$msgPassword  = $errorPassword ? $mensajesError['e_password_vacio'] : '';
$msgRepass    = '';
if (in_array('e_repassword_vacio', $listaErrores)) {
    $msgRepass = $mensajesError['e_repassword_vacio'];
} elseif (in_array('e_password_mismatch', $listaErrores)) {
    $msgRepass = $mensajesError['e_password_mismatch'];
}
?>

<form id="form_registro" action="<?= e($actionForm); ?>" method="post" enctype="multipart/form-data" novalidate>

    <p>
        <label for="nombre">
            <span class="material-symbols-outlined" aria-hidden="true">person</span>
            Nombre *
        </label>
        <div class="campo-con-error">
            <input type="text" id="nombre" name="nombre"
                value="<?= e($datosPrevios['nombre'] ?? ''); ?>"
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

    <?php if (!$esEdicion): ?>
    <p>
        <label for="apellidos">
            <span class="material-symbols-outlined" aria-hidden="true">badge</span>
            Apellido/s *
        </label>
        <div class="campo-con-error">
            <input type="text" id="apellidos" name="apellidos"
                value="<?= e($datosPrevios['apellidos'] ?? ''); ?>"
                class="<?= $errorApellidos ? 'input-error' : ''; ?>"
                required
                data-ayuda="ayuda_apellidos" aria-describedby="ayuda_apellidos">
            <small id="ayuda_apellidos" class="ayuda">
                Escribe al menos un apellido. Solo letras (con tildes), espacios y guiones.
            </small>
            <?php if ($errorApellidos): ?>
                <span class="error-text"><?= e($msgApellidos); ?></span>
            <?php endif; ?>
        </div>
    </p>
    <?php endif; ?>

    <p>
        <label for="email">
            <span class="icono-unicode" aria-hidden="true">&#9993;</span>
            Email *
        </label>
        <div class="campo-con-error">
            <input type="text" id="email" name="email"
                value="<?= e($datosPrevios['email'] ?? ''); ?>"
                data-ayuda="ayuda_email" aria-describedby="ayuda_email">
            <small id="ayuda_email" class="ayuda">
                Formato: parte-local@dominio (ej.: ana.lopez@ua.es).
            </small>
        </div>
    </p>

    <p>
        <label for="password">
            <span class="material-symbols-outlined" aria-hidden="true">lock</span>
            <?= $esEdicion ? 'Nueva contraseña (dejar vacía para mantener la actual)' : 'Contraseña *'; ?>
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

    <p>
        <label for="confirmar_password">
            <span class="material-symbols-outlined" aria-hidden="true">lock</span>
            <?= $esEdicion ? 'Repetir nueva contraseña' : 'Repetir contraseña *'; ?>
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

    <p>
        <label for="sexo">Sexo *</label>
        <div class="campo-con-error">
            <select id="sexo" name="sexo">
                <option value="">Selecciona una opción...</option>
                <option value="2" <?= ((string)($datosPrevios['sexo'] ?? '') === '2') ? 'selected' : ''; ?>>Mujer</option>
                <option value="1" <?= ((string)($datosPrevios['sexo'] ?? '') === '1') ? 'selected' : ''; ?>>Hombre</option>
            </select>
        </div>
    </p>

    <p>
        <label for="fecha_nacimiento">Fecha nacimiento *</label>
        <div class="campo-con-error">
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                value="<?= e($datosPrevios['fecha_nacimiento'] ?? ''); ?>"
                data-ayuda="ayuda_fecha" aria-describedby="ayuda_fecha">
            <small id="ayuda_fecha" class="ayuda">
                Elige la fecha con el calendario. Debes tener 18 años cumplidos.
            </small>
        </div>
    </p>

    <p>
        <label for="ciudad">Ciudad de residencia</label>
        <div class="campo-con-error">
            <input type="text" id="ciudad" name="ciudad"
                value="<?= e($datosPrevios['ciudad'] ?? ''); ?>">
        </div>
    </p>

    <p>
        <label for="pais">País de residencia</label>
        <div class="campo-con-error">
            <select id="pais" name="pais">
                <option value="">Selecciona un país...</option>
                <?php foreach ($paises as $pais): ?>
                    <option value="<?= (int)$pais['id']; ?>"
                        <?= ((int)($datosPrevios['pais'] ?? 0) === (int)$pais['id']) ? 'selected' : ''; ?>>
                        <?= e($pais['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </p>

    <p>
        <label for="foto">Foto de Perfil</label>
        <div class="campo-con-error">
            <?php if ($esEdicion && !empty($datosPrevios['foto'])): ?>
                <p><img src="<?= e($datosPrevios['foto']); ?>" alt="Foto actual de perfil" width="90"></p>
            <?php endif; ?>
            <input type="file" id="foto" name="foto" accept="image/*">
        </div>
    </p>

    <button type="submit">
        <span class="material-symbols-outlined" aria-hidden="true">
            <?= $esEdicion ? 'save' : 'person_add'; ?>
        </span>
        <?= $esEdicion ? 'Guardar cambios' : 'Registrarse'; ?>
    </button>

</form>