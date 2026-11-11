<?php
require_once 'includes/bootstrap.php';
require_once "includes/lib/funciones.php";
require_once "includes/validaciones/valida_anuncio.php";

requiereUsuario();

$exito        = get('exito');
$erroresRaw   = get('errores');
$listaErrores = ($erroresRaw !== '') ? explode('|', $erroresRaw) : [];

$title          = "Crear anuncio";
$esPartePrivada = true;

require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<section>
    <h2>Crear anuncio</h2>

    <?php if ($exito === '1'): ?>
        <section class="alerta-exito" role="alert">
            <p>Los datos del anuncio son correctos. Todavía no se guardan (se hará con la base de datos).</p>
        </section>

        <h3>Datos recibidos</h3>
        <ul>
            <li><strong>Tipo de anuncio:</strong> <?= e(get('tipo_anuncio')); ?></li>
            <li><strong>Tipo de vivienda:</strong> <?= e(get('tipo_vivienda')); ?></li>
            <li><strong>Título:</strong> <?= e(get('titulo')); ?></li>
            <li><strong>Descripción:</strong> <?= e(get('descripcion')); ?></li>
            <li><strong>Precio:</strong> <?= e(get('precio')); ?> €</li>
            <li><strong>Ciudad:</strong> <?= e(get('ciudad')); ?></li>
            <li><strong>País:</strong> <?= e(paises()[get('pais')] ?? get('pais')); ?></li>
            <li><strong>Superficie:</strong> <?= e(get('superficie')); ?> m²</li>
            <li><strong>Habitaciones:</strong> <?= e(get('habitaciones')); ?></li>
            <li><strong>Baños:</strong> <?= e(get('banos')); ?></li>
            <li><strong>Planta:</strong> <?= e(get('planta')); ?></li>
            <li><strong>Año de construcción:</strong> <?= e(get('ano_construccion')); ?></li>
        </ul>
        <p><a href="<?= e(urlPrivada('crear_anuncio.php')); ?>">Crear otro anuncio</a></p>
    <?php else: ?>
        <p><small>* Campos obligatorios</small></p>

        <form action="<?= e(urlPrivada('includes/procesadores/respuesta_crear_anuncio.php')); ?>"
              method="post" enctype="multipart/form-data" novalidate>

            <!-- TIPO DE ANUNCIO -->
            <?php 
                $errTipoAnuncio = obtenerErrorCampo('tipo de anuncio', $listaErrores);
                $tieneErrorTA = !empty($errTipoAnuncio);
            ?>
            <p>
                <label for="tipo_anuncio">Tipo de anuncio *</label>
                <div class="campo-con-error">
                    <select id="tipo_anuncio" name="tipo_anuncio" class="<?= $tieneErrorTA ? 'input-error' : ''; ?>">
                        <option value="">Selecciona un tipo de anuncio...</option>
                        <?php foreach (TIPOS_ANUNCIO as $t): ?>
                            <option value="<?= e($t); ?>" <?= get('tipo_anuncio') === $t ? 'selected' : ''; ?>><?= e($t); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($tieneErrorTA): ?>
                        <span class="error-text"><?= e($errTipoAnuncio); ?></span>
                    <?php endif; ?>
                </div>
            </p>

            <!-- TIPO DE VIVIENDA -->
            <?php 
                $errTipoVivienda = obtenerErrorCampo('tipo de vivienda', $listaErrores);
                $tieneErrorTV = !empty($errTipoVivienda);
            ?>
            <p>
                <label for="tipo_vivienda">Tipo de vivienda *</label>
                <div class="campo-con-error">
                    <select id="tipo_vivienda" name="tipo_vivienda" class="<?= $tieneErrorTV ? 'input-error' : ''; ?>">
                        <option value="">Selecciona un tipo de vivienda...</option>
                        <?php foreach (TIPOS_VIVIENDA as $t): ?>
                            <option value="<?= e($t); ?>" <?= get('tipo_vivienda') === $t ? 'selected' : ''; ?>><?= e($t); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($tieneErrorTV): ?>
                        <span class="error-text"><?= e($errTipoVivienda); ?></span>
                    <?php endif; ?>
                </div>
            </p>

            <!-- TÍTULO -->
            <?php 
                $errTitulo = obtenerErrorCampo('título', $listaErrores);
                $tieneErrorTitulo = !empty($errTitulo);
            ?>
            <p>
                <label for="titulo">Título *</label>
                <div class="campo-con-error">
                    <input type="text" id="titulo" name="titulo" value="<?= e(get('titulo')); ?>" class="<?= $tieneErrorTitulo ? 'input-error' : ''; ?>">
                    <?php if ($tieneErrorTitulo): ?>
                        <span class="error-text"><?= e($errTitulo); ?></span>
                    <?php endif; ?>
                </div>
            </p>

            <!-- DESCRIPCIÓN -->
            <?php 
                $errDesc = obtenerErrorCampo('descripción', $listaErrores);
                $tieneErrorDesc = !empty($errDesc);
            ?>
            <p>
                <label for="descripcion">Descripción *</label>
                <div class="campo-con-error">
                    <textarea id="descripcion" name="descripcion" rows="5" cols="50" class="<?= $tieneErrorDesc ? 'input-error' : ''; ?>"><?= e(get('descripcion')); ?></textarea>
                    <?php if ($tieneErrorDesc): ?>
                        <span class="error-text"><?= e($errDesc); ?></span>
                    <?php endif; ?>
                </div>
            </p>

            <!-- PRECIO -->
            <?php 
                $errPrecio = obtenerErrorCampo('precio', $listaErrores);
                $tieneErrorPrecio = !empty($errPrecio);
            ?>
            <p>
                <label for="precio">Precio (€) *</label>
                <div class="campo-con-error">
                    <input type="text" id="precio" name="precio" value="<?= e(get('precio')); ?>" class="<?= $tieneErrorPrecio ? 'input-error' : ''; ?>">
                    <?php if ($tieneErrorPrecio): ?>
                        <span class="error-text"><?= e($errPrecio); ?></span>
                    <?php endif; ?>
                </div>
            </p>

            <!-- CIUDAD -->
            <?php 
                $errCiudad = obtenerErrorCampo('ciudad', $listaErrores);
                $tieneErrorCiudad = !empty($errCiudad);
            ?>
            <p>
                <label for="ciudad">Ciudad *</label>
                <div class="campo-con-error">
                    <input type="text" id="ciudad" name="ciudad" value="<?= e(get('ciudad')); ?>" class="<?= $tieneErrorCiudad ? 'input-error' : ''; ?>">
                    <?php if ($tieneErrorCiudad): ?>
                        <span class="error-text"><?= e($errCiudad); ?></span>
                    <?php endif; ?>
                </div>
            </p>

            <!-- PAÍS -->
            <?php 
                $errPais = obtenerErrorCampo('país', $listaErrores);
                $tieneErrorPais = !empty($errPais);
            ?>
            <p>
                <label for="pais">País *</label>
                <div class="campo-con-error">
                    <select id="pais" name="pais" class="<?= $tieneErrorPais ? 'input-error' : ''; ?>">
                        <option value="">Selecciona un país...</option>
                        <?php foreach (paises() as $clave => $nombre): ?>
                            <option value="<?= e($clave); ?>" <?= get('pais') === $clave ? 'selected' : ''; ?>><?= e($nombre); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($tieneErrorPais): ?>
                        <span class="error-text"><?= e($errPais); ?></span>
                    <?php endif; ?>
                </div>
            </p>

            <fieldset>
                <legend>Características</legend>

                <!-- SUPERFICIE -->
                <?php 
                    $errSup = obtenerErrorCampo('superficie', $listaErrores);
                    $tieneErrorSup = !empty($errSup);
                ?>
                <p>
                    <label for="superficie">Superficie (m²)</label>
                    <div class="campo-con-error">
                        <input type="text" id="superficie" name="superficie" value="<?= e(get('superficie')); ?>" class="<?= $tieneErrorSup ? 'input-error' : ''; ?>">
                        <?php if ($tieneErrorSup): ?>
                            <span class="error-text"><?= e($errSup); ?></span>
                        <?php endif; ?>
                    </div>
                </p>

                <!-- HABITACIONES -->
                <?php 
                    $errHab = obtenerErrorCampo('habitaciones', $listaErrores);
                    $tieneErrorHab = !empty($errHab);
                ?>
                <p>
                    <label for="habitaciones">Número de habitaciones</label>
                    <div class="campo-con-error">
                        <input type="text" id="habitaciones" name="habitaciones" value="<?= e(get('habitaciones')); ?>" class="<?= $tieneErrorHab ? 'input-error' : ''; ?>">
                        <?php if ($tieneErrorHab): ?>
                            <span class="error-text"><?= e($errHab); ?></span>
                        <?php endif; ?>
                    </div>
                </p>

                <!-- BAÑOS -->
                <?php 
                    $errBanos = obtenerErrorCampo('baños', $listaErrores);
                    $tieneErrorBanos = !empty($errBanos);
                ?>
                <p>
                    <label for="banos">Número de baños</label>
                    <div class="campo-con-error">
                        <input type="text" id="banos" name="banos" value="<?= e(get('banos')); ?>" class="<?= $tieneErrorBanos ? 'input-error' : ''; ?>">
                        <?php if ($tieneErrorBanos): ?>
                            <span class="error-text"><?= e($errBanos); ?></span>
                        <?php endif; ?>
                    </div>
                </p>

                <!-- PLANTA -->
                <?php 
                    $errPlanta = obtenerErrorCampo('planta', $listaErrores);
                    $tieneErrorPlanta = !empty($errPlanta);
                ?>
                <p>
                    <label for="planta">Planta</label>
                    <div class="campo-con-error">
                        <input type="text" id="planta" name="planta" value="<?= e(get('planta')); ?>" class="<?= $tieneErrorPlanta ? 'input-error' : ''; ?>">
                        <?php if ($tieneErrorPlanta): ?>
                            <span class="error-text"><?= e($errPlanta); ?></span>
                        <?php endif; ?>
                    </div>
                </p>

                <!-- AÑO DE CONSTRUCCIÓN -->
                <?php 
                    $errAno = obtenerErrorCampo('año', $listaErrores);
                    $tieneErrorAno = !empty($errAno);
                ?>
                <p>
                    <label for="ano_construccion">Año de construcción</label>
                    <div class="campo-con-error">
                        <input type="text" id="ano_construccion" name="ano_construccion" value="<?= e(get('ano_construccion')); ?>" class="<?= $tieneErrorAno ? 'input-error' : ''; ?>">
                        <?php if ($tieneErrorAno): ?>
                            <span class="error-text"><?= e($errAno); ?></span>
                        <?php endif; ?>
                    </div>
                </p>
            </fieldset>

            <p>
                <label for="foto_principal">Foto principal</label>
                <input type="file" id="foto_principal" name="foto_principal" accept="image/*">
            </p>

            <button type="submit">
                <span class="material-symbols-outlined" aria-hidden="true">add_box</span>
                Crear anuncio
            </button>
        </form>
    <?php endif; ?>
</section>

<?php
require_once "includes/layout/pie.php";
?>