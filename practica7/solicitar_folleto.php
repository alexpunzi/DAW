<?php
//cargamos las librerias y funciones auxiliares 
require_once "includes/lib/funciones.php";
require_once "includes/lib/costes.php";


$title          = "Solicitar Folleto";
$esPartePrivada = estaIdentificado();


$scriptsExtra = array_merge(
    ['js/utilidades.js'],
    scriptsValidacion(['js/validadores.js', 'js/solicitud.js']),
    ['js/folleto.js']
);


require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";


$exito        = get('exito');
$erroresRaw   = get('errores');
$listaErrores = !empty($erroresRaw) ? explode('|', $erroresRaw) : [];

// Matriz de tarifas generada dinámicamente desde costes.php
$matrizTarifas = obtenerMatrizTarifas();
?>

<section>
    <h2>Solicitud de impresión de folleto de publicidad</h2>

    <p>
        Mediante esta opción puedes solicitar la impresión que prefieras. Revisa las tarifas antes de realizar tu pedido.
    </p>

    <!-- Tabla base de tarifas estáticas -->
    <table>
        <caption>Tarifas de impresión y envío</caption>

        <thead>
            <tr>
                <th scope="col">Concepto</th>
                <th scope="col">Tarifa</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <th scope="row">Coste procesamiento y envío</th>
                <td><?= euros(COSTE_ENVIO); ?></td>
            </tr>
            <tr>
                <th scope="row">&lt; 5 páginas</th>
                <td>2 € por pág.</td>
            </tr>
            <tr>
                <th scope="row">Entre 5 y 10 páginas</th>
                <td>1,8 € por pág.</td>
            </tr>
            <tr>
                <th scope="row">&gt; 10 páginas</th>
                <td>1,6 € por pág.</td>
            </tr>
            <tr>
                <th scope="row">Blanco y negro</th>
                <td>0 €</td>
            </tr>
            <tr>
                <th scope="row">Color</th>
                <td><?= euros(PRECIO_FOTO_COLOR); ?> por foto</td>
            </tr>
            <tr>
                <th scope="row">Resolución &lt;= 300 dpi</th>
                <td>0 € por foto</td>
            </tr>
            <tr>
                <th scope="row">Resolución &gt; 300 dpi</th>
                <td><?= euros(PRECIO_FOTO_ALTA_RES); ?> por foto</td>
            </tr>
        </tbody>
    </table>
</section>

<section id="costes">
    <h3>Costes posibles de un folleto</h3>

    <p>
        Consulta cuánto costaría un folleto según el número de páginas, de fotos, el color y la resolución.
    </p>

    <button type="button" id="boton_costes" aria-expanded="false" aria-controls="tabla_costes">
        Mostrar tabla de costes
    </button>

</section>

