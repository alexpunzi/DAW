<?php
// Ficha completa de un anuncio. Requiere las variables $anuncio (array) y $idAnuncio (int).
$car     = $anuncio['caracteristicas'] ?? [];
$fecha   = $anuncio['fecha'] ?? '';
$fechaOk = $fecha !== '' ? date('d/m/Y', strtotime($fecha)) : '';
$totalFotos = (!empty($anuncio['foto_principal']) ? 1 : 0) + count($anuncio['fotos_miniaturas'] ?? []);
?>
<article>
    <h2><?= e($anuncio['titulo']); ?></h2>

    <section class="fotos">
        <img src="<?= e($anuncio['foto_principal']); ?>"
            alt="<?= e(!empty($anuncio['alternativo']) ? $anuncio['alternativo'] : 'Foto principal de ' . $anuncio['titulo']); ?>"
            width="600"
            height="400">

        <h3>Más fotos</h3>

      <?php foreach (($anuncio['fotos_miniaturas'] ?? []) as $foto): ?>
            <img class="miniatura" src="<?= e($foto); ?>"
                alt="Miniatura de <?= e($anuncio['titulo']); ?>"
                width="120"
                height="80">
        <?php endforeach; ?>

        <p>
            <a href="<?= e(urlPrivada('ver_fotos.php', ['id' => $idAnuncio])); ?>">
                <span class="material-symbols-outlined" aria-hidden="true">photo_library</span>
                Ver todas las fotos (<?= (int)$totalFotos; ?>)
            </a>
        </p>
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
            Localización: <?= e($anuncio['ciudad']); ?><?= !empty($anuncio['provincia']) ? ', ' . e($anuncio['provincia']) : ''; ?>, <?= e($anuncio['pais']); ?>
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
            Anunciante:
            <a href="perfil_usuario.php?id=<?= (int)$anuncio['id_usuario']; ?>">
                <?= e($anuncio['usuario'] ?? ''); ?>
            </a>
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