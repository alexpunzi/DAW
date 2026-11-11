<?php
require_once 'includes/bootstrap.php';
require_once "includes/lib/funciones.php";

$usuario  = requiereUsuario();
$anuncios = anunciosDeUsuario($usuario['email']);


$idsPropios = array_column($anuncios, 'id');
$idFijo     = leerId(get('anuncio'));
if ($idFijo !== null && !in_array($idFijo, $idsPropios, true)) {
    $idFijo = null;
}
$idSeleccionado = leerId(get('seleccionado')); // se conserva tras un error

$erroresRaw   = get('errores');
$listaErrores = ($erroresRaw !== '') ? explode('|', $erroresRaw) : [];

$title          = "Añadir foto a anuncio";
$esPartePrivada = true;

require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<section>
    <h2>Añadir foto a un anuncio</h2>

    <p><small>* Campos obligatorios</small></p>

    <?php if (empty($anuncios)): ?>
        <p>
            No tienes anuncios a los que añadir fotos.
            <a href="<?= e(urlPrivada('crear_anuncio.php')); ?>">Crea un anuncio</a> primero.
        </p>
    <?php else: ?>
        <form action="<?= e(urlPrivada('includes/procesadores/respuesta_anadir_foto.php')); ?>"
              method="post" enctype="multipart/form-data" novalidate>

            <!-- FOTO -->
            <?php 
                $errFoto = obtenerErrorCampo('foto', $listaErrores);
                $tieneErrorFoto = !empty($errFoto);
            ?>
            <p>
                <label for="foto">Foto *</label>
                <div class="campo-con-error">
                    <input type="file" id="foto" name="foto" accept="image/*" class="<?= $tieneErrorFoto ? 'input-error' : ''; ?>">
                    <?php if ($tieneErrorFoto): ?>
                        <span class="error-text"><?= e($errFoto); ?></span>
                    <?php endif; ?>
                </div>
            </p>

            <!-- TEXTO ALTERNATIVO -->
            <?php 
                $errAlt = obtenerErrorCampo('alternativo', $listaErrores);
                $tieneErrorAlt = !empty($errAlt);
            ?>
            <p>
                <label for="alt">Texto alternativo * (mínimo 10 caracteres)</label>
                <div class="campo-con-error">
                    <input type="text" id="alt" name="alt" value="<?= e(get('alt')); ?>" class="<?= $tieneErrorAlt ? 'input-error' : ''; ?>">
                    <?php if ($tieneErrorAlt): ?>
                        <span class="error-text"><?= e($errAlt); ?></span>
                    <?php endif; ?>
                </div>
            </p>

            <!-- TÍTULO DE LA FOTO -->
            <?php 
                $errTituloFoto = obtenerErrorCampo('título', $listaErrores);
                $tieneErrorTituloFoto = !empty($errTituloFoto);
            ?>
            <p>
                <label for="titulo_foto">Título *</label>
                <div class="campo-con-error">
                    <input type="text" id="titulo_foto" name="titulo_foto" value="<?= e(get('titulo_foto')); ?>" class="<?= $tieneErrorTituloFoto ? 'input-error' : ''; ?>">
                    <?php if ($tieneErrorTituloFoto): ?>
                        <span class="error-text"><?= e($errTituloFoto); ?></span>
                    <?php endif; ?>
                </div>
            </p>

            <!-- ANUNCIO -->
            <?php 
                $errAnuncio = obtenerErrorCampo('anuncio', $listaErrores);
                $tieneErrorAnuncio = !empty($errAnuncio);
            ?>
            <p>
                <label for="anuncio">Anuncio *</label>
                <div class="campo-con-error">
                    <?php if ($idFijo !== null): ?>
                        <!-- Anuncio fijo: el select disabled no se envía, por eso va también en un campo oculto -->
                        <select id="anuncio" disabled class="<?= $tieneErrorAnuncio ? 'input-error' : ''; ?>">
                            <?php foreach ($anuncios as $a): ?>
                                <option value="<?= (int)$a['id']; ?>" <?= $a['id'] === $idFijo ? 'selected' : ''; ?>>
                                    <?= e($a['titulo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="anuncio" value="<?= $idFijo; ?>">
                        <input type="hidden" name="fijo" value="1">
                    <?php else: ?>
                        <select id="anuncio" name="anuncio" class="<?= $tieneErrorAnuncio ? 'input-error' : ''; ?>">
                            <option value="">-- Selecciona un anuncio --</option>
                            <?php foreach ($anuncios as $a): ?>
                                <option value="<?= (int)$a['id']; ?>" <?= $a['id'] === $idSeleccionado ? 'selected' : ''; ?>>
                                    <?= e($a['titulo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <?php if ($tieneErrorAnuncio): ?>
                        <span class="error-text"><?= e($errAnuncio); ?></span>
                    <?php endif; ?>
                </div>
            </p>

            <button type="submit">
                <span class="material-symbols-outlined" aria-hidden="true">add_a_photo</span>
                Añadir foto
            </button>
        </form>
    <?php endif; ?>
</section>

<?php
require_once "includes/layout/pie.php";
?>