<section id="costes_php">
    <h3>Costes de un folleto calculados con PHP</h3>

    <table id="tabla_costes_php">
        <caption>Posibles costes de un folleto de publicidad impreso (incluye <?= euros(COSTE_ENVIO); ?> de envío)</caption>
        <thead>
            <tr>
                <th scope="col">Páginas</th>
                <th scope="col">Fotos</th>
                <?php foreach ($matrizTarifas['columnas'] as $col): ?>
                    <th scope="col"><?= e($col['titulo']); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($matrizTarifas['filas'] as $fila): ?>
                <tr>
                    <th scope="row"><?= (int)$fila['paginas']; ?></th>
                    <td><?= (int)$fila['fotos']; ?></td>
                    <?php foreach ($fila['precios'] as $precio): ?>
                        <td><?= euros($precio); ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<section>

    <h3>Formulario de solicitud</h3>

    <p>
        Rellena el siguiente formulario aportando todos los detalles para confeccionar tu folleto publicitario. Los campos marcados con un asterisco (*) son obligatorios.
    </p>

    <!-- Mensajes de respuesta al enviar el formulario -->
    <?php if ($exito === '1'): ?>
        <section class="alerta-exito" role="alert">
            <p>¡Solicitud de folleto tramitada correctamente!</p>
        </section>
    <?php endif; ?>

    <?php if (!empty($listaErrores)): ?>
        <section class="alerta-error" role="alert">
            <p><strong>Por favor, corrige los siguientes errores:</strong></p>
            <ul>
                <?php foreach ($listaErrores as $error): ?>
                    <li><?= e($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <!-- Formulario redirigido al procesador backend correspondiente -->
    <form id="form_solicitud" action="<?= e(urlPrivada('respuesta_solicitar_folleto.php')); ?>" method="post" novalidate>

        <fieldset>
            <legend>Datos de contacto y envío</legend>

            <p>
                <label for="nombre">
                    <span class="material-symbols-outlined" aria-hidden="true">person</span>
                    Nombre *
                </label>
                <input type="text" id="nombre" name="nombre" value="<?= e(get('nombre')); ?>" data-ayuda="ayuda_nombre" aria-describedby="ayuda_nombre">
                <small id="ayuda_nombre" class="ayuda">Tu nombre de pila (se permiten tildes y nombres compuestos).</small>
            </p>

            <p>
                <label for="apellidos">
                    <span class="material-symbols-outlined" aria-hidden="true">badge</span>
                    Apellido/s *
                </label>
                <input type="text" id="apellidos" name="apellidos" value="<?= e(get('apellidos')); ?>" data-ayuda="ayuda_apellidos" aria-describedby="ayuda_apellidos">
                <small id="ayuda_apellidos" class="ayuda">Al menos un apellido. Solo letras, espacios y guiones.</small>
            </p>

            <p>
                <label for="correo">
                    <span class="icono-unicode" aria-hidden="true">&#9993;</span> Correo electrónico *
                </label>
                <input type="text" id="correo" name="correo" value="<?= e(get('correo')); ?>" data-ayuda="ayuda_correo" aria-describedby="ayuda_correo">
                <small id="ayuda_correo" class="ayuda">Formato: parte-local@dominio (ej.: ana.lopez@ua.es).</small>
            </p>

            <p>
                <label for="telefono">
                    <span class="icono-unicode" aria-hidden="true">&#9742;</span> Teléfono
                </label>
                <input type="tel" id="telefono" name="telefono" value="<?= e(get('telefono')); ?>" data-ayuda="ayuda_telefono" aria-describedby="ayuda_telefono">
                <small id="ayuda_telefono" class="ayuda">Opcional. Solo números, espacios y + inicial (9 a 15 cifras).</small>
            </p>

            <fieldset>
                <legend>Dirección de envío *</legend>

                <p>
                    <label for="calle">Calle *</label>
                    <input type="text" id="calle" name="calle" value="<?= e(get('calle')); ?>">
                </p>

                <p>
                    <label for="numero">Número *</label>
                    <input type="text" id="numero" name="numero" value="<?= e(get('numero')); ?>">
                </p>

                <p>
                    <label for="puerta">Puerta *</label>
                    <input type="text" id="puerta" name="puerta" value="<?= e(get('puerta')); ?>">
                </p>

                <p>
                    <label for="piso">Piso</label>
                    <input type="text" id="piso" name="piso" value="<?= e(get('piso')); ?>">
                </p>

                <p>
                    <label for="cp">Código Postal *</label>
                    <input type="text" id="cp" name="cp" value="<?= e(get('cp')); ?>" data-ayuda="ayuda_cp" aria-describedby="ayuda_cp">
                    <small id="ayuda_cp" class="ayuda">5 dígitos (ej.: 03690).</small>
                </p>

                <p>
                    <label for="localidad">Localidad *</label>
                    <input type="text" id="localidad" name="localidad" value="<?= e(get('localidad')); ?>">
                </p>

                <p>
                    <label for="provincia">Provincia *</label>
                    <input type="text" id="provincia" name="provincia" value="<?= e(get('provincia')); ?>">
                </p>

                <p>
                    <label for="pais">País *</label>
                    <select id="pais" name="pais">
                        <option value="">Selecciona un país...</option>
                        <option value="argentina" <?= get('pais') === 'argentina' ? 'selected' : ''; ?>>Argentina</option>
                        <option value="espana" <?= get('pais') === 'espana' ? 'selected' : ''; ?>>España</option>
                        <option value="mexico" <?= get('pais') === 'mexico' ? 'selected' : ''; ?>>México</option>
                    </select>
                </p>

            </fieldset>
        </fieldset>

        <fieldset>
            <legend>Detalles de impresión</legend>

            <p>
                <label for="anuncio">Anuncio a imprimir *</label>
                <select id="anuncio" name="anuncio">
                    <option value="">-- Selecciona uno de tus anuncios --</option>
                    <option value="1" <?= get('anuncio') === '1' ? 'selected' : ''; ?>>Chalet Independiente con Piscina</option>
                    <option value="2" <?= get('anuncio') === '2' ? 'selected' : ''; ?>>Estudio Luminoso para Estudiantes</option>
                    <option value="3" <?= get('anuncio') === '3' ? 'selected' : ''; ?>>Piso Céntrico con Terraza</option>
                </select>
            </p>

            <p>
                <label for="texto_adicional">Texto adicional para el folleto</label>
                <textarea
                    id="texto_adicional"
                    name="texto_adicional"
                    rows="4"
                    maxlength="4000"
                    placeholder="Información adicional a la que ya tiene el anuncio..."
                ><?= e(get('texto_adicional')); ?></textarea>
            </p>

            <p>
                <label for="color_portada">Color de la portada *</label>
                <input type="color" id="color_portada" name="color_portada" value="<?= e(get('color_portada') ?: '#000000'); ?>">
            </p>

            <p>
                <label for="num_copias">Número de copias *</label>
                <input
                    type="number"
                    id="num_copias"
                    name="num_copias"
                    value="<?= e(get('num_copias') ?: '1'); ?>" 
                    data-ayuda="ayuda_copias" 
                    aria-describedby="ayuda_copias"
                >
                <small id="ayuda_copias" class="ayuda">Entre 1 y 99 copias.</small>
            </p>

            <p>
                <label for="resolucion">Resolución (150 - 900 dpi) *</label>
                <input
                    type="range"
                    id="resolucion"
                    name="resolucion"
                    min="150"
                    max="900"
                    step="150"
                    value="<?= e(get('resolucion') ?: '150'); ?>"
                    oninput="salida_resolucion.value = resolucion.value + ' dpi'"
                >
                <output id="salida_resolucion" for="resolucion"><?= e(get('resolucion') ?: '150'); ?> dpi</output>
            </p>

            <p>
                <label for="fecha_recepcion">Fecha aproximada de recepción</label>
                <input type="date" id="fecha_recepcion" name="fecha_recepcion" value="<?= e(get('fecha_recepcion')); ?>" data-ayuda="ayuda_fecha" aria-describedby="ayuda_fecha">
                <small id="ayuda_fecha" class="ayuda">Opcional. No puede ser anterior a hoy.</small>
            </p>

            <fieldset>
                <legend>¿Impresión a color? *</legend>

                <label for="imp_color">
                    <input
                        type="radio"
                        id="imp_color"
                        name="tipo_impresion"
                        value="color"
                        <?= get('tipo_impresion') !== 'blanco_negro' ? 'checked' : ''; ?>
                    >
                    A todo color
                </label>

                <label for="imp_bn">
                    <input
                        type="radio"
                        id="imp_bn"
                        name="tipo_impresion"
                        value="blanco_negro"
                        <?= get('tipo_impresion') === 'blanco_negro' ? 'checked' : ''; ?>
                    >
                    Blanco y negro
                </label>
            </fieldset>

            <fieldset>
                <legend>¿Imprimir el precio del anuncio? *</legend>

                <label for="precio_si">
                    <input
                        type="radio"
                        id="precio_si"
                        name="imprimir_precio"
                        value="si"
                        <?= get('imprimir_precio') !== 'no' ? 'checked' : ''; ?>
                    >
                    Sí
                </label>

                <label for="precio_no">
                    <input
                        type="radio"
                        id="precio_no"
                        name="imprimir_precio"
                        value="no"
                        <?= get('imprimir_precio') === 'no' ? 'checked' : ''; ?>
                    >
                    No
                </label>
            </fieldset>

        </fieldset>

        <button type="submit">
            <span class="material-symbols-outlined" aria-hidden="true">send</span>
            Solicitar folleto
        </button>

        <button type="reset">
            <span class="material-symbols-outlined" aria-hidden="true">delete_sweep</span>
            Borrar datos
        </button>

    </form>

</section>

<?php
require_once "includes/layout/pie.php";
?>