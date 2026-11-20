<?php
// Ficha completa de un anuncio. Requiere las variables $anuncio (array) y $idAnuncio (int).
$car     = $anuncio['caracteristicas'] ?? [];
$autor   = buscarUsuario($anuncio['usuario'] ?? '');
$fecha   = $anuncio['fecha'] ?? '';
$fechaOk = $fecha !== '' ? date('d/m/Y', strtotime($fecha)) : '';
?>
<article>
    <h2><?= e($anuncio['titulo']); ?> <small>(Anuncio #<?= (int)$idAnuncio; ?>)</small></h2>

    <section class="fotos">
        <img src="<?= e($anuncio['foto_principal']); ?>"
            alt="Foto principal de <?= e($anuncio['titulo']); ?>"
            width="600"
            height="400">

        <h3>Más fotos</h3>

        <?php foreach (($anuncio['fotos_miniaturas'] ?? []) as $foto): ?>
            <img class="miniatura" src="<?= e($foto); ?>"
                alt="Miniatura de <?= e($anuncio['titulo']); ?>"
                width="120"
                height="80">
        <?php endforeach; ?>
    </section>

    <section class="conBorde">
        <p>
            <span class="material-symbols-outlined" aria-hidden="true">sell</span>
            Tipo de anuncio: <?= e($anuncio['tipo_anuncio']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">home</span>
            Tipo de vivienda: <?= e($anuncio['tipo_vivienda']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">location_on</span>
            Localización: <?= e($anuncio['ciudad']); ?>, <?= e($anuncio['provincia']); ?>, <?= e($anuncio['pais']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">euro</span>
            Precio: <?= euros($anuncio['precio']); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
            Fecha de publicación: <?= e($fechaOk); ?>
        </p>

        <p>
            <span class="material-symbols-outlined" aria-hidden="true">person</span>
            Anunciante: <?= e($autor['nombre'] ?? ($anuncio['usuario'] ?? '')); ?>
        </p>

        <p><?= e($anuncio['descripcion']); ?></p>

        <h3>Características</h3>

        <ul>
            <li>
                <span class="material-symbols-outlined" aria-hidden="true">square_foot</span>
                Superficie: <?= (int)($car['superficie'] ?? 0); ?> m²
            </li>
            <li>
                <span class="material-symbols-outlined" aria-hidden="true">bed</span>
                Habitaciones: <?= (int)($car['habitaciones'] ?? 0); ?>
            </li>
            <li>
                <span class="material-symbols-outlined" aria-hidden="true">bathtub</span>
                Baños: <?= (int)($car['banos'] ?? 0); ?>
            </li>
            <li>
                <span class="material-symbols-outlined" aria-hidden="true">stairs</span>
                Planta: <?= e($car['planta'] ?? ''); ?>
            </li>
            <li>
                <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
                Año de construcción: <?= (int)($car['ano_construccion'] ?? 0); ?>
            </li>
        </ul>
    </section>
</article>
