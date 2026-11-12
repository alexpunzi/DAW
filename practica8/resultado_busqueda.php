<?php
require_once 'includes/bootstrap.php';
require_once "includes/lib/funciones.php";

$title          = "Resultados de búsqueda";
$esPartePrivada = estaIdentificado();


//CRITERIOS RECIBIDOS 
$etiquetas = [
    'texto_busqueda' => 'Palabra clave',
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
    'tipo_vivienda' => ['garaje' => 'Garaje', 'local' => 'Local', 'obra_nueva' => 'Obra nueva',
                        'oficina' => 'Oficina', 'vivienda' => 'Vivienda'],
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
    <?php endif; ?>

    <p>Se han encontrado 2 anuncios que coinciden con tus criterios:</p>

    <!-- Resultado de ejemplo -->
    <ul class="cards-grid">
        <li class="card">
            <h3><a href="<?= e(urlPrivada('anuncio.php', ['id' => 4])); ?>">Piso Amplio en Ruzafa</a></h3>

            <img src="img/piso-valencia.jpg"
                alt="Piso amplio en el barrio de Ruzafa, Valencia"
                width="600"
                height="400">

            <p><b><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Localización</b> Valencia, Valencia, España</p>
            <p><b><span class="material-symbols-outlined" aria-hidden="true">euro</span> Precio</b> <?= euros(240000); ?></p>
            <p><b><span class="material-symbols-outlined" aria-hidden="true">calendar_month</span> Publicado el</b> 01/11/2026</p>
        </li>

        <li class="card">
            <h3><a href="<?= e(urlPrivada('anuncio.php', ['id' => 1])); ?>">Chalet Independiente con Piscina</a></h3>

            <img src="img/chalet-piscina.jpg"
                alt="Chalet independiente con piscina en Altea"
                width="600"
                height="400">

            <p><b><span class="material-symbols-outlined" aria-hidden="true">location_on</span> Localización</b> Altea, Alicante, España</p>
            <p><b><span class="material-symbols-outlined" aria-hidden="true">euro</span> Precio</b> <?= euros(320000); ?></p>
            <p><b><span class="material-symbols-outlined" aria-hidden="true">calendar_month</span> Publicado el</b> 15/10/2026</p>
        </li>
    </ul>
</section>

<?php
require_once "includes/layout/pie.php";
?>
