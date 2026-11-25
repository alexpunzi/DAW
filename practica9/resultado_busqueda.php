<?php
require_once 'includes/bootstrap.php';
require_once 'includes/repositorios/carga_anuncios.php';

$title          = "Resultados de búsqueda";
$esPartePrivada = estaIdentificado();

// CRITERIOS RECIBIDOS
$etiquetas = [
    'texto_busqueda' => 'Búsqueda rápida',
    'tipo_anuncio'   => 'Tipo de anuncio',
    'tipo_vivienda'  => 'Tipo de vivienda',
    'ciudad'         => 'Ciudad',
    'pais'           => 'País',
    'precio_desde'   => 'Precio desde (€)',
    'precio_hasta'   => 'Precio hasta (€)',
    'fecha_desde'    => 'Fecha de publicación desde',
    'fecha_hasta'    => 'Fecha de publicación hasta'
];

$valoresLegibles = [
    'tipo_anuncio'  => ['venta' => 'Venta', 'alquiler' => 'Alquiler'],
    'tipo_vivienda' => [
        'garaje'     => 'Garaje',
        'local'      => 'Local',
        'obra_nueva' => 'Obra nueva',
        'oficina'    => 'Oficina',
        'vivienda'   => 'Vivienda'
    ],
    'pais'          => paises()
];

$criterios = [];
foreach ($etiquetas as $campo => $etiqueta) {
    $valor = get($campo);
    if ($valor !== '') {
        $criterios[$campo] = $valor;
    }
}

// Comprobaciones de los criterios numéricos y de fecha
$errores = [];
foreach (['precio_desde', 'precio_hasta'] as $campo) {
    if (isset($criterios[$campo]) && (!is_numeric($criterios[$campo]) || (float)$criterios[$campo] < 0)) {
        $errores[] = $etiquetas[$campo] . ' debe ser un número no negativo.';
    }
}
foreach (['fecha_desde', 'fecha_hasta'] as $campo) {
    if (isset($criterios[$campo])) {
        $d = DateTime::createFromFormat('Y-m-d', $criterios[$campo]);
        if ($d === false || $d->format('Y-m-d') !== $criterios[$campo]) {
            $errores[] = $etiquetas[$campo] . ' no es una fecha válida.';
        }
    }
}
if (isset($criterios['precio_desde'], $criterios['precio_hasta'])
    && is_numeric($criterios['precio_desde']) && is_numeric($criterios['precio_hasta'])
    && (float)$criterios['precio_desde'] > (float)$criterios['precio_hasta']) {
    $errores[] = 'El precio mínimo no puede ser mayor que el máximo.';
}

// Si no hay errores de validación, consultamos en MySQL
$resultados = empty($errores) ? buscarAnuncios($criterios) : [];

// Si se usó búsqueda rápida, preparamos el desglose interpretado para mostrarlo
$interpretacionRapida = null;
if (isset($criterios['texto_busqueda'])) {
    $interpretacionRapida = parsearBusquedaRapida($criterios['texto_busqueda']);
}

require_once "includes/layout/cabecera.php";
require_once "includes/layout/inicio.php";
?>

<section>
    <h2>Resultados de la búsqueda</h2>

    <?php if (!empty($errores)): ?>
        <section class="alerta-error" role="alert">
            <p><strong>Revisa los criterios de búsqueda:</strong></p>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= e($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php if (empty($criterios)): ?>
        <p>No has indicado ningún criterio de búsqueda, se muestran todos los anuncios.</p>
    <?php else: ?>
        <p>Criterios de búsqueda introducidos:</p>
        <ul>
            <?php foreach ($criterios as $campo => $valor): ?>
                <li>
                    <strong><?= e($etiquetas[$campo]); ?>:</strong>
                    <?= e($valoresLegibles[$campo][$valor] ?? $valor); ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($interpretacionRapida !== null): ?>
            <p>
                <small>
                    <strong>Filtros detectados:</strong>
                    Tipo de vivienda: <em><?= e($interpretacionRapida['tipo_vivienda'] ?? 'cualquiera'); ?></em> |
                    Tipo de anuncio: <em><?= e($interpretacionRapida['tipo_anuncio'] ?? 'cualquiera'); ?></em> |
                    Ciudad: <em><?= e($interpretacionRapida['ciudad'] ?? 'cualquiera'); ?></em>
                </small>
            </p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (empty($errores)): ?>
        <?php if (empty($resultados)): ?>
            <p>No se han encontrado anuncios que coincidan con los criterios seleccionados.</p>
            <p><a href="buscar.php">Realizar una nueva búsqueda</a></p>
        <?php else: ?>
            <p>Se han encontrado <strong><?= count($resultados); ?></strong> anuncio(s).</p>

            <ul class="cards-grid">
                <?php foreach ($resultados as $anuncio): ?>
                    <li class="card">
                        <h3>
                            <a href="<?= e(urlPrivada('anuncio.php', ['id' => $anuncio['id']])); ?>">
                                <?= e($anuncio['titulo']); ?>
                            </a>
                        </h3>

                        <img src="<?= e($anuncio['foto_principal']); ?>"
                             alt="<?= e($anuncio['alternativo']); ?>"
                             width="600"
                             height="400">

                        <p>
                            <b><span class="material-symbols-outlined" aria-hidden="true">home_work</span> Tipo</b>
                            <?= e($anuncio['tipo_vivienda']); ?> (<?= e($anuncio['tipo_anuncio']); ?>)
                        </p>
                        <p>
                            <b><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Localización</b>
                            <?= e($anuncio['ciudad']); ?>, <?= e($anuncio['pais']); ?>
                        </p>
                        <p>
                            <b><span class="material-symbols-outlined" aria-hidden="true">euro</span> Precio</b>
                            <?= euros($anuncio['precio']); ?>
                        </p>
                        <p>
                            <b><span class="material-symbols-outlined" aria-hidden="true">calendar_month</span> Publicado el</b>
                            <?= e(date('d/m/Y', strtotime($anuncio['fecha']))); ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php
require_once "includes/layout/pie.php";
?